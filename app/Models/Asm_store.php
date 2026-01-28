<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asm_store extends Model
{
    use HasFactory;

    protected $fillable = ['store','emp_id'];
    protected $table = 'asm_store';
}
