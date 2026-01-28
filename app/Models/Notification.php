<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

   protected $fillable = ['user_id', 'noty_type', 'type_id','title','body','c_by'];

}
