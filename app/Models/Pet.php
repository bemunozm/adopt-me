<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pet extends Model
{
    use HasFactory;
    
    protected $guarded = [];


    public function site(): BelongsTo{
        return $this->belongsTo(Site::class);
    }

    public function adopters(): BelongsToMany{
        return $this->belongsToMany(Adopter::class)->withPivot('id','status','date');
    }

    public function images(): HasMany{
        return $this->hasMany(Image::class);
    }

    public function vaccines(): HasMany{
        return $this->hasMany(Vaccine::class);
    }

    public function organizations(): BelongsToMany{
        return $this->belongsToMany(Organization::class)->withPivot('id', 'title','meeting_date', 'status', 'meeting_type', 'notes');
    }

    public function firstImage()
    {
        return $this->hasOne(Image::class)->ofMany([], function ($query) {
            $query->orderBy('id', 'asc'); 
        });
    }
    
}
