<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['year', 'make', 'model', 'vin'];

    /**
     * A vehicle has many keys
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function keys() {
        return $this->hasMany('App\Models\Key');
    }
}
