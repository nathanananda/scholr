<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenyalurProfile extends Model
{
    protected $table = 'penyalur_profile';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
