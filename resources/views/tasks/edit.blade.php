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
    <div class="bg-white border border-slate-200 rounded-3xl shadow-xs p-6 sm:p-8">
        <div class="border-b border-slate-100 pb-5 mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Edit Task</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Perbarui rincian tugas, mata kuliah, deadline, atau ubah status tugas.</p>
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
                <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
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

            <!-- Course Selection Field -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="course_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Mata Kuliah Terkait
                    </label>
                    <a href="{{ route('courses.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">
                        + Kelola Mata Kuliah
                    </a>
                </div>
                <select
                    name="course_id"
                    id="course_id"
                    class="w-full px-4 py-2.5 bg-slate-50 border @error('course_id') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                >
                    <option value="">-- Tugas Mandiri / Tanpa Mata Kuliah --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $task->course_id) == $course->id ? 'selected' : '' }}>
                            {{ $course->name }} ({{ $course->code ?? 'Matkul' }}) {{ $course->lecturer ? '• ' . $course->lecturer : '' }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description Field -->
            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Deskripsi Tugas <span class="text-xs font-normal text-slate-400 normal-case">(Opsional)</span>
                </label>
                <textarea
                    name="description"
                    id="description"
                    rows="3"
                    placeholder="Tambahkan catatan khusus, format pengumpulan, link referensi, dll."
                    class="w-full px-4 py-2.5 bg-slate-50 border @error('description') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all resize-y"
                >{{ old('description', $task->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Priority Selection -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Tingkat Prioritas <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="cursor-pointer">
                        <input type="radio" name="priority" value="low" {{ old('priority', $task->priority) === 'low' ? 'checked' : '' }} class="sr-only peer">
                        <div class="p-3 text-center rounded-xl border border-slate-200 bg-slate-50 peer-checked:bg-slate-100 peer-checked:border-slate-400 peer-checked:ring-2 peer-checked:ring-slate-300 transition-all">
                            <span class="text-xs font-bold text-slate-700 block">⚪ Low</span>
                            <span class="text-[10px] text-slate-400 block mt-0.5">Tugas santai</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="priority" value="medium" {{ old('priority', $task->priority) === 'medium' ? 'checked' : '' }} class="sr-only peer">
                        <div class="p-3 text-center rounded-xl border border-slate-200 bg-slate-50 peer-checked:bg-amber-50 peer-checked:border-amber-400 peer-checked:ring-2 peer-checked:ring-amber-200 transition-all">
                            <span class="text-xs font-bold text-amber-700 block">🟡 Medium</span>
                            <span class="text-[10px] text-amber-600/70 block mt-0.5">Standar kuliah</span>
                        </div>
                    </label>

                    <label class="cursor-pointer">
                        <input type="radio" name="priority" value="high" {{ old('priority', $task->priority) === 'high' ? 'checked' : '' }} class="sr-only peer">
                        <div class="p-3 text-center rounded-xl border border-slate-200 bg-slate-50 peer-checked:bg-rose-50 peer-checked:border-rose-400 peer-checked:ring-2 peer-checked:ring-rose-200 transition-all">
                            <span class="text-xs font-bold text-rose-700 block">🔴 High</span>
                            <span class="text-[10px] text-rose-600/70 block mt-0.5">Tugas besar/UAS</span>
                        </div>
                    </label>
                </div>
                @error('priority')
                    <p class="text-xs text-rose-600 mt-1.5 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <!-- Grid for Deadline and Status -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Deadline Field -->
                <div>
                    <label for="deadline" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
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
                    <label for="status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status Tugas <span class="text-rose-500">*</span>
                    </label>
                    <select
                        name="status"
                        id="status"
                        class="w-full px-4 py-2.5 bg-slate-50 border @error('status') border-rose-400 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                    >
                        <option value="pending" {{ old('status', $task->status) === 'pending' ? 'selected' : '' }}>To Do (Belum Dimulai)</option>
                        <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress (Sedang Dikerjakan)</option>
                        <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed (Sudah Selesai)</option>
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
