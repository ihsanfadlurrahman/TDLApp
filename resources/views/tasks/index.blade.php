@extends('layouts.app')
@section('title', 'Tasks')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white" style="font-family:'Syne',sans-serif;">Semua Task</h1>
        <p class="text-white/40 text-sm mt-1">{{ $tasks->total() }} task ditemukan</p>
    </div>
    <a href="{{ route('tasks.create') }}"
       class="btn-primary px-4 py-2 rounded-xl text-sm font-semibold text-white flex items-center gap-1.5">
        + Tambah Task
    </a>
</div>

{{-- Filter --}}
<div class="navy-card rounded-2xl p-4 mb-6">
    <form method="GET" action="{{ route('tasks.index') }}" class="flex flex-wrap gap-2 items-center">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari task..."
               class="flex-1 min-w-36 bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white placeholder-white/20 focus:outline-none focus:border-blue-500/50 transition">
        <select name="status"
                class="bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-sm text-white/60 focus:outline-none focus:border-blue-500/50">
            <option value="">Semua Status</option>
            <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Belum Selesai</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
        </select>
        <select name="priority"
                class="bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-sm text-white/60 focus:outline-none focus:border-blue-500/50">
            <option value="">Semua Prioritas</option>
            <option value="high"   {{ request('priority') == 'high'   ? 'selected' : '' }}>High</option>
            <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
            <option value="low"    {{ request('priority') == 'low'    ? 'selected' : '' }}>Low</option>
        </select>
        <select name="category_id"
                class="bg-white/5 border border-white/10 rounded-xl px-3 py-2 text-sm text-white/60 focus:outline-none focus:border-blue-500/50">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->icon }} {{ $cat->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn-primary px-4 py-2 rounded-xl text-sm font-semibold text-white">
            Filter
        </button>
    </form>
</div>

{{-- Task List --}}
<div class="space-y-2">
    @forelse($tasks as $task)
        @php $subCount = $task->subTasks->count(); @endphp
        <div class="navy-card rounded-2xl px-5 py-4 hover:border-blue-500/30 transition
                    {{ $task->is_completed ? 'opacity-40' : '' }}">
            <div class="flex items-start gap-3">

                <form method="POST" action="{{ route('tasks.toggle', $task) }}" class="mt-0.5">
                    @csrf @method('PATCH')
                    <button type="submit"
                            class="w-5 h-5 rounded-full border-2 flex-shrink-0 flex items-center justify-center transition
                                   {{ $task->is_completed ? 'bg-blue-600 border-blue-600' : 'border-white/20 hover:border-blue-400' }}">
                        @if($task->is_completed)
                            <svg class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        @endif
                    </button>
                </form>

                <div class="flex-1 min-w-0">
                    <a href="{{ route('tasks.show', $task) }}"
                       class="text-sm font-semibold text-white/90 hover:text-blue-300 transition
                              {{ $task->is_completed ? 'line-through text-white/30' : '' }}">
                        {{ $task->title }}
                    </a>
                    <div class="flex flex-wrap gap-1.5 mt-2">
                        @if($task->category)
                            <span class="pill-blue text-xs px-2.5 py-0.5 rounded-lg">
                                {{ $task->category->icon }} {{ $task->category->name }}
                            </span>
                        @endif
                        <span class="pill-blue text-xs px-2.5 py-0.5 rounded-lg">{{ ucfirst($task->priority) }}</span>
                        @if($task->due_date)
                            <span class="pill-blue text-xs px-2.5 py-0.5 rounded-lg">
                                📅 {{ $task->due_date->format('d M Y') }}
                            </span>
                        @endif
                        @if($subCount > 0)
                            <span class="pill-blue text-xs px-2.5 py-0.5 rounded-lg">
                                {{ $task->subTasks->where('is_completed', true)->count() }}/{{ $subCount }} sub-task
                            </span>
                        @endif
                    </div>
                    @if($subCount > 0)
                        <div class="mt-2.5">
                            <div class="w-full bg-white/5 rounded-full h-1">
                                <div class="h-1 rounded-full transition-all duration-700
                                            {{ $task->progress == 100 ? 'progress-green' : 'progress-blue' }}"
                                     style="width: {{ $task->progress }}%">
                                </div>
                            </div>
                            <p class="text-xs text-white/30 mt-1">{{ $task->progress }}% selesai</p>
                        </div>
                    @endif
                </div>

                <div class="flex gap-1.5 flex-shrink-0">
                    <a href="{{ route('tasks.edit', $task) }}"
                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 border border-white/10 text-white/40 hover:bg-blue-500/20 hover:text-blue-300 hover:border-blue-500/30 transition text-sm">
                        ✏️
                    </a>
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                          onsubmit="return confirm('Hapus task ini?')">
                        @csrf @method('DELETE')
                        <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 border border-white/10 text-white/40 hover:bg-red-500/20 hover:text-red-400 hover:border-red-500/30 transition text-sm">
                            🗑️
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-20 text-white/30">
            <div class="text-5xl mb-4">📭</div>
            <p class="text-sm">Belum ada task.</p>
            <a href="{{ route('tasks.create') }}" class="text-blue-400 text-sm hover:underline mt-2 inline-block">Tambah sekarang</a>
        </div>
    @endforelse
</div>

<div class="mt-6">{{ $tasks->links() }}</div>

@endsection
