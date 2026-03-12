@extends('layouts.app')
@section('title', 'Tambah Kategori')

@section('content')
<div class="max-w-md mx-auto bg-white rounded shadow p-6">
    <h1 class="text-xl font-bold mb-6">➕ Tambah Kategori</h1>

    <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Warna</label>
            <input type="color" name="color" value="{{ old('color', '#6366f1') }}"
                   class="w-full h-10 border rounded px-1 py-1 cursor-pointer">
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
            Simpan
        </button>
    </form>
</div>
@endsection
