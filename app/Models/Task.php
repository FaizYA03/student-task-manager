<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tasks';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'title',
        'description',
        'deadline',
        'status',
        'priority',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    /**
     * Relasi ke User (setiap task dimiliki oleh satu user).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Mata Kuliah (Course).
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Status Urgensi Deadline Dinamis.
     */
    public function getUrgencyStatusAttribute(): string
    {
        if ($this->status === 'completed') {
            return 'completed';
        }

        $today = Carbon::today();
        $deadline = Carbon::parse($this->deadline)->startOfDay();
        $diffInDays = $today->diffInDays($deadline, false);

        if ($diffInDays < 0) {
            return 'overdue';
        } elseif ($diffInDays === 0) {
            return 'today';
        } elseif ($diffInDays === 1) {
            return 'tomorrow';
        } elseif ($diffInDays <= 7) {
            return 'this_week';
        }

        return 'upcoming';
    }

    /**
     * Label teks deskriptif untuk sisa waktu deadline.
     */
    public function getUrgencyLabelAttribute(): string
    {
        if ($this->status === 'completed') {
            return 'Selesai';
        }

        $today = Carbon::today();
        $deadline = Carbon::parse($this->deadline)->startOfDay();
        $diffInDays = (int) $today->diffInDays($deadline, false);

        if ($diffInDays < 0) {
            return 'Terlewat ' . abs($diffInDays) . ' hari';
        } elseif ($diffInDays === 0) {
            return 'Hari ini!';
        } elseif ($diffInDays === 1) {
            return 'Besok (H-1)';
        }

        return 'Sisa ' . $diffInDays . ' hari';
    }

    /**
     * Badge styling Tailwind CSS berdasarkan urgensi.
     */
    public function getUrgencyBadgeClassesAttribute(): string
    {
        if ($this->status === 'completed') {
            return 'bg-emerald-50 text-emerald-700 border-emerald-200';
        }

        return match ($this->urgency_status) {
            'overdue' => 'bg-rose-100 text-rose-800 border-rose-300 font-bold',
            'today', 'tomorrow' => 'bg-orange-100 text-orange-800 border-orange-300 font-semibold animate-pulse',
            'this_week' => 'bg-amber-50 text-amber-800 border-amber-200 font-medium',
            default => 'bg-slate-50 text-slate-600 border-slate-200',
        };
    }

    /**
     * Badge styling untuk tingkat prioritas.
     */
    public function getPriorityBadgeClassesAttribute(): string
    {
        return match ($this->priority) {
            'high' => 'bg-rose-50 text-rose-700 border-rose-200',
            'low' => 'bg-slate-50 text-slate-600 border-slate-200',
            default => 'bg-amber-50 text-amber-700 border-amber-200',
        };
    }

    /**
     * Label teks deskriptif status tugas.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'in_progress' => 'Sedang Dikerjakan',
            'completed' => 'Selesai',
            default => 'Belum Dimulai',
        };
    }

    /**
     * Badge styling Tailwind CSS untuk status tugas.
     */
    public function getStatusBadgeClassesAttribute(): string
    {
        return match ($this->status) {
            'in_progress' => 'bg-sky-50 text-sky-700 border-sky-200',
            'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            default => 'bg-amber-50 text-amber-700 border-amber-200',
        };
    }

    /**
     * Dot indicator Tailwind CSS untuk status tugas.
     */
    public function getStatusDotClassAttribute(): string
    {
        return match ($this->status) {
            'in_progress' => 'bg-sky-500 animate-pulse',
            'completed' => 'bg-emerald-500',
            default => 'bg-amber-500 animate-pulse',
        };
    }
}
