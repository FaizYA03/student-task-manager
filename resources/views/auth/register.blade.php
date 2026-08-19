@extends('layouts.app')

@section('title', 'Pendaftaran Akun Mahasiswa - Student Task Manager')

@section('content')
<div class="min-h-[70vh] flex flex-col justify-center items-center py-6">
    <div class="w-full max-w-md">

        <!-- Card Container -->
        <div class="bg-white border border-slate-200 rounded-3xl shadow-lg p-6 sm:p-10 space-y-6">

            <!-- Card Header -->
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-indigo-500 flex items-center justify-center text-white mx-auto shadow-md shadow-indigo-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Daftar Akun Mahasiswa</h1>
                <p class="text-xs sm:text-sm text-slate-500">Lengkapi data di bawah ini untuk mulai mengatur tugas kuliah Anda.</p>
            </div>

            <!-- Register Form -->
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <!-- NIM Input -->
                <div>
                    <label for="nim" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nomor Induk Mahasiswa (NIM) <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="nim"
                        id="nim"
                        value="{{ old('nim') }}"
                        placeholder="Contoh: 202401001"
                        required
                        autofocus
                        class="w-full px-4 py-2.5 bg-slate-50 border @error('nim') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                    >
                    @error('nim')
                        <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Lengkap Input -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Budi Pratama"
                        required
                        class="w-full px-4 py-2.5 bg-slate-50 border @error('name') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                    >
                    @error('name')
                        <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Email Kampus / Pribadi <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        placeholder="budi@student.ac.id"
                        required
                        class="w-full px-4 py-2.5 bg-slate-50 border @error('email') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                    >
                    @error('email')
                        <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Password <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Minimal 6 karakter"
                        required
                        class="w-full px-4 py-2.5 bg-slate-50 border @error('password') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                    >
                    @error('password')
                        <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation Input -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Konfirmasi Password <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        placeholder="Ulangi password di atas"
                        required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                    >
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold text-sm rounded-xl shadow-md hover:shadow-lg shadow-indigo-100 transition-all duration-150 transform active:scale-95 cursor-pointer flex items-center justify-center gap-2 mt-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Daftar Akun Mahasiswa
                </button>
            </form>

            <!-- Card Footer / Login Link -->
            <div class="pt-4 border-t border-slate-100 text-center">
                <p class="text-xs text-slate-500">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                        Masuk di Sini
                    </a>
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
