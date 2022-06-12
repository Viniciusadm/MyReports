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
        'hidden_collection',
        'airing',
        'weekday',
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(AssisCollection::class, 'collection_id');
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }

    public function getFullNameAttribute(): string
    {
        if ($this['hidden_collection']) {
            return $this['name'];
        }

        if ($this['name']) {
            return $this['collection']['name'] .  ' - ' .  $this['name'];
        } else {
            return $this['collection']['name'];
        }
    }

    public function getImageUrlAttribute(): string
    {
        if ($this['image']) {
            return env('APP_URL_IMAGES') . $this['image'];
        } else if ($this['collection']['image']) {
            return env('APP_URL_IMAGES') . $this['collection']['image'];
        } else {
            return '';
        }
    }

    public function getTypeFormattedAttribute(): string
    {
        $types = [
            "anime" => "Anime",
            "dorama" => "Dorama",
            "cartoon" => "Desenho",
            "movie" => "Filme",
            "serie" => "Série",
            "special" => "Especial",
            "specials" => "Especiais",
            "youtube" => "YouTube",
            "other" => "Outro",
        ];

        return $types[$this['type']];
    }

    public function getWeekdayFormattedAttribute(): string
    {
        $weekdays = [
            "" => "",
            "monday" => "Segunda-feira",
            "tuesday" => "Terça-feira",
            "wednesday" => "Quarta-feira",
            "thursday" => "Quinta-feira",
            "friday" => "Sexta-feira",
            "saturday" => "Sábado",
            "sunday" => "Domingo",
        ];

        return $weekdays[$this['weekday']];
    }
}
