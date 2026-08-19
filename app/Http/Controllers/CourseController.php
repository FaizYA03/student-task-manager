<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Menampilkan daftar mata kuliah milik mahasiswa.
     */
    public function index(Request $request): View
    {
        $courses = $request->user()
            ->courses()
            ->withCount('tasks')
            ->orderBy('name')
            ->get();

        return view('courses.index', compact('courses'));
    }

    /**
     * Menyimpan mata kuliah baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'lecturer' => ['nullable', 'string', 'max:255'],
            'color' => ['required', 'in:indigo,emerald,amber,rose,sky,purple,teal,fuchsia,orange'],
        ], [
            'name.required' => 'Nama mata kuliah wajib diisi.',
            'color.required' => 'Warna identitas wajib dipilih.',
        ]);

        $request->user()->courses()->create($validated);

        return redirect()
            ->route('courses.index')
            ->with('success', 'Mata kuliah baru berhasil ditambahkan.');
    }

    /**
     * Memperbarui data mata kuliah.
     */
    public function update(Request $request, Course $course): RedirectResponse
    {
        abort_if($course->user_id !== auth()->id(), 403, 'Akses ditolak.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50'],
            'lecturer' => ['nullable', 'string', 'max:255'],
            'color' => ['required', 'in:indigo,emerald,amber,rose,sky,purple,teal,fuchsia,orange'],
        ], [
            'name.required' => 'Nama mata kuliah wajib diisi.',
            'color.required' => 'Warna identitas wajib dipilih.',
        ]);

        $course->update($validated);

        return redirect()
            ->route('courses.index')
            ->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    /**
     * Menghapus mata kuliah.
     */
    public function destroy(Course $course): RedirectResponse
    {
        abort_if($course->user_id !== auth()->id(), 403, 'Akses ditolak.');

        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
