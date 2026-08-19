<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'courses';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'code',
        'lecturer',
        'color',
    ];

    /**
     * Relasi ke User pemilik mata kuliah.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Tasks (satu mata kuliah memiliki banyak tugas).
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Helper styling Tailwind CSS berdasarkan warna mata kuliah.
     */
    public function getColorBadgeClassesAttribute(): string
    {
        return match ($this->color) {
            'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'amber' => 'bg-amber-50 text-amber-700 border-amber-200',
            'rose' => 'bg-rose-50 text-rose-700 border-rose-200',
            'sky' => 'bg-sky-50 text-sky-700 border-sky-200',
            'purple' => 'bg-purple-50 text-purple-700 border-purple-200',
            'teal' => 'bg-teal-50 text-teal-700 border-teal-200',
            'fuchsia' => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200',
            'orange' => 'bg-orange-50 text-orange-700 border-orange-200',
            default => 'bg-indigo-50 text-indigo-700 border-indigo-200',
        };
    }

    /**
     * Helper dot indicator Tailwind CSS.
     */
    public function getColorDotClassAttribute(): string
    {
        return match ($this->color) {
            'emerald' => 'bg-emerald-500',
            'amber' => 'bg-amber-500',
            'rose' => 'bg-rose-500',
            'sky' => 'bg-sky-500',
            'purple' => 'bg-purple-500',
            'teal' => 'bg-teal-500',
            'fuchsia' => 'bg-fuchsia-500',
            'orange' => 'bg-orange-500',
            default => 'bg-indigo-500',
        };
    }
}
