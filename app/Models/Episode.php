<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Episode extends Model
{
    use HasFactory;

    protected $fillable = [
        'assis_id',
        'episode',
        'comment',
    ];

    public function assis(): BelongsTo
    {
        return $this->belongsTo(Assis::class);
    }
}
