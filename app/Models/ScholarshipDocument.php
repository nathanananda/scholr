<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarshipDocument extends Model
{
    protected $table = 'scholarship_document';

    protected $fillable = [
        'scholarship_id',
        'name',
        'description',
        'is_required',
        'allowed_types',
        'max_size_kb',
        'order',
    ];

    protected $casts = [
        'allowed_types' => 'array',
        'is_required' => 'boolean',
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarships::class);
    }

    public function applicationDocuments()
    {
        return $this->hasMany(ApplicationDocument::class, 'scholarship_document_id');
    }
}
