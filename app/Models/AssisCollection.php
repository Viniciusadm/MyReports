<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssisCollection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
    ];

    public function assis(): HasMany
    {
        return $this->hasMany(Assis::class, 'collection_id');
    }
}
