<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmartResult extends Model
{
    protected $table = 'smart_results';

    protected $guarded = [];

    public function criteria()
    {
        return $this->belongsTo(ScholarshipCriteria::class, 'criteria_id');
    }
}
