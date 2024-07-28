<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\GeoObject;

class SearchResult extends Model
{
    use HasFactory;

    protected $fillable = [
        "request",
        "result"
    ];

    public function geoObjects(): BelongsToMany{
        return $this->belongsToMany(GeoObject::class, 'search_result_has_geo_object', 'search_result_id', 'geo_object_id');
    }
}
