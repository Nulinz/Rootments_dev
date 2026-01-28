<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Clusterstore extends Model
{
    use HasFactory;

    protected $table = 'cluster_store';

    protected static function booted()
    {
        static::addGlobalScope('active', function (Builder $builder) {
            $builder->where('status', 1);
        });
    }

    public function m_cluster()
    {
        return $this->belongsTo(Cluster::class, 'cluster_id');
    }
}
