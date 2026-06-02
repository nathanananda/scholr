<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationCriteriaValue extends Model
{
    protected $table = 'application_criteria_value';

    protected $fillable = [
        'application_id',
        'criteria_id',
        'value',
        'criteria_range_id',
    ];

    protected $casts = [
        'value' => 'float',
    ];

    public function criteria()
    {
        return $this->belongsTo(
            ScholarshipCriteria::class,
            'criteria_id'
        );
    }

    public function range()
    {
        return $this->belongsTo(
            CriteriaRange::class,
            'criteria_range_id'
        );
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
