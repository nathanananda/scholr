<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarshipFaq extends Model
{
    protected $table = 'scholarship_faq';

    protected $fillable = [
        'scholarship_id',
        'question',
        'answer',
        'order',
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarships::class);
    }
}
