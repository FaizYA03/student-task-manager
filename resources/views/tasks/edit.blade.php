@extends('layouts.app')

@section('title', 'Edit Task - Student Task Manager')

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
        <div class="border-b border-slate-100 pb-5 mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Edit Task</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Perbarui rincian, tenggat waktu, atau ubah status tugas.</p>
            </div>
            <div>
                @if($task->status === 'completed')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Completed
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        Pending
                    </span>
                @endif
            </div>
        </div>

        <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Title Field -->
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Judul Tugas <span class="text-rose-500">*</span>
                </label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    value="{{ old('title', $task->title) }}"
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
                >{{ old('description', $task->description) }}</textarea>
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
                        value="{{ old('deadline', $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('Y-m-d') : '') }}"
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
                        Status Tugas <span class="text-rose-500">*</span>
                    </label>
                    <select
                        name="status"
                        id="status"
                        class="w-full px-4 py-2.5 bg-slate-50 border @error('status') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                    >
                        <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>Pending (Belum Selesai)</option>
                        <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed (Sudah Selesai)</option>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Perbarui Tugas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
