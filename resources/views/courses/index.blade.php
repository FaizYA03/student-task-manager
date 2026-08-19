@extends('layouts.app')

@section('title', 'Mata Kuliah - Student Task Manager')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                <span>Mata Kuliah Saya</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                    {{ count($courses) }} Matkul
                </span>
            </h1>
            <p class="text-sm text-slate-500 mt-1">Kelola mata kuliah, dosen pengampu, dan warna identitas untuk mempermudah organisasi tugas.</p>
        </div>

        <button
            onclick="document.getElementById('add-course-modal').classList.remove('hidden')"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md transition-all duration-150 transform active:scale-95 cursor-pointer"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Mata Kuliah
        </button>
    </div>

    <!-- Grid Daftar Mata Kuliah -->
    @if($courses->isEmpty())
        <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-xs">
            <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-indigo-100 shadow-xs">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">Belum Ada Mata Kuliah</h3>
            <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">Tambahkan mata kuliah semester ini agar tugas kuliahmu dapat terorganisir dengan rapi dan memiliki kode warna khusus.</p>
            <button
                onclick="document.getElementById('add-course-modal').classList.remove('hidden')"
                class="mt-5 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors cursor-pointer"
            >
                Tambah Mata Kuliah Pertama
            </button>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($courses as $course)
                <div class="bg-white border border-slate-200 rounded-2xl shadow-xs p-5 hover:shadow-md transition-all duration-200 flex flex-col justify-between group">
                    <div>
                        <!-- Header Card: Badge & Actions -->
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $course->color_badge_classes }}">
                                <span class="w-2 h-2 rounded-full {{ $course->color_dot_class }}"></span>
                                {{ $course->code ?? 'Matkul' }}
                            </span>

                            <div class="flex items-center gap-1 opacity-80 group-hover:opacity-100 transition-opacity">
                                <!-- Edit Button -->
                                <button
                                    onclick="openEditModal({{ json_encode($course) }})"
                                    class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer"
                                    title="Edit Mata Kuliah"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <form action="{{ route('courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini? Tugas terkait tidak akan terhapus namun tidak lagi memiliki kategori mata kuliah.');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Mata Kuliah">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Course Title -->
                        <h3 class="text-lg font-bold text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors">
                            {{ $course->name }}
                        </h3>

                        <!-- Lecturer Info -->
                        @if($course->lecturer)
                            <div class="flex items-center gap-2 text-xs text-slate-500 mt-2">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="truncate">{{ $course->lecturer }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Footer: Task Count & Filter Link -->
                    <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">
                            {{ $course->tasks_count }} Tugas terdaftar
                        </span>
                        <a href="{{ route('tasks.index', ['course_id' => $course->id]) }}" class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline flex items-center gap-1">
                            Lihat Tugas &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- MODAL: TAMBAH MATA KULIAH -->
    <div id="add-course-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-100 overflow-hidden animate-scale-up">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Tambah Mata Kuliah Baru</h2>
                </div>
                <button onclick="document.getElementById('add-course-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form action="{{ route('courses.store') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Mata Kuliah <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Pemrograman Web Lanjut" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kode Matkul <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="code" placeholder="Contoh: IF-301" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Dosen Pengampu <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="lecturer" placeholder="Contoh: Dr. Hendra, M.Kom" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Warna Identitas Badge <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-5 sm:grid-cols-9 gap-2">
                        @foreach(['indigo' => 'bg-indigo-500', 'emerald' => 'bg-emerald-500', 'amber' => 'bg-amber-500', 'rose' => 'bg-rose-500', 'sky' => 'bg-sky-500', 'purple' => 'bg-purple-500', 'teal' => 'bg-teal-500', 'fuchsia' => 'bg-fuchsia-500', 'orange' => 'bg-orange-500'] as $colorKey => $colorBg)
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="{{ $colorKey }}" {{ $loop->first ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-8 h-8 rounded-xl {{ $colorBg }} flex items-center justify-center text-white peer-checked:ring-4 peer-checked:ring-indigo-200 peer-checked:scale-110 transition-all shadow-xs">
                                    <svg class="w-4 h-4 opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('add-course-modal').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800 cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl shadow-xs transition-colors cursor-pointer">Simpan Mata Kuliah</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: EDIT MATA KULIAH -->
    <div id="edit-course-modal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">Edit Mata Kuliah</h2>
                </div>
                <button onclick="document.getElementById('edit-course-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form id="edit-course-form" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Mata Kuliah <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="edit-name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kode Matkul</label>
                        <input type="text" name="code" id="edit-code" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Dosen Pengampu</label>
                        <input type="text" name="lecturer" id="edit-lecturer" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Warna Identitas Badge</label>
                    <div class="grid grid-cols-5 sm:grid-cols-9 gap-2" id="edit-color-container">
                        @foreach(['indigo' => 'bg-indigo-500', 'emerald' => 'bg-emerald-500', 'amber' => 'bg-amber-500', 'rose' => 'bg-rose-500', 'sky' => 'bg-sky-500', 'purple' => 'bg-purple-500', 'teal' => 'bg-teal-500', 'fuchsia' => 'bg-fuchsia-500', 'orange' => 'bg-orange-500'] as $colorKey => $colorBg)
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="{{ $colorKey }}" id="edit-color-{{ $colorKey }}" class="sr-only peer edit-color-radio">
                                <div class="w-8 h-8 rounded-xl {{ $colorBg }} flex items-center justify-center text-white peer-checked:ring-4 peer-checked:ring-indigo-200 peer-checked:scale-110 transition-all shadow-xs">
                                    <svg class="w-4 h-4 opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="document.getElementById('edit-course-modal').classList.add('hidden')" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800 cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl shadow-xs transition-colors cursor-pointer">Perbarui Mata Kuliah</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function openEditModal(course) {
        document.getElementById('edit-name').value = course.name;
        document.getElementById('edit-code').value = course.code || '';
        document.getElementById('edit-lecturer').value = course.lecturer || '';
        
        const colorRadio = document.getElementById('edit-color-' + (course.color || 'indigo'));
        if (colorRadio) {
            colorRadio.checked = true;
        }

        document.getElementById('edit-course-form').action = '/courses/' + course.id;
        document.getElementById('edit-course-modal').classList.remove('hidden');
    }
</script>
@endsection
