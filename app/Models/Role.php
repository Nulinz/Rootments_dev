<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;



public function assignedRoles()
{
    return $this->belongsToMany(Role::class, 'role_based', 'role_id', 'assign_role_id');
}

public function assignedRolesforstore()
    {
        return $this->belongsToMany(Role::class,  'role_based_store', 'role_id', 'assign_role_id');
    }

    public function users()
    {
        return $this->hasMany(User::class,'role_id');
    }




    // public function name_store()
    // {
    //     return $this->belongsToMany(User::class, 'role_based', 'role_id', 'assign_role_id');
    // }
}
