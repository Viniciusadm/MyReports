<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assis extends Model
{
    use HasFactory;

    protected $table = 'assis';

    protected $fillable = [
        'collection_id',
        'name',
        'total',
        'type',
        'status',
        'image',
        'average_time',
        'year',
        'sinopse',
        'order',
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(AssisCollection::class, 'collection_id');
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }
}
