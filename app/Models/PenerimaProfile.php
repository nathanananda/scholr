<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerimaProfile extends Model
{
    protected $table = 'penyalur_profile';

    protected $fillable = [
        'user_id',
        'organization_name',
        'contact_person',
        'contact_email',
        'contact_phone',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
