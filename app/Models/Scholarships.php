<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scholarships extends Model
{
    protected $table = 'scholarships';

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'announcement_date' => 'date',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class, 'scholarship_id');
    }



    public function faqs()
    {
        return $this->hasMany(ScholarshipFaq::class, 'scholarship_id');
    }

    

    public function penyalur()
    {
        return $this->belongsTo(User::class, 'penyalur_id');
    }

    public function documentTemplates()
    {
        return $this->hasMany(ScholarshipDocument::class, 'scholarship_id');
    }
    public function documents()
    {
        return $this->hasMany(ScholarshipDocument::class, 'scholarship_id');
    }

    public function criteria()
    {
        return $this->hasMany(ScholarshipCriteria::class, 'scholarship_id');
    }
}
