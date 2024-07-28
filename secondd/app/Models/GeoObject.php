<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GeoObject extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "point",
        "kind",
        "description",
        "address",
    ];

    public function searchResults(): BelongsToMany{
        return $this->belongsToMany(GeoObject::class, 'search_result_has_geo_object', 'search_result_id', 'geo_object_id');
    }
}
