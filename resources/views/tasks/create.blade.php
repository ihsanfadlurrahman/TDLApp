@extends('layouts.app')
@section('title', 'Tambah Task')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded shadow p-6">
    <h1 class="text-xl font-bold mb-6 text-gray-800">➕ Tambah Task</h1>

    <form method="POST" action="{{ route('tasks.store') }}" class="space-y-4">
        @csrf
        @include('tasks._form')

        <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
            Simpan Task
        </button>
    </form>
</div>
@endsection
