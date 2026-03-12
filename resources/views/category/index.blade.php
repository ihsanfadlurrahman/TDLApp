@extends('layouts.app')
@section('title', 'Kategori')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">🏷️ Kategori</h1>
    <a href="{{ route('categories.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
        + Tambah Kategori
    </a>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 gap-4">
    @forelse($categories as $cat)
        <div class="bg-white rounded shadow p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-4 h-4 rounded-full" style="background-color: {{ $cat->color }}"></div>
                <div>
                    <p class="font-semibold text-gray-800">{{ $cat->name }}</p>
                    <p class="text-xs text-gray-400">{{ $cat->tasks_count }} task</p>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('categories.edit', $cat) }}"
                   class="text-sm text-indigo-600 hover:underline">Edit</a>
                <form method="POST" action="{{ route('categories.destroy', $cat) }}"
                      onsubmit="return confirm('Hapus kategori ini?')">
                    @csrf @method('DELETE')
                    <button class="text-sm text-red-500 hover:underline">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-gray-400 col-span-3 text-center py-8">Belum ada kategori.</p>
    @endforelse
</div>
@endsection
