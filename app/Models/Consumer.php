<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consumer extends Model
{
    /** @use HasFactory<\Database\Factories\ConsumerFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'preferences',
        'sustainability_level',
        'dietary_restrictions',
        'allergies',
        'budget_range',
    ];

    protected function casts(): array
    {
        return [
            'preferences' => 'array',
            'dietary_restrictions' => 'array',
            'allergies' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function personalRatings(): HasMany
    {
        return $this->hasMany(PersonalRating::class);
    }
}
