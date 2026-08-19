@extends('layouts.app')

@section('title', 'Daftar Tugas Kuliah - Student Task Manager')

@section('content')
<!-- Include SortableJS & FullCalendar CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

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

        <!-- Pending / In Progress Tasks -->
        <a href="{{ route('tasks.index', ['status' => 'pending']) }}" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-amber-200 transition-all group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">To Do / In Progress</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-amber-600">{{ $pendingCount + $inProgressCount }}</div>
            <span class="text-xs text-slate-400 mt-1 block">{{ $pendingCount }} To Do &bull; {{ $inProgressCount }} In Progress</span>
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

    <!-- Header Actions Bar & Multi-View Switcher -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                <span>My Tasks</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                    {{ count($tasks) }} Ditampilkan
                </span>
            </h1>
            <p class="text-sm text-slate-500 mt-1">Kelola dan pantau tenggat waktu tugas dalam mode Tabel, Kanban Board, atau Kalender.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- View Mode Switcher -->
            <div class="inline-flex p-1 bg-slate-100 border border-slate-200 rounded-xl">
                <button
                    type="button"
                    onclick="switchView('table')"
                    id="btn-view-table"
                    class="view-switch-btn inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg transition-all cursor-pointer {{ $activeView === 'table' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                    <span>Tabel</span>
                </button>

                <button
                    type="button"
                    onclick="switchView('kanban')"
                    id="btn-view-kanban"
                    class="view-switch-btn inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg transition-all cursor-pointer {{ $activeView === 'kanban' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" /></svg>
                    <span>Kanban</span>
                </button>

                <button
                    type="button"
                    onclick="switchView('calendar')"
                    id="btn-view-calendar"
                    class="view-switch-btn inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg transition-all cursor-pointer {{ $activeView === 'calendar' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    <span>Kalender</span>
                </button>
            </div>

            <!-- Action Buttons -->
            <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                Matkul
            </a>

            <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-bold rounded-xl shadow-sm hover:shadow-md transition-all duration-150 transform active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Tambah Task
            </a>
        </div>
    </div>

    <!-- Search & Multi-Criteria Filter Bar (Bisa dipakai di Table & Kanban) -->
    <div id="filter-bar-container" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs space-y-4">
        <form method="GET" action="{{ route('tasks.index') }}" id="filter-form" class="space-y-3">
            <input type="hidden" name="view" id="filter-view-input" value="{{ $activeView }}">

            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <!-- Search Input -->
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

                <!-- Course Dropdown Filter -->
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

                <!-- Status Filter -->
                <div class="md:col-span-2">
                    <select
                        name="status"
                        onchange="this.form.submit()"
                        class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                    >
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>To Do (Belum)</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress (Dikerjakan)</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                    </select>
                </div>

                <!-- Action Button -->
                <div class="md:col-span-2 flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl transition-colors cursor-pointer text-center">
                        Cari / Filter
                    </button>
                    @if(request()->hasAny(['search', 'course_id', 'status', 'priority', 'urgency']))
                        <a href="{{ route('tasks.index', ['view' => $activeView]) }}" class="p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors shrink-0" title="Reset Semua Filter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Filter Badges -->
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

    <!-- ========================================== -->
    <!-- VIEW 1: TABLE VIEW                         -->
    <!-- ========================================== -->
    <div id="view-container-table" class="{{ $activeView === 'table' ? '' : 'hidden' }}">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden">
            @if($tasks->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-16 px-6">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-indigo-100 shadow-xs">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Tidak Ada Tugas</h3>
                    <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">
                        @if(request()->hasAny(['search', 'course_id', 'status', 'priority', 'urgency']))
                            Tidak ditemukan tugas dengan filter yang dipilih.
                        @else
                            Mulai catat tugas kuliahmu hari ini!
                        @endif
                    </p>
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
                                    <td class="py-4 px-5 text-center text-slate-400 font-medium text-xs">{{ $index + 1 }}</td>
                                    <td class="py-4 px-5">
                                        <div class="flex items-center gap-2 flex-wrap mb-1">
                                            @if($task->course)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-bold border {{ $task->course->color_badge_classes }}">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $task->course->color_dot_class }}"></span>
                                                    {{ $task->course->name }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                    Mandiri
                                                </span>
                                            @endif
                                        </div>
                                        <div class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                                            {{ $task->title }}
                                        </div>
                                        @if($task->description)
                                            <p class="text-xs text-slate-500 mt-1 line-clamp-1 max-w-lg">{{ $task->description }}</p>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border capitalize {{ $task->priority_badge_classes }}">
                                            @if($task->priority === 'high') 🔴 High
                                            @elseif($task->priority === 'medium') 🟡 Med
                                            @else ⚪ Low
                                            @endif
                                        </span>
                                    </td>
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
                                    <td class="py-4 px-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $task->status_badge_classes }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $task->status_dot_class }}"></span>
                                            {{ $task->status_label }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-5 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a href="{{ route('tasks.edit', $task) }}" class="p-2 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-colors" title="Edit Tugas">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                            </a>
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

    <!-- ========================================== -->
    <!-- VIEW 2: KANBAN BOARD (DRAG & DROP)        -->
    <!-- ========================================== -->
    <div id="view-container-kanban" class="{{ $activeView === 'kanban' ? '' : 'hidden' }}">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- COLUMN 1: PENDING (TO DO) -->
            <div class="bg-slate-100/70 border border-slate-200/80 rounded-3xl p-4 flex flex-col min-h-[500px]">
                <div class="flex items-center justify-between px-2 py-1 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        <h3 class="font-bold text-slate-900 text-sm">To Do / Belum Dimulai</h3>
                    </div>
                    <span id="badge-count-pending" class="px-2 py-0.5 bg-white border border-slate-200 rounded-full text-xs font-bold text-slate-700 shadow-2xs">
                        {{ count($pendingTasks) }}
                    </span>
                </div>

                <div id="kanban-pending" data-status="pending" class="kanban-dropzone space-y-3 flex-1 overflow-y-auto max-h-[70vh] p-1">
                    @foreach($pendingTasks as $task)
                        @include('tasks.partials.kanban_card', ['task' => $task])
                    @endforeach
                </div>
            </div>

            <!-- COLUMN 2: IN PROGRESS -->
            <div class="bg-sky-50/50 border border-sky-200/70 rounded-3xl p-4 flex flex-col min-h-[500px]">
                <div class="flex items-center justify-between px-2 py-1 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-sky-500 animate-pulse"></span>
                        <h3 class="font-bold text-sky-950 text-sm">Sedang Dikerjakan</h3>
                    </div>
                    <span id="badge-count-in_progress" class="px-2 py-0.5 bg-white border border-sky-200 rounded-full text-xs font-bold text-sky-800 shadow-2xs">
                        {{ count($inProgressTasks) }}
                    </span>
                </div>

                <div id="kanban-in_progress" data-status="in_progress" class="kanban-dropzone space-y-3 flex-1 overflow-y-auto max-h-[70vh] p-1">
                    @foreach($inProgressTasks as $task)
                        @include('tasks.partials.kanban_card', ['task' => $task])
                    @endforeach
                </div>
            </div>

            <!-- COLUMN 3: COMPLETED -->
            <div class="bg-emerald-50/50 border border-emerald-200/70 rounded-3xl p-4 flex flex-col min-h-[500px]">
                <div class="flex items-center justify-between px-2 py-1 mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <h3 class="font-bold text-emerald-950 text-sm">Selesai (Completed)</h3>
                    </div>
                    <span id="badge-count-completed" class="px-2 py-0.5 bg-white border border-emerald-200 rounded-full text-xs font-bold text-emerald-800 shadow-2xs">
                        {{ count($completedTasks) }}
                    </span>
                </div>

                <div id="kanban-completed" data-status="completed" class="kanban-dropzone space-y-3 flex-1 overflow-y-auto max-h-[70vh] p-1">
                    @foreach($completedTasks as $task)
                        @include('tasks.partials.kanban_card', ['task' => $task])
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <!-- ========================================== -->
    <!-- VIEW 3: INTERACTIVE CALENDAR VIEW          -->
    <!-- ========================================== -->
    <div id="view-container-calendar" class="{{ $activeView === 'calendar' ? '' : 'hidden' }}">
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xs">
            <div class="mb-4 flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Kalender Deadline Tugas</h2>
                        <p class="text-xs text-slate-500">Klik event pada tanggal untuk melihat detail lengkap tugas atau mengeditnya.</p>
                    </div>
                </div>

                <!-- Legend Warna -->
                <div class="flex items-center gap-3 text-xs flex-wrap">
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span><span class="text-slate-600">Selesai</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span><span class="text-slate-600">Sesuai Warna Matkul</span></div>
                </div>
            </div>

            <!-- FullCalendar Container Element -->
            <div id="calendar-el" class="min-h-[600px] text-slate-800"></div>
        </div>
    </div>

</div>

<!-- MODAL: DETAIL TUGAS DARI KALENDER -->
<div id="task-detail-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-100 overflow-hidden animate-scale-up">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span id="modal-priority-badge" class="px-2.5 py-0.5 rounded-full text-xs font-bold border"></span>
                <span id="modal-status-badge" class="px-2.5 py-0.5 rounded-full text-xs font-bold border"></span>
            </div>
            <button onclick="document.getElementById('task-detail-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="p-6 space-y-4">
            <div>
                <span id="modal-course-badge" class="text-xs font-bold text-indigo-600 block mb-1"></span>
                <h3 id="modal-title" class="text-xl font-bold text-slate-900 leading-tight"></h3>
            </div>

            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-2 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-500">Deadline:</span>
                    <span id="modal-deadline" class="font-bold text-slate-800"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Urgensi:</span>
                    <span id="modal-urgency" class="font-bold text-slate-800"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Dosen Pengampu:</span>
                    <span id="modal-lecturer" class="font-medium text-slate-700"></span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Catatan / Deskripsi:</label>
                <p id="modal-description" class="text-sm text-slate-700 whitespace-pre-line bg-slate-50/50 p-3 rounded-xl border border-slate-100"></p>
            </div>
        </div>

        <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
            <button onclick="document.getElementById('task-detail-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 cursor-pointer">Tutup</button>
            <a id="modal-edit-link" href="#" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs transition-colors">Edit Tugas Ini &rarr;</a>
        </div>
    </div>
</div>

<!-- TOAST NOTIFICATION -->
<div id="toast" class="fixed bottom-6 right-6 z-50 bg-slate-900 text-white px-4 py-3 rounded-2xl shadow-xl border border-slate-800 flex items-center gap-3 transform translate-y-20 opacity-0 transition-all duration-300">
    <div id="toast-icon" class="w-5 h-5 text-emerald-400">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
    </div>
    <span id="toast-message" class="text-xs font-semibold"></span>
</div>

<script>
    const csrfToken = "{{ csrf_token() }}";
    let calendar = null;

    // Switch Tampilan (Tabel / Kanban / Kalender)
    function switchView(viewName) {
        ['table', 'kanban', 'calendar'].forEach(v => {
            const container = document.getElementById('view-container-' + v);
            const btn = document.getElementById('btn-view-' + v);
            if (v === viewName) {
                container.classList.remove('hidden');
                btn.className = 'view-switch-btn inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg transition-all cursor-pointer bg-white text-slate-900 shadow-xs';
            } else {
                container.classList.add('hidden');
                btn.className = 'view-switch-btn inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg transition-all cursor-pointer text-slate-600 hover:text-slate-900';
            }
        });

        document.getElementById('filter-view-input').value = viewName;

        // Update URL tanpa reload
        const url = new URL(window.location);
        url.searchParams.set('view', viewName);
        window.history.replaceState({}, '', url);

        // Render kalender jika aktif
        if (viewName === 'calendar') {
            initCalendar();
        }
    }

    // Inisialisasi Kanban Drag and Drop (SortableJS)
    document.addEventListener('DOMContentLoaded', function() {
        const dropzones = document.querySelectorAll('.kanban-dropzone');
        dropzones.forEach(zone => {
            new Sortable(zone, {
                group: 'kanban-tasks',
                animation: 200,
                ghostClass: 'opacity-40',
                chosenClass: 'scale-105',
                dragClass: 'shadow-2xl',
                onEnd: function(evt) {
                    const taskId = evt.item.dataset.taskId;
                    const newStatus = evt.to.dataset.status;
                    const oldStatus = evt.from.dataset.status;

                    if (newStatus !== oldStatus) {
                        // Update status via AJAX fetch
                        fetch('/tasks/' + taskId + '/status', {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ status: newStatus })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                showToast(data.message, 'success');
                                updateBadgeCounts();
                            }
                        })
                        .catch(err => {
                            showToast('Gagal memindahkan status tugas.', 'error');
                        });
                    }
                }
            });
        });

        // Jika active view adalah kalender dari URL, inisialisasi langsung
        if ("{{ $activeView }}" === 'calendar') {
            initCalendar();
        }
    });

    // Update Counter Badges di Kolom Kanban
    function updateBadgeCounts() {
        ['pending', 'in_progress', 'completed'].forEach(st => {
            const count = document.querySelectorAll('#kanban-' + st + ' .kanban-card').length;
            const badge = document.getElementById('badge-count-' + st);
            if (badge) badge.innerText = count;
        });
    }

    // Inisialisasi FullCalendar
    function initCalendar() {
        const calEl = document.getElementById('calendar-el');
        if (!calEl || calendar) {
            if (calendar) calendar.render();
            return;
        }

        calendar = new FullCalendar.Calendar(calEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listMonth'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                list: 'Daftar'
            },
            events: '{{ route("tasks.calendar-events") }}',
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                const props = info.event.extendedProps;
                
                document.getElementById('modal-title').innerText = props.title_raw;
                document.getElementById('modal-course-badge').innerText = props.course_name;
                document.getElementById('modal-deadline').innerText = props.deadline;
                document.getElementById('modal-urgency').innerText = props.urgency_label;
                document.getElementById('modal-lecturer').innerText = props.lecturer;
                document.getElementById('modal-description').innerText = props.description;
                document.getElementById('modal-status-badge').innerText = props.status_label;
                document.getElementById('modal-status-badge').className = 'px-2.5 py-0.5 rounded-full text-xs font-bold border ' + (props.status === 'completed' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : (props.status === 'in_progress' ? 'bg-sky-50 text-sky-700 border-sky-200' : 'bg-amber-50 text-amber-700 border-amber-200'));
                
                document.getElementById('modal-priority-badge').innerText = 'Prioritas ' + props.priority;
                document.getElementById('modal-priority-badge').className = 'px-2.5 py-0.5 rounded-full text-xs font-bold border ' + (props.priority === 'High' ? 'bg-rose-50 text-rose-700 border-rose-200' : (props.priority === 'Medium' ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-slate-50 text-slate-700 border-slate-200'));
                
                document.getElementById('modal-edit-link').href = props.edit_url;
                document.getElementById('task-detail-modal').classList.remove('hidden');
            }
        });

        calendar.render();
    }

    // Toast Notifikasi
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const msg = document.getElementById('toast-message');
        const icon = document.getElementById('toast-icon');

        msg.innerText = message;
        if (type === 'success') {
            icon.className = 'w-5 h-5 text-emerald-400';
            icon.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
        } else {
            icon.className = 'w-5 h-5 text-rose-400';
            icon.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
        }

        toast.classList.remove('translate-y-20', 'opacity-0');
        setTimeout(() => {
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3000);
    }
</script>

<style>
    /* FullCalendar Custom Theme Styles */
    .fc {
        font-family: inherit;
    }
    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
    }
    .fc .fc-button {
        background-color: #f8fafc;
        border-color: #e2e8f0;
        color: #334155;
        font-weight: 700;
        font-size: 0.75rem;
        border-radius: 0.75rem;
        padding: 0.4rem 0.8rem;
        box-shadow: none !important;
        text-transform: capitalize;
    }
    .fc .fc-button-primary:not(:disabled):active, .fc .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #4f46e5 !important;
        border-color: #4f46e5 !important;
        color: #ffffff !important;
    }
    .fc .fc-button:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
        color: #0f172a;
    }
    .fc-theme-standard td, .fc-theme-standard th {
        border-color: #f1f5f9;
    }
    .fc-event {
        border-radius: 0.5rem;
        padding: 2px 6px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.15s ease;
    }
    .fc-event:hover {
        transform: scale(1.02);
    }
</style>
@endsection
