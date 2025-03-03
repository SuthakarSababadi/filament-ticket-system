<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $fillable = ['name'];


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
