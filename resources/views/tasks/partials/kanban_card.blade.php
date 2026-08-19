<div
    data-task-id="{{ $task->id }}"
    class="kanban-card bg-white border border-slate-200/90 rounded-2xl p-4 shadow-xs hover:shadow-md transition-all duration-200 cursor-grab active:cursor-grabbing group relative select-none"
>
    <!-- Header: Course Badge & Priority -->
    <div class="flex items-center justify-between gap-2 mb-2">
        @if($task->course)
            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $task->course->color_badge_classes }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $task->course->color_dot_class }}"></span>
                <span class="truncate max-w-[120px]">{{ $task->course->name }}</span>
            </span>
        @else
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                Mandiri
            </span>
        @endif

        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold border capitalize {{ $task->priority_badge_classes }}">
            @if($task->priority === 'high') 🔴 High
            @elseif($task->priority === 'medium') 🟡 Med
            @else ⚪ Low
            @endif
        </span>
    </div>

    <!-- Title & Description -->
    <h4 class="text-sm font-bold text-slate-900 leading-snug group-hover:text-indigo-600 transition-colors">
        {{ $task->title }}
    </h4>

    @if($task->description)
        <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
            {{ $task->description }}
        </p>
    @endif

    <!-- Footer: Deadline & Action -->
    <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between gap-2">
        <div class="flex items-center gap-1.5">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] border {{ $task->urgency_badge_classes }}">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                <span>{{ \Carbon\Carbon::parse($task->deadline)->format('d M') }} &bull; {{ $task->urgency_label }}</span>
            </span>
        </div>

        <div class="flex items-center gap-1 opacity-60 group-hover:opacity-100 transition-opacity">
            <a href="{{ route('tasks.edit', $task) }}" class="p-1 text-slate-400 hover:text-indigo-600 rounded-md hover:bg-slate-50 transition-colors" title="Edit">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
            </a>
            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded-md hover:bg-rose-50 transition-colors cursor-pointer" title="Hapus">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </form>
        </div>
    </div>
</div>
