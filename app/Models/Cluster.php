<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Cluster extends Model
{
    use HasFactory;

    protected  $table = 'm_cluster';

    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('status', 1);
        });
    }

    public function cluster_store()
    {

        return $this->hasMany(Clusterstore::class, 'cluster_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'cl_name', 'id')->where('store_id', 12)->where('id', auth()->user()->id);
    }
}
