<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationStatusLog extends Model
{
    protected $table = 'application_status_log';

    protected $fillable = [
        'application_id',
        'status',
        'changed_by',
        'note',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }


    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
