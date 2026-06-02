<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationDocument extends Model
{
    protected $table = 'application_document';

    protected $fillable = [
        'application_id',
        'scholarship_document_id',
        'file_path',
        'original_filename',
        'mime_type',
        'size_kb',
        'status',
        'rejection_note',
        'uploaded_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function template()
    {
        return $this->belongsTo(
            ScholarshipDocument::class,
            'scholarship_document_id'
        );
    }

    public function scholarshipDocument()
    {
        return $this->belongsTo(
            ScholarshipDocument::class,
            'scholarship_document_id'
        );
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getIsApprovedAttribute()
    {
        return $this->status === 'approved';
    }

    public function getIsRejectedAttribute()
    {
        return $this->status === 'rejected';
    }

    public function getIsUploadedAttribute()
    {
        return $this->status === 'uploaded';
    }
}
