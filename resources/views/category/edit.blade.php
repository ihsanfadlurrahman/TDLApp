@extends('layouts.app')
@section('title', 'Edit Kategori')

@section('content')
<div class="max-w-md mx-auto">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white" style="font-family:'Syne',sans-serif;">Edit Kategori</h1>
        <p class="text-white/40 text-sm mt-1">Perbarui detail kategori</p>
    </div>

    <div class="navy-card rounded-2xl p-6">
        <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-white/30 mb-2 uppercase tracking-widest">Nama Kategori *</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}"
                       placeholder="Contoh: Kerjaan"
                       class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-white/20 focus:outline-none focus:border-blue-500/50 transition @error('name') border-red-500/50 @enderror">
                @error('name') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-white/30 mb-3 uppercase tracking-widest">Icon Kategori *</label>
                <input type="hidden" name="icon" id="selectedIcon" value="{{ old('icon', $category->icon) }}">
                @php
                $icons = ['📁','📂','🏠','💼','🎯','📚','🛒','💡','🏋️','🎮','✈️','🍳','💰','🎵','❤️','🌱','📝','🔧','🎨','🏥','🚗','📷','🐾','⭐'];
                @endphp
                <div class="grid grid-cols-8 gap-2">
                    @foreach($icons as $icon)
                        <button type="button"
                                onclick="selectIcon(this)"
                                data-icon="{{ htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') }}"
                                class="icon-btn text-xl w-10 h-10 rounded-xl border border-white/10 bg-white/5
                                       hover:border-blue-500/50 hover:bg-blue-500/10 transition
                                       flex items-center justify-center
                                       {{ old('icon', $category->icon) === $icon ? 'border-blue-500 bg-blue-500/20' : '' }}">
                            {{ $icon }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('categories.index') }}"
                   class="flex-1 py-3 rounded-xl text-sm font-semibold text-center border border-white/10 text-white/50 hover:bg-white/5 transition">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 btn-primary py-3 rounded-xl text-sm font-semibold text-white">
                    Update Kategori
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function selectIcon(el) {
    const icon = el.getAttribute('data-icon');
    document.getElementById('selectedIcon').value = icon;
    document.querySelectorAll('.icon-btn').forEach(btn => {
        btn.classList.remove('border-blue-500', 'bg-blue-500/20');
        btn.classList.add('border-white/10', 'bg-white/5');
    });
    el.classList.add('border-blue-500', 'bg-blue-500/20');
    el.classList.remove('border-white/10', 'bg-white/5');
}
</script>

@endsection
