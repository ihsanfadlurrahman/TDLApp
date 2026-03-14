@extends('layouts.app')
@section('title', 'Semua Task')

@section('content')

{{-- Header --}}
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">📋 Semua Task</h1>
    <a href="{{ route('tasks.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
        + Tambah Task
    </a>
</div>

{{-- Filter & Search --}}
<form method="GET" action="{{ route('tasks.index') }}"
      class="bg-white p-4 rounded shadow mb-6 space-y-2 md:space-y-0 md:grid md:grid-cols-4 md:gap-3">

    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari task..."
           class="w-full border rounded px-3 py-2 text-sm">

    <select name="status" class="w-full border rounded px-3 py-2 text-sm">
        <option value="">Semua Status</option>
        <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Belum Selesai</option>
        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
    </select>

    <select name="priority" class="w-full border rounded px-3 py-2 text-sm">
        <option value="">Semua Prioritas</option>
        <option value="high"   {{ request('priority') == 'high'   ? 'selected' : '' }}>🔴 High</option>
        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
        <option value="low"    {{ request('priority') == 'low'    ? 'selected' : '' }}>🟢 Low</option>
    </select>

    <select name="category_id" class="w-full border rounded px-3 py-2 text-sm">
        <option value="">Semua Kategori</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->icon }} {{ $cat->name }}
            </option>
        @endforeach
    </select>

    <button type="submit"
            class="w-full bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700 md:col-span-4">
        Filter
    </button>

</form>

{{-- Task List --}}
<div class="space-y-3">
    @forelse($tasks as $task)
        @php $subCount = $task->subTasks->count(); @endphp

        <div class="bg-white rounded shadow px-5 py-4 {{ $task->is_completed ? 'opacity-60' : '' }}">
            <div class="flex items-start justify-between gap-4">

                <div class="flex items-start gap-3 flex-1">
                    {{-- Toggle Button --}}
                    <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="mt-1 w-5 h-5 rounded-full border-2 flex-shrink-0
                                       {{ $task->is_completed ? 'bg-indigo-500 border-indigo-500' : 'border-gray-400' }}">
                        </button>
                    </form>

                    {{-- Info --}}
                    <div class="flex-1">
                        <a href="{{ route('tasks.show', $task) }}"
                           class="font-semibold text-gray-800 hover:text-indigo-600
                                  {{ $task->is_completed ? 'line-through' : '' }}">
                            {{ $task->title }}
                        </a>

                        <div class="flex gap-2 mt-1 flex-wrap text-xs text-gray-500">
                            @if($task->category)
                                <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 font-medium">
                                    {{ $task->category->icon }} {{ $task->category->name }}
                                </span>
                            @endif

                            <span class="{{ $task->priority == 'high' ? 'text-red-500' : ($task->priority == 'medium' ? 'text-yellow-500' : 'text-green-500') }} font-medium">
                                {{ ucfirst($task->priority) }}
                            </span>

                            @if($task->due_date)
                                <span>📅 {{ $task->due_date->format('d M Y') }}</span>
                            @endif

                            @if($subCount > 0)
                                <span class="text-gray-400">
                                    ✅ {{ $task->subTasks->where('is_completed', true)->count() }}/{{ $subCount }} sub-task
                                </span>
                            @endif
                        </div>

                        {{-- Progress Bar (hanya kalau ada sub-task) --}}
                        @if($subCount > 0)
                            <div class="mt-2">
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="h-1.5 rounded-full transition-all duration-300
                                                {{ $task->progress == 100 ? 'bg-green-500' : 'bg-indigo-500' }}"
                                         style="width: {{ $task->progress }}%">
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $task->progress }}% selesai</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-1.5 flex-shrink-0">
                    <a href="{{ route('tasks.edit', $task) }}" title="Edit"
                       class="w-8 h-8 flex items-center justify-center rounded-lg
                              bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition text-base">
                        ✏️
                    </a>
                    <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                          onsubmit="return confirm('Hapus task ini beserta semua sub-task-nya?')">
                        @csrf @method('DELETE')
                        <button type="submit" title="Hapus"
                                class="w-8 h-8 flex items-center justify-center rounded-lg
                                       bg-red-50 text-red-500 hover:bg-red-100 transition text-base">
                            🗑️
                        </button>
                    </form>
                </div>

            </div>
        </div>
    @empty
        <div class="text-center text-gray-400 py-12">
            Belum ada task. <a href="{{ route('tasks.create') }}" class="text-indigo-500 hover:underline">Tambah sekarang</a>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="mt-6">
    {{ $tasks->links() }}
</div>

@endsection
