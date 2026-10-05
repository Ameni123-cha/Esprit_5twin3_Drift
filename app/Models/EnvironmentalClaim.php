<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EnvironmentalClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'claim_type',
        'title',
        'description',
        'source_document',
        'status',
        'confidence_score',
    ];

    protected function casts(): array
    {
        return [
            'confidence_score' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function complianceChecks(): HasMany
    {
        return $this->hasMany(ComplianceCheck::class);
    }
}
