<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function sites(): HasMany{
        return $this->hasMany(Site::class);
    }

    public function pets(): BelongsToMany{
        return $this->belongsToMany(Pet::class)->withPivot('id', 'title','meeting_date', 'status', 'meeting_type', 'notes');
    }

    public function collections(): HasMany{
        return $this->hasMany(Collection::class);
    }
}
