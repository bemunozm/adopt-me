<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Collection extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function organization(): BelongsTo{
        return $this->belongsTo(Organization::class);
    }

    public function users(): BelongsToMany{
        return $this->belongsToMany(User::class)->withPivot('id','amount', 'status', 'file');
    }

    public function approvedDonations()
    {
        return $this->belongsToMany(User::class, 'collection_user')
                ->withPivot('amount', 'status', 'file')
                ->wherePivot('status', 'Aprobado');
    }
    
}
