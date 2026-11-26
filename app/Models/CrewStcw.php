<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrewStcw extends Model
{
    protected $table = 'crew_stcws';

    protected $fillable = [
        'crew_id',
        'course_name',
        'certificate_number',
        'issue_date',
        'expiry_date',
        'no_expiry',
        'file_path',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'no_expiry' => 'boolean',
    ];

    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }
}
