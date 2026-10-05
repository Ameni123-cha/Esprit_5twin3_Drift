<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIAnalysis extends Model
{
    /** @use HasFactory<\Database\Factories\AIAnalysisFactory> */
    use HasFactory;

    protected $table = 'ai_analyses';

    protected $fillable = [
        'product_id',
        'analysis_type',
        'greenwashing_score',
        'credibility_rating',
        'ai_summary',
        'model_used',
        'is_demo',
    ];

    protected function casts(): array
    {
        return [
            'greenwashing_score' => 'integer',
            'is_demo' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
