<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplyChainTrace extends Model
{
    /** @use HasFactory<\Database\Factories\SupplyChainTraceFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'distributor_id',
        'current_stage',
        'current_location_lat',
        'current_location_lon',
        'path_history',
        'status',
        'total_distance_km',
    ];

    protected function casts(): array
    {
        return [
            'path_history' => 'array',
            'current_location_lat' => 'decimal:7',
            'current_location_lon' => 'decimal:7',
            'total_distance_km' => 'decimal:2',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }
}
