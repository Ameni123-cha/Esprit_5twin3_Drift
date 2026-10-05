<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alert extends Model
{
    /** @use HasFactory<\Database\Factories\AlertFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'compliance_check_id',
        'alert_type',
        'severity',
        'title',
        'description',
        'detected_at',
        'status',
        'resolution',
    ];

    protected function casts(): array
    {
        return [
            'detected_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function complianceCheck(): BelongsTo
    {
        return $this->belongsTo(ComplianceCheck::class);
    }
}
