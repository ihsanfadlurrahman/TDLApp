<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'parent_id',
        'category_id',
        'title',
        'description',
        'is_completed',
        'due_date',
        'priority'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'due_date'     => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke parent task
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    // Relasi ke sub-tasks
    public function subTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    // Cek apakah task ini adalah sub-task
    public function isSubTask(): bool
    {
        return !is_null($this->parent_id);
    }

    // Hitung progress (0-100)
    public function getProgressAttribute(): int
    {
        $total = $this->subTasks()->count();
        if ($total === 0) return 0;

        $completed = $this->subTasks()->completed()->count();
        return (int) round(($completed / $total) * 100);
    }

    // Auto-complete parent kalau semua sub-task selesai
    public function checkAndCompleteParent(): void
    {
        if (!$this->parent_id) return;

        $parent = $this->parent;
        $allDone = $parent->subTasks()->pending()->count() === 0;

        $parent->update(['is_completed' => $allDone]);
    }

    // Scope: hanya parent task
    public function scopeParentOnly($query)
    {
        return $query->whereNull('parent_id');
    }

    // Scope: filter by priority
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Scope: belum selesai
    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    // Scope: sudah selesai
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }
}
