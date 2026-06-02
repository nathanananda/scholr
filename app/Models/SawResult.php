<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SawResult extends Model
{
    protected $table = 'saw_result';

    protected $guarded = []; // ← tambahkan ini

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
    public function criteria()
    {
        return $this->belongsTo(ScholarshipCriteria::class, 'criteria_id');
    }
}
