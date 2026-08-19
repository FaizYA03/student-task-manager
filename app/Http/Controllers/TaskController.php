<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Menampilkan daftar tugas dengan fitur Pencarian, Filter Mata Kuliah, Status, dan Urgensi.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = $user->tasks()->with('course');

        // Filter Pencarian (Keyword di Title atau Description)
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
        if ($request->filled('status') && in_array($request->status, ['pending', 'completed'])) {
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
                $query->where('status', 'pending')
                    ->where('deadline', '<', $today);
            } elseif ($request->urgency === 'today') {
                $query->where('status', 'pending')
                    ->where('deadline', '=', $today);
            } elseif ($request->urgency === 'this_week') {
                $query->where('status', 'pending')
                    ->whereBetween('deadline', [$today, $endOfWeek]);
            }
        }

        // Sorting: default urutkan berdasarkan deadline terdekat, lalu prioritas
        $tasks = $query->orderBy('deadline', 'asc')
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->get();

        // Ambil daftar mata kuliah milik user untuk dropdown filter
        $courses = $user->courses()->orderBy('name')->get();

        // Hitung statistik ringkas untuk header
        $totalCount = $user->tasks()->count();
        $pendingCount = $user->tasks()->where('status', 'pending')->count();
        $completedCount = $user->tasks()->where('status', 'completed')->count();
        $overdueCount = $user->tasks()->where('status', 'pending')->where('deadline', '<', Carbon::today()->toDateString())->count();

        return view('tasks.index', compact('tasks', 'courses', 'totalCount', 'pendingCount', 'completedCount', 'overdueCount'));
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
            'status' => ['nullable', 'in:pending,completed'],
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
            'status' => ['required', 'in:pending,completed'],
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
