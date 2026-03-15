@extends('layouts.app')
@section('title', 'Edit Task')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white" style="font-family:'Syne',sans-serif;">Edit Task</h1>
        <p class="text-white/40 text-sm mt-1">Perbarui detail task</p>
    </div>
    <div class="navy-card rounded-2xl p-6">
        <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-5">
            @csrf @method('PUT')
            @include('tasks._form')

            @if($task->subTasks->count() === 0)
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_completed" value="1"
                           class="w-4 h-4 accent-blue-600"
                           {{ $task->is_completed ? 'checked' : '' }}>
                    <span class="text-sm text-white/50">Tandai sebagai selesai</span>
                </label>
            @else
                <p class="text-xs text-white/30 bg-white/5 rounded-xl px-4 py-3">
                    💡 Status selesai otomatis dihitung dari sub-task.
                </p>
            @endif

            <button type="submit" class="btn-primary w-full py-3 rounded-xl text-sm font-semibold text-white">
                Update Task
            </button>
        </form>
    </div>
</div>
@endsection
