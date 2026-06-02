<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarshipCriteria extends Model
{
    protected $table = 'scholarship_criteria';

    protected $guarded = [];

    public function scholarship()
    {
        return $this->belongsTo(Scholarships::class, 'scholarship_id');
    }

    public function ranges()
    {
        return $this->hasMany(
            CriteriaRange::class,
            'criteria_id'
        );
    }
}
