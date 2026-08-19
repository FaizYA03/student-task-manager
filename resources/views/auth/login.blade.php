@extends('layouts.app')

@section('title', 'Login Mahasiswa - Student Task Manager')

@section('content')
<div class="min-h-[70vh] flex flex-col justify-center items-center py-6">
    <div class="w-full max-w-md">

        <!-- Card Container -->
        <div class="bg-white border border-slate-200 rounded-3xl shadow-lg p-6 sm:p-10 space-y-6">

            <!-- Card Header -->
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-indigo-500 flex items-center justify-center text-white mx-auto shadow-md shadow-indigo-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Portal Masuk Mahasiswa</h1>
                <p class="text-xs sm:text-sm text-slate-500">Masukkan NIM dan Password untuk mengakses daftar tugas Anda.</p>
            </div>

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf

                <!-- NIM Input -->
                <div>
                    <label for="nim" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nomor Induk Mahasiswa (NIM)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="nim"
                            id="nim"
                            value="{{ old('nim') }}"
                            placeholder="Contoh: 202401001"
                            required
                            autofocus
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border @error('nim') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                        >
                    </div>
                    @error('nim')
                        <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="••••••••"
                            required
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border @error('password') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                        >
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Anti-Bot Random Math Challenge -->
                <div class="p-4 bg-indigo-50/70 border border-indigo-100 rounded-2xl space-y-3">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-lg bg-indigo-600 text-white flex items-center justify-center shrink-0 text-xs font-bold">
                            🤖
                        </div>
                        <div class="text-xs font-bold text-indigo-950">
                            Tugas Anti-Bot (Verifikasi Ringan)
                        </div>
                    </div>
                    <p class="text-[11px] text-indigo-800/80 leading-relaxed">
                        Selesaikan penjumlahan matematika acak berikut untuk membuktikan Anda bukan bot:
                    </p>

                    <div class="flex items-center gap-3">
                        <!-- Math Badge -->
                        <div class="px-4 py-2 bg-white border border-indigo-200 rounded-xl font-mono text-base font-extrabold text-indigo-700 tracking-wider shadow-xs select-none">
                            {{ $captchaQuestion }} = ?
                        </div>

                        <!-- Answer Input -->
                        <div class="flex-1">
                            <input
                                type="number"
                                name="captcha"
                                id="captcha"
                                placeholder="Jawaban"
                                required
                                class="w-full px-3.5 py-2 bg-white border @error('captcha') border-rose-400 bg-rose-50/30 @else border-indigo-200 @enderror rounded-xl text-slate-900 placeholder-slate-400 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
                            >
                        </div>
                    </div>
                    @error('captcha')
                        <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                        <span class="text-xs font-medium text-slate-600">Ingat Saya</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold text-sm rounded-xl shadow-md hover:shadow-lg shadow-indigo-100 transition-all duration-150 transform active:scale-95 cursor-pointer flex items-center justify-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Masuk ke Student Task Manager
                </button>
            </form>

            <!-- Card Footer / Register Link -->
            <div class="pt-4 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">
                    Mahasiswa baru belum punya akun?
                    <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                        Daftar Akun Baru
                    </a>
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
