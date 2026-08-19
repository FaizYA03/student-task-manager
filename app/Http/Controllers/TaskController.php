<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Menampilkan daftar tugas dengan fitur Multi-View (Table, Kanban, Calendar),
     * Pencarian, Filter Mata Kuliah, Status, dan Urgensi.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = $user->tasks()->with('course');

        // Filter Pencarian (Keyword di Title, Description, atau Course)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('course', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    });
            });
        }

        // Filter berdasarkan Status
        if ($request->filled('status') && in_array($request->status, ['pending', 'in_progress', 'completed'])) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan Mata Kuliah
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter berdasarkan Prioritas
        if ($request->filled('priority') && in_array($request->priority, ['low', 'medium', 'high'])) {
            $query->where('priority', $request->priority);
        }

        // Filter berdasarkan Urgensi Deadline
        if ($request->filled('urgency')) {
            $today = Carbon::today()->toDateString();
            $endOfWeek = Carbon::today()->addDays(7)->toDateString();

            if ($request->urgency === 'overdue') {
                $query->where('status', '!=', 'completed')
                    ->where('deadline', '<', $today);
            } elseif ($request->urgency === 'today') {
                $query->where('status', '!=', 'completed')
                    ->where('deadline', '=', $today);
            } elseif ($request->urgency === 'this_week') {
                $query->where('status', '!=', 'completed')
                    ->whereBetween('deadline', [$today, $endOfWeek]);
            }
        }

        // Sorting: default urutkan berdasarkan deadline terdekat, lalu prioritas
        $tasks = $query->orderBy('deadline', 'asc')
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->get();

        // Kelompokkan data untuk Kanban Board
        $pendingTasks = $tasks->where('status', 'pending');
        $inProgressTasks = $tasks->where('status', 'in_progress');
        $completedTasks = $tasks->where('status', 'completed');

        // Ambil daftar mata kuliah milik user untuk dropdown filter
        $courses = $user->courses()->orderBy('name')->get();

        // Hitung statistik ringkas untuk header
        $totalCount = $user->tasks()->count();
        $pendingCount = $user->tasks()->where('status', 'pending')->count();
        $inProgressCount = $user->tasks()->where('status', 'in_progress')->count();
        $completedCount = $user->tasks()->where('status', 'completed')->count();
        $overdueCount = $user->tasks()->where('status', '!=', 'completed')->where('deadline', '<', Carbon::today()->toDateString())->count();

        // Mode tampilan aktif (default 'table')
        $activeView = $request->query('view', 'table');
        if (!in_array($activeView, ['table', 'kanban', 'calendar'])) {
            $activeView = 'table';
        }

        return view('tasks.index', compact(
            'tasks',
            'pendingTasks',
            'inProgressTasks',
            'completedTasks',
            'courses',
            'totalCount',
            'pendingCount',
            'inProgressCount',
            'completedCount',
            'overdueCount',
            'activeView'
        ));
    }

    /**
     * Endpoint API JSON untuk data event FullCalendar.
     */
    public function calendarEvents(Request $request): JsonResponse
    {
        $user = $request->user();

        $tasks = $user->tasks()->with('course')->get();

        $events = $tasks->map(function (Task $task) {
            // Tentukan warna event berdasarkan warna mata kuliah atau status
            $hexColor = match ($task->course?->color) {
                'emerald' => '#059669',
                'amber' => '#d97706',
                'rose' => '#e11d48',
                'sky' => '#0284c7',
                'purple' => '#7c3aed',
                'teal' => '#0d9488',
                'fuchsia' => '#c026d3',
                'orange' => '#ea580c',
                default => '#4f46e5',
            };

            if ($task->status === 'completed') {
                $hexColor = '#10b981';
            }

            return [
                'id' => (string) $task->id,
                'title' => ($task->course ? "[{$task->course->code}] " : "") . $task->title,
                'start' => $task->deadline->format('Y-m-d'),
                'allDay' => true,
                'backgroundColor' => $hexColor,
                'borderColor' => $hexColor,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'title_raw' => $task->title,
                    'description' => $task->description ?? 'Tidak ada deskripsi',
                    'status' => $task->status,
                    'status_label' => $task->status_label,
                    'priority' => ucfirst($task->priority),
                    'deadline' => $task->deadline->format('d M Y'),
                    'urgency_label' => $task->urgency_label,
                    'course_name' => $task->course?->name ?? 'Tugas Mandiri',
                    'lecturer' => $task->course?->lecturer ?? '-',
                    'edit_url' => route('tasks.edit', $task),
                ],
            ];
        });

        return response()->json($events);
    }

    /**
     * Endpoint API AJAX untuk update status instan dari Kanban Board.
     */
    public function updateStatus(Request $request, Task $task): JsonResponse
    {
        abort_if($task->user_id !== auth()->id(), 403, 'Akses ditolak.');

        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed'],
        ]);

        $task->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Status tugas berhasil dipindahkan ke ' . $task->status_label . '.',
            'task' => [
                'id' => $task->id,
                'status' => $task->status,
                'status_label' => $task->status_label,
            ],
        ]);
    }

    /**
     * Menampilkan form untuk membuat tugas baru.
     */
    public function create(Request $request): View
    {
        $courses = $request->user()->courses()->orderBy('name')->get();

        return view('tasks.create', compact('courses'));
    }

    /**
     * Menyimpan data tugas baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'course_id' => [
                'nullable',
                Rule::exists('courses', 'id')->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }),
            ],
            'description' => ['nullable', 'string'],
            'deadline' => ['required', 'date'],
            'status' => ['nullable', 'in:pending,in_progress,completed'],
            'priority' => ['required', 'in:low,medium,high'],
        ], [
            'title.required' => 'Judul tugas wajib diisi.',
            'deadline.required' => 'Batas waktu (deadline) wajib diisi.',
            'course_id.exists' => 'Mata kuliah yang dipilih tidak valid.',
        ]);

        $user->tasks()->create($validated);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit untuk tugas tertentu.
     */
    public function edit(Request $request, Task $task): View
    {
        abort_if($task->user_id !== auth()->id(), 403, 'Akses ditolak. Anda bukan pemilik tugas ini.');

        $courses = $request->user()->courses()->orderBy('name')->get();

        return view('tasks.edit', compact('task', 'courses'));
    }

    /**
     * Memperbarui data tugas di database.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        abort_if($task->user_id !== auth()->id(), 403, 'Akses ditolak. Anda bukan pemilik tugas ini.');

        $user = $request->user();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'course_id' => [
                'nullable',
                Rule::exists('courses', 'id')->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }),
            ],
            'description' => ['nullable', 'string'],
            'deadline' => ['required', 'date'],
            'status' => ['required', 'in:pending,in_progress,completed'],
            'priority' => ['required', 'in:low,medium,high'],
        ], [
            'title.required' => 'Judul tugas wajib diisi.',
            'deadline.required' => 'Batas waktu (deadline) wajib diisi.',
            'course_id.exists' => 'Mata kuliah yang dipilih tidak valid.',
        ]);

        $task->update($validated);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    /**
     * Menghapus tugas dari database.
     */
    public function destroy(Task $task): RedirectResponse
    {
        abort_if($task->user_id !== auth()->id(), 403, 'Akses ditolak. Anda bukan pemilik tugas ini.');

        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }
}
