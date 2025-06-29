<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    protected $fillable = ['name', 'carbs', 'fat', 'protein'];

     public function recipes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class)
                    ->withPivot('quantity');
    }
}
