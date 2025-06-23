<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Step extends Model
{
    protected $fillable = ['recipe_id', 'description', 'step_number'];

    public function recipe(): BelongsTo {
        return $this->belongsTo(Recipe::class);
    }
}
