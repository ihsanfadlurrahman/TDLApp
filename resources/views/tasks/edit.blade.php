@extends('layouts.app')
@section('title', 'Edit Task')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded shadow p-6">
    <h1 class="text-xl font-bold mb-6 text-gray-800">✏️ Edit Task</h1>

    <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-4">
        @csrf @method('PUT')
        @include('tasks._form')

        <label class="flex items-center gap-2 text-sm text-gray-700">
            <input type="checkbox" name="is_completed" value="1"
                   {{ $task->is_completed ? 'checked' : '' }}>
            Tandai sebagai selesai
        </label>

        <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
            Update Task
        </button>
    </form>
</div>
@endsection
