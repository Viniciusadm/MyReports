<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'yes',
        'no',
        'deactivated_at',
    ];

    public function answer(): HasOne
    {
        return $this->hasOne(Answer::class);
    }
}
