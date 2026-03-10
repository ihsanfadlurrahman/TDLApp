<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
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
