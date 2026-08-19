@extends('layouts.app')

@section('title', 'Tambah Task Baru - Student Task Manager')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header Breadcrumb / Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-xs p-6 sm:p-8">
        <div class="border-b border-slate-100 pb-5 mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Tambah Task Baru</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Catat detail tugas kuliah dan tenggat waktu pengumpulan.</p>
        </div>

        <form action="{{ route('tasks.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Title Field -->
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Judul Tugas <span class="text-rose-500">*</span>
                </label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    value="{{ old('title') }}"
                    placeholder="Contoh: Makalah Sistem Basis Data"
                    required
                    class="w-full px-4 py-2.5 bg-slate-50 border @error('title') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                >
                @error('title')
                    <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description Field -->
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Deskripsi Tugas <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                </label>
                <textarea
                    name="description"
                    id="description"
                    rows="4"
                    placeholder="Tambahkan catatan khusus, instruksi dosen, format pengumpulan (PDF/Word), dll."
                    class="w-full px-4 py-2.5 bg-slate-50 border @error('description') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all resize-y"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid for Deadline and Status -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Deadline Field -->
                <div>
                    <label for="deadline" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Deadline Pengumpulan <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="date"
                        name="deadline"
                        id="deadline"
                        value="{{ old('deadline') }}"
                        required
                        class="w-full px-4 py-2.5 bg-slate-50 border @error('deadline') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                    >
                    @error('deadline')
                        <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status Field -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Status Awal
                    </label>
                    <select
                        name="status"
                        id="status"
                        class="w-full px-4 py-2.5 bg-slate-50 border @error('status') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                    >
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending (Belum Selesai)</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed (Sudah Selesai)</option>
                    </select>
                    @error('status')
                        <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('tasks.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors">
                    Batal
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-150 transform active:scale-95 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Tugas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
