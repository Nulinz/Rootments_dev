<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoTask extends Model
{
    protected $table = 'auto_tasks';

    protected $fillable = [
        'task_title',
        'category_id',
        'subcategory_id',
        'assign_to',
        'task_description',
        'start_date',
        'priority',
        'task_file',
        'created_by',
    ];

    // protected $casts = [
    //     'assign_to' => 'array',
    // ];
}
