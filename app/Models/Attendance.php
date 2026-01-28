<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table='attendance';

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
