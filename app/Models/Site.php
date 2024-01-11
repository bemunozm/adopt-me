<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function pets(): HasMany{
        return $this->hasMany(Pet::class);
    }

    public function organization(): BelongsTo{
        return $this->belongsTo(Organization::class);
    }

}
