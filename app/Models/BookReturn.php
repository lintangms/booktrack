<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookReturn extends Model
{
    protected $fillable = [
        'return_code',
        'loan_id',
        'return_date',
        'late_days',
        'fine_amount',
        'condition',
        'fine_status',
        'notes',
    ];

    protected $casts = [
        'return_date' => 'date',
        'late_days'   => 'integer',
        'fine_amount' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (BookReturn $return) {
            if (empty($return->return_code)) {
                $return->return_code = self::generateCode();
            }
        });
    }

    /** Format: KB-YYYYMMDD-001 (urutan reset tiap hari) */
    public static function generateCode(): string
    {
        $prefix = 'KB-' . now()->format('Ymd') . '-';

        $last = self::where('return_code', 'like', $prefix . '%')
            ->orderByDesc('return_code')
            ->value('return_code');

        $next = $last ? ((int) substr($last, -3)) + 1 : 1;

        return $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }
}