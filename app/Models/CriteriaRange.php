<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriteriaRange extends Model
{
    protected $table = 'criteria_range';

    protected $guarded = [];

    public function criteria()
    {
        return $this->belongsTo(ScholarshipCriteria::class, 'criteria_id');
    }
}
