<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Walkin extends Model
{
    use HasFactory;

    protected $table='walkin';

    public function createdBy()
    {
        return $this->belongsTo(User::class,'c_by');
    }
}
