<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'barcode',
        'sku',
        'category',
        'origin',
        'ingredients',
        'producer_id',
        'transformer_id',
        'status',
    ];

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class);
    }

    public function transformer(): BelongsTo
    {
        return $this->belongsTo(Transformer::class);
    }

    public function environmentalFootprint(): HasOne
    {
        return $this->hasOne(EnvironmentalFootprint::class);
    }

    public function supplyChainTraces(): HasMany
    {
        return $this->hasMany(SupplyChainTrace::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    public function aiAnalyses(): HasMany
    {
        return $this->hasMany(AIAnalysis::class);
    }

    public function personalRatings(): HasMany
    {
        return $this->hasMany(PersonalRating::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function openAlerts(): HasMany
    {
        return $this->hasMany(Alert::class)->whereIn('status', ['open', 'investigating']);
    }

    public function averageRating(): ?float
    {
        $avg = $this->approvedReviews()->avg('rating');

        return $avg !== null ? round((float) $avg, 1) : null;
    }

    public function environmentalClaims(): HasMany
    {
        return $this->hasMany(EnvironmentalClaim::class);
    }

    public function complianceChecks(): HasMany
    {
        return $this->hasMany(ComplianceCheck::class);
    }
}
