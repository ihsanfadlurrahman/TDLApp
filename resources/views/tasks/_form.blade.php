{{-- Title --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Task *</label>
    <input type="text" name="title" value="{{ old('title', $task->title ?? '') }}"
           class="w-full border rounded px-3 py-2 @error('title') border-red-500 @enderror">
    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

{{-- Description --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
    <textarea name="description" rows="3"
              class="w-full border rounded px-3 py-2">{{ old('description', $task->description ?? '') }}</textarea>
</div>

{{-- Category --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
    <select name="category_id" class="w-full border rounded px-3 py-2">
        <option value="">-- Tanpa Kategori --</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}"
                {{ old('category_id', $task->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
</div>

{{-- Priority & Due Date --}}
<div class="grid grid-cols-2 gap-3">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas *</label>
        <select name="priority" class="w-full border rounded px-3 py-2">
            @foreach(['low' => '🟢 Low', 'medium' => '🟡 Medium', 'high' => '🔴 High'] as $val => $label)
                <option value="{{ $val }}"
                    {{ old('priority', $task->priority ?? 'medium') == $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
        <input type="date" name="due_date"
               value="{{ old('due_date', isset($task) && $task->due_date ? $task->due_date->format('Y-m-d') : '') }}"
               class="w-full border rounded px-3 py-2">
    </div>
</div>
