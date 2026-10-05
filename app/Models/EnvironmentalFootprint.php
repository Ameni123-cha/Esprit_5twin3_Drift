<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnvironmentalFootprint extends Model
{
    /** @use HasFactory<\Database\Factories\EnvironmentalFootprintFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'co2_emissions',
        'water_usage',
        'land_usage',
        'ai_score',
    ];

    protected function casts(): array
    {
        return [
            'co2_emissions' => 'decimal:2',
            'water_usage' => 'decimal:2',
            'land_usage' => 'decimal:2',
            'ai_score' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function indicatorLevel(): string
    {
        $score = $this->ai_score;

        if ($score === null) {
            return 'unknown';
        }

        return match (true) {
            $score >= 75 => 'excellent',
            $score >= 50 => 'good',
            $score >= 25 => 'moderate',
            default => 'poor',
        };
    }
}
