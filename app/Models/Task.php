<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;


    protected $guarded = [];

    public function c_by()
    {
        return $this->belongsTo(User::class,'assign_by');
    }

    public function assign()
    {
        return $this->belongsTo(User::class,'assign_to');
    }
    public function cat()
    {
        return $this->belongsTo(Category::class,'category_id');
    }
    public function sub()
    {
        return $this->belongsTo(SubCategory::class,'subcategory_id');
    }

}
