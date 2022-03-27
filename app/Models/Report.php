<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
      'title',
      'report',
      'humor',
      'type',
      'persons_id'
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }
}
