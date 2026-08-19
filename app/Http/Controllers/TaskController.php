<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Menampilkan daftar tugas milik user yang sedang login.
     */
    public function index(Request $request): View
    {
        $tasks = $request->user()
            ->tasks()
            ->latest()
            ->get();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Menampilkan form untuk membuat tugas baru.
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * Menyimpan data tugas baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline' => ['required', 'date'],
            'status' => ['nullable', 'in:pending,completed'],
        ]);

        $request->user()->tasks()->create($validated);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit untuk tugas tertentu.
     */
    public function edit(Task $task): View
    {
        abort_if($task->user_id !== auth()->id(), 403, 'Akses ditolak. Anda bukan pemilik tugas ini.');

        return view('tasks.edit', compact('task'));
    }

    /**
     * Memperbarui data tugas di database.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        abort_if($task->user_id !== auth()->id(), 403, 'Akses ditolak. Anda bukan pemilik tugas ini.');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline' => ['required', 'date'],
            'status' => ['required', 'in:pending,completed'],
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
