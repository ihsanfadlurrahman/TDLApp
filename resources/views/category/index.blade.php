@extends('layouts.app')
@section('title', 'Kategori')

@section('content')

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white" style="font-family:'Syne',sans-serif;">Kategori</h1>
        <p class="text-white/40 text-sm mt-1">{{ $categories->count() }} kategori</p>
    </div>
    <a href="{{ route('categories.create') }}"
       class="btn-primary px-4 py-2 rounded-xl text-sm font-semibold text-white">
        + Tambah Kategori
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    @forelse($categories as $cat)
        @php $pendingCount = $cat->tasks->where('is_completed', false)->count(); @endphp
        <div class="navy-card rounded-2xl p-5 flex items-center justify-between hover:border-blue-500/30 transition">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-blue-600/10 border border-blue-500/20 flex items-center justify-center text-2xl">
                    {{ $cat->icon }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-white/80">{{ $cat->name }}</p>
                    <p class="text-xs text-white/30 mt-0.5">{{ $cat->tasks_count }} task</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('categories.edit', $cat) }}"
                   class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 border border-white/10 text-white/40 hover:bg-blue-500/20 hover:text-blue-300 hover:border-blue-500/30 transition text-sm">
                    ✏️
                </a>
                <form method="POST" action="{{ route('categories.destroy', $cat) }}"
                      onsubmit="return confirm('Hapus kategori ini?')">
                    @csrf @method('DELETE')
                    <button title="{{ $pendingCount > 0 ? $pendingCount . ' task belum selesai' : 'Hapus kategori' }}"
                            class="w-8 h-8 flex items-center justify-center rounded-lg border transition text-sm
                                   {{ $pendingCount > 0
                                      ? 'bg-white/2 border-white/5 text-white/15 cursor-not-allowed'
                                      : 'bg-white/5 border-white/10 text-white/40 hover:bg-red-500/20 hover:text-red-400 hover:border-red-500/30' }}"
                            {{ $pendingCount > 0 ? 'disabled' : '' }}>
                        🗑️
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-2 text-center py-20 text-white/30">
            <div class="text-5xl mb-4">🏷️</div>
            <p class="text-sm">Belum ada kategori.</p>
            <a href="{{ route('categories.create') }}" class="text-blue-400 text-sm hover:underline mt-2 inline-block">Tambah sekarang</a>
        </div>
    @endforelse
</div>

@endsection
