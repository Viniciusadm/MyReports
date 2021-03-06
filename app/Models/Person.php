<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nickname',
        'birth_date',
        'email',
        'phone',
        'address',
        'description',
        'twitter',
        'instagram',
        'image',
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }
}
