<div>
    <label class="block text-xs font-semibold text-white/30 mb-2 uppercase tracking-widest">Judul Task *</label>
    <input type="text" name="title" value="{{ old('title', $task->title ?? '') }}"
           placeholder="Contoh: Bikin fitur login"
           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-white/20 focus:outline-none focus:border-blue-500/50 transition @error('title') border-red-500/50 @enderror">
    @error('title') <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p> @enderror
</div>

<div>
    <label class="block text-xs font-semibold text-white/30 mb-2 uppercase tracking-widest">Deskripsi</label>
    <textarea name="description" rows="3"
              placeholder="Tambahkan deskripsi..."
              class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-white/20 focus:outline-none focus:border-blue-500/50 transition resize-none">{{ old('description', $task->description ?? '') }}</textarea>
</div>

<div>
    <label class="block text-xs font-semibold text-white/30 mb-2 uppercase tracking-widest">Kategori</label>
    <select name="category_id"
            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white/70 focus:outline-none focus:border-blue-500/50 transition">
        <option value="" class="bg-[#111d3d]">-- Tanpa Kategori --</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" class="bg-[#111d3d]"
                {{ old('category_id', $task->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->icon }} {{ $cat->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-xs font-semibold text-white/30 mb-2 uppercase tracking-widest">Prioritas *</label>
        <select name="priority"
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white/70 focus:outline-none focus:border-blue-500/50 transition">
            @foreach(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $val => $label)
                <option value="{{ $val }}" class="bg-[#111d3d]"
                    {{ old('priority', $task->priority ?? 'medium') == $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold text-white/30 mb-2 uppercase tracking-widest">Due Date</label>
        <input type="date" name="due_date"
               value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white/70 focus:outline-none focus:border-blue-500/50 transition">
    </div>
</div>
