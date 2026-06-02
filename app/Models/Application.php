<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'applications';

    protected $fillable = [
        'user_id',
        'scholarship_id',
        'status',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function criteriaValues()
    {
        return $this->hasMany(ApplicationCriteriaValue::class);
    }

    public function sawResults()
    {
        return $this->hasMany(SawResult::class, 'application_id');
    }

    public function documents()
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarships::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(ApplicationStatusLog::class);
    }
}
