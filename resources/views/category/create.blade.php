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
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Icon Kategori *</label>

                {{-- Hidden input yang nyimpen nilai terpilih --}}
                <input type="hidden" name="icon" id="selectedIcon" value="{{ old('icon', $category->icon ?? '📁') }}">

                {{-- Grid icon picker --}}
                <div class="grid grid-cols-8 gap-2">
                    @foreach (['📁', '📂', '🏠', '💼', '🎯', '📚', '🛒', '💡', '🏋️', '🎮', '✈️', '🍳', '💰', '🎵', '❤️', '🌱', '📝', '🔧', '🎨', '🏥', '🚗', '📷', '🐾', '⭐'] as $icon)
                        <button type="button" onclick="selectIcon('{{ $icon }}', this)"
                            class="icon-btn text-2xl w-10 h-10 rounded-lg border-2 border-transparent
                           hover:border-indigo-400 hover:bg-indigo-50 transition flex items-center justify-center
                           {{ old('icon', $category->icon ?? '📁') === $icon ? 'border-indigo-500 bg-indigo-50' : '' }}">
                            {{ $icon }}
                        </button>
                    @endforeach
                </div>
            </div>

            <script>
                function selectIcon(icon, el) {
                    document.getElementById('selectedIcon').value = icon;
                    document.querySelectorAll('.icon-btn').forEach(btn => {
                        btn.classList.remove('border-indigo-500', 'bg-indigo-50');
                        btn.classList.add('border-transparent');
                    });
                    el.classList.add('border-indigo-500', 'bg-indigo-50');
                    el.classList.remove('border-transparent');
                }
            </script>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                Simpan
            </button>
        </form>
    </div>
@endsection
