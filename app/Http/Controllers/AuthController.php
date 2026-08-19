<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Menampilkan form login dengan pertanyaan penjumlahan anti-bot acak.
     */
    public function showLoginForm(): View
    {
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);

        // Simpan jawaban captcha di session
        session([
            'captcha_answer' => $num1 + $num2,
            'captcha_question' => "$num1 + $num2",
        ]);

        return view('auth.login', [
            'captchaQuestion' => "$num1 + $num2",
        ]);
    }

    /**
     * Proses autentikasi login mahasiswa.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'nim' => ['required', 'string'],
            'password' => ['required', 'string'],
            'captcha' => ['required', 'numeric'],
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'captcha.required' => 'Hasil penjumlahan anti-bot wajib diisi.',
            'captcha.numeric' => 'Jawaban anti-bot harus berupa angka.',
        ]);

        // Verifikasi Captcha Anti-Bot
        $expectedAnswer = session('captcha_answer');
        if (is_null($expectedAnswer) || (int) $request->captcha !== (int) $expectedAnswer) {
            return back()
                ->withInput($request->except(['password', 'captcha']))
                ->withErrors([
                    'captcha' => 'Hasil penjumlahan verifikasi salah. Silakan hitung kembali untuk membuktikan bukan bot.',
                ]);
        }

        // Hapus token captcha setelah berhasil diverifikasi
        session()->forget(['captcha_answer', 'captcha_question']);

        // Cek kredensial NIM & Password
        $remember = $request->boolean('remember');
        if (Auth::attempt(['nim' => $request->nim, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();

            return redirect()
                ->intended(route('tasks.index'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()
            ->withInput($request->except(['password', 'captcha']))
            ->withErrors([
                'nim' => 'NIM atau password yang Anda masukkan tidak cocok.',
            ]);
    }

    /**
     * Menampilkan form pendaftaran akun mahasiswa baru.
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi akun mahasiswa baru.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nim' => ['required', 'string', 'max:20', 'unique:users,nim'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM ini sudah terdaftar sebelumnya.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'nim' => $validated['nim'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Akun mahasiswa berhasil didaftarkan! Selamat datang di Student Task Manager.');
    }

    /**
     * Logout pengguna dan hancurkan session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
