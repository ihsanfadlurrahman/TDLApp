@extends('layouts.app')
@section('title', $task->title)

@section('content')

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('tasks.index') }}" class="text-indigo-600 hover:underline text-sm">
            ← Kembali
        </a>
        <a href="{{ route('tasks.edit', $task) }}"
            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-md
              bg-indigo-50 text-indigo-600 border border-indigo-200 hover:bg-indigo-100">
            ✏️ Edit Task
        </a>
    </div>

    {{-- Task Detail --}}
    <div class="bg-white rounded shadow p-6 mb-6">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-start gap-3">

                {{-- Toggle --}}
                <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="mt-1 w-6 h-6 rounded-full border-2 flex-shrink-0
                               {{ $task->is_completed ? 'bg-indigo-500 border-indigo-500' : 'border-gray-400' }}">
                    </button>
                </form>

                <div>
                    <h1 class="text-xl font-bold text-gray-800 {{ $task->is_completed ? 'line-through opacity-60' : '' }}">
                        {{ $task->title }}
                    </h1>

                    @if ($task->description)
                        <p class="text-gray-500 text-sm mt-1">{{ $task->description }}</p>
                    @endif

                    <div class="flex gap-2 mt-2 flex-wrap text-xs">
                        @if ($task->category)
                            <span class="px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 font-medium">
                                {{ $task->category->icon }} {{ $task->category->name }}
                            </span>
                        @endif
                        <span
                            class="{{ $task->priority == 'high' ? 'text-red-500' : ($task->priority == 'medium' ? 'text-yellow-500' : 'text-green-500') }} font-medium">
                            {{ ucfirst($task->priority) }}
                        </span>
                        @if ($task->due_date)
                            <span class="text-gray-400">📅 {{ $task->due_date->format('d M Y') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Progress Bar --}}
        @if ($task->subTasks->count() > 0)
            <div class="mt-4 pt-4 border-t">
                <div class="flex justify-between text-sm text-gray-500 mb-1">
                    <span>Progress</span>
                    <span>{{ $task->subTasks->where('is_completed', true)->count() }}/{{ $task->subTasks->count() }}
                        selesai</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="h-2.5 rounded-full transition-all duration-500
                            {{ $task->progress == 100 ? 'bg-green-500' : 'bg-indigo-500' }}"
                        style="width: {{ $task->progress }}%">
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $task->progress }}% selesai</p>
            </div>
        @endif
    </div>

    {{-- Sub-tasks --}}
    <div class="bg-white rounded shadow p-6">
        <h2 class="font-bold text-gray-700 mb-4">📝 Sub-task</h2>

        {{-- List Sub-task --}}
        <div class="space-y-2 mb-4">
            @forelse($task->subTasks as $sub)
                <div class="flex items-center justify-between gap-3 py-2 border-b last:border-0">
                    <div class="flex items-center gap-3 flex-1">
                        {{-- Toggle Sub-task --}}
                        <form method="POST" action="{{ route('tasks.toggle', $sub) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="w-5 h-5 rounded-full border-2 flex-shrink-0
                       {{ $sub->is_completed ? 'bg-indigo-500 border-indigo-500' : 'border-gray-400' }}">
                            </button>
                        </form>

                        <div>
                            <span
                                class="text-sm text-gray-700 {{ $sub->is_completed ? 'line-through text-gray-400' : '' }}">
                                {{ $sub->title }}
                            </span>

                            {{-- Tanggal selesai --}}
                            @if ($sub->is_completed && $sub->completed_at)
                                <p class="text-xs text-green-500 mt-0.5">
                                    ✅ Selesai pada {{ $sub->completed_at->translatedFormat('l, d F Y H:i:s') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Hapus Sub-task --}}
                    <form method="POST" action="{{ route('tasks.subtasks.destroy', $sub) }}"
                        onsubmit="return confirm('Hapus sub-task ini?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-7 h-7 flex items-center justify-center rounded-lg
                                   bg-red-50 text-red-400 hover:bg-red-100 transition text-sm">
                            🗑️
                        </button>
                    </form>
                </div>
            @empty
                <p class="text-gray-400 text-sm">Belum ada sub-task.</p>
            @endforelse
        </div>

        {{-- Form Tambah Sub-task --}}
        @if (!$task->is_completed)
            <form method="POST" action="{{ route('tasks.subtasks.store', $task) }}" class="flex gap-2 mt-4">
                @csrf
                <input type="text" name="title" placeholder="Tambah sub-task baru..."
                    class="flex-1 border rounded px-3 py-2 text-sm @error('title') border-red-500 @enderror">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700">
                    + Tambah
                </button>
            </form>
            @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        @else
            <p class="text-gray-400 text-xs mt-2">Task sudah selesai, sub-task tidak bisa ditambah.</p>
        @endif
    </div>

@endsection
