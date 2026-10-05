<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComplianceCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'environmental_claim_id',
        'product_id',
        'check_type',
        'status',
        'result_summary',
        'notes',
        'checked_at',
    ];

    protected function casts(): array
    {
        return [
            'checked_at' => 'date',
        ];
    }

    public function environmentalClaim(): BelongsTo
    {
        return $this->belongsTo(EnvironmentalClaim::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }
}
