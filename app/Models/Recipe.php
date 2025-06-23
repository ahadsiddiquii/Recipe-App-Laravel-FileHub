<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recipe extends Model
{
    protected $fillable = ['title'];

    public function ingredients(): BelongsToMany {
        return $this->belongsToMany(Ingredient::class)
                ->withPivot('quantity');
    }

    public function steps(): HasMany {
        return $this->hasMany(Step::class);
    }
}
