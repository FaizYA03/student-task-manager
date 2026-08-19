@extends('layouts.app')

@section('title', 'Daftar Tugas Kuliah - Student Task Manager')

@section('content')
<div class="space-y-6">

    <!-- Top Summary Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Tasks -->
        <a href="{{ route('tasks.index') }}" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-indigo-200 transition-all group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Tugas</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $totalCount }}</div>
            <span class="text-xs text-slate-400 mt-1 block">Semua tugas semester ini</span>
        </a>

        <!-- Pending Tasks -->
        <a href="{{ route('tasks.index', ['status' => 'pending']) }}" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-amber-200 transition-all group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Belum Selesai</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-amber-600">{{ $pendingCount }}</div>
            <span class="text-xs text-slate-400 mt-1 block">Tugas aktif dalam antrean</span>
        </a>

        <!-- Completed Tasks -->
        <a href="{{ route('tasks.index', ['status' => 'completed']) }}" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-emerald-200 transition-all group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Selesai</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600">{{ $completedCount }}</div>
            <span class="text-xs text-slate-400 mt-1 block">Tugas tuntas dikerjakan</span>
        </a>

        <!-- Overdue Tasks -->
        <a href="{{ route('tasks.index', ['urgency' => 'overdue']) }}" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-rose-200 transition-all group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-rose-600 uppercase tracking-wider">Terlewat Deadline</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-rose-600">{{ $overdueCount }}</div>
            <span class="text-xs text-slate-400 mt-1 block">Memerlukan atensi segera</span>
        </a>
    </div>

    <!-- Header Actions Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                <span>My Tasks</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                    {{ count($tasks) }} Ditampilkan
                </span>
            </h1>
            <p class="text-sm text-slate-500 mt-1">Pantau tenggat waktu, mata kuliah, dan prioritas tugas kuliah Anda.</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-colors">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                Kelola Matkul
            </a>
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-150 transform active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah Task
            </a>
        </div>
    </div>

    <!-- Interactive Search & Multi-Criteria Filter Bar -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4">
        <form method="GET" action="{{ route('tasks.index') }}" class="space-y-3">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <!-- Search Input (Span 5) -->
                <div class="md:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari judul, deskripsi, atau mata kuliah..."
                        class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                    >
                </div>

                <!-- Course Dropdown Filter (Span 3) -->
                <div class="md:col-span-3">
                    <select
                        name="course_id"
                        onchange="this.form.submit()"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                    >
                        <option value="">Semua Mata Kuliah</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }} ({{ $course->code ?? 'Matkul' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter (Span 2) -->
                <div class="md:col-span-2">
                    <select
                        name="status"
                        onchange="this.form.submit()"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                    >
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Belum)</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                    </select>
                </div>

                <!-- Action Button (Span 2) -->
                <div class="md:col-span-2 flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition-colors cursor-pointer text-center">
                        Cari / Filter
                    </button>
                    @if(request()->hasAny(['search', 'course_id', 'status', 'priority', 'urgency']))
                        <a href="{{ route('tasks.index') }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0" title="Reset Semua Filter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Filter Badges (Urgency & Priority) -->
            <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-100 text-xs">
                <span class="font-bold text-slate-400 uppercase tracking-wider mr-1">Urgensi:</span>
                <a href="{{ route('tasks.index', array_merge(request()->query(), ['urgency' => ''])) }}" class="px-2.5 py-1 rounded-lg border {{ !request('urgency') ? 'bg-slate-800 text-white border-slate-800 font-bold' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">Semua</a>
                <a href="{{ route('tasks.index', array_merge(request()->query(), ['urgency' => 'today'])) }}" class="px-2.5 py-1 rounded-lg border {{ request('urgency') === 'today' ? 'bg-orange-600 text-white border-orange-600 font-bold' : 'bg-orange-50 text-orange-700 border-orange-200 hover:bg-orange-100' }}">🔥 Hari Ini / H-1</a>
                <a href="{{ route('tasks.index', array_merge(request()->query(), ['urgency' => 'this_week'])) }}" class="px-2.5 py-1 rounded-lg border {{ request('urgency') === 'this_week' ? 'bg-amber-600 text-white border-amber-600 font-bold' : 'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100' }}">📅 Minggu Ini</a>
                <a href="{{ route('tasks.index', array_merge(request()->query(), ['urgency' => 'overdue'])) }}" class="px-2.5 py-1 rounded-lg border {{ request('urgency') === 'overdue' ? 'bg-rose-600 text-white border-rose-600 font-bold' : 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100' }}">⚠️ Terlewat Deadline</a>

                <span class="font-bold text-slate-400 uppercase tracking-wider ml-auto mr-1">Prioritas:</span>
                <a href="{{ route('tasks.index', array_merge(request()->query(), ['priority' => 'high'])) }}" class="px-2.5 py-1 rounded-lg border {{ request('priority') === 'high' ? 'bg-rose-600 text-white border-rose-600 font-bold' : 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100' }}">High 🔴</a>
                <a href="{{ route('tasks.index', array_merge(request()->query(), ['priority' => 'medium'])) }}" class="px-2.5 py-1 rounded-lg border {{ request('priority') === 'medium' ? 'bg-amber-600 text-white border-amber-600 font-bold' : 'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100' }}">Medium 🟡</a>
                <a href="{{ route('tasks.index', array_merge(request()->query(), ['priority' => 'low'])) }}" class="px-2.5 py-1 rounded-lg border {{ request('priority') === 'low' ? 'bg-slate-700 text-white border-slate-700 font-bold' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">Low ⚪</a>
            </div>
        </form>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
        @if($tasks->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-16 px-6">
                <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-indigo-100 shadow-xs">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900">Tidak Ada Tugas yang Sesuai</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                    @if(request()->hasAny(['search', 'course_id', 'status', 'priority', 'urgency']))
                        Tidak ditemukan tugas dengan filter yang dipilih. Silakan coba atur ulang filter pencarian Anda.
                    @else
                        Mulai catat tugas kuliahmu hari ini agar tidak terlewat deadline!
                    @endif
                </p>
                <div class="mt-6 flex items-center justify-center gap-3">
                    @if(request()->hasAny(['search', 'course_id', 'status', 'priority', 'urgency']))
                        <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition-colors">
                            Reset Semua Filter
                        </a>
                    @endif
                    <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Tambah Tugas Baru
                    </a>
                </div>
            </div>
        @else
            <!-- Table Component -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-600 text-xs uppercase tracking-wider font-semibold">
                            <th class="py-4 px-5 w-12 text-center">No</th>
                            <th class="py-4 px-5">Tugas & Mata Kuliah</th>
                            <th class="py-4 px-4 text-center">Prioritas</th>
                            <th class="py-4 px-5">Deadline & Sisa Waktu</th>
                            <th class="py-4 px-4 text-center">Status</th>
                            <th class="py-4 px-5 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($tasks as $index => $task)
                            <tr class="hover:bg-slate-50/70 transition-colors group">
                                <!-- Number -->
                                <td class="py-4 px-5 text-center text-slate-400 font-medium text-xs">
                                    {{ $index + 1 }}
                                </td>

                                <!-- Title & Course Badge -->
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-2 flex-wrap mb-1">
                                        @if($task->course)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-bold border {{ $task->course->color_badge_classes }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $task->course->color_dot_class }}"></span>
                                                {{ $task->course->name }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                Umum / Mandiri
                                            </span>
                                        @endif
                                    </div>

                                    <div class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                                        {{ $task->title }}
                                    </div>

                                    @if($task->description)
                                        <p class="text-xs text-slate-500 mt-1 line-clamp-1 max-w-lg">
                                            {{ $task->description }}
                                        </p>
                                    @endif

                                    @if($task->course && $task->course->lecturer)
                                        <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                                            <span>Dosen: {{ $task->course->lecturer }}</span>
                                        </div>
                                    @endif
                                </td>

                                <!-- Priority -->
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border capitalize {{ $task->priority_badge_classes }}">
                                        @if($task->priority === 'high') 🔴 High
                                        @elseif($task->priority === 'medium') 🟡 Med
                                        @else ⚪ Low
                                        @endif
                                    </span>
                                </td>

                                <!-- Deadline & Dynamic Urgency Badge -->
                                <td class="py-4 px-5 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 text-slate-800 font-semibold">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        <span>{{ \Carbon\Carbon::parse($task->deadline)->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <div class="mt-1">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] border {{ $task->urgency_badge_classes }}">
                                            {{ $task->urgency_label }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-4 whitespace-nowrap text-center">
                                    @if($task->status === 'completed')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-5 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Edit Action -->
                                        <a href="{{ route('tasks.edit', $task) }}" class="p-2 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors" title="Edit Tugas">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                        </a>

                                        <!-- Delete Action -->
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-600 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer" title="Hapus Tugas">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
