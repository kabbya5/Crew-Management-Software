<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrewService extends Model
{
    protected $table = 'crew_services';

    protected $fillable = [
        'crew_id',
        'rank',
        'vessel_name',
        'vessel_type',
        'dwt_bhp',
        'imo_number',
        'vessel_flag',
        'main_engine',
        'sign_on',
        'sign_off',
    ];

    protected $casts = [
        'sign_on' => 'date',
        'sign_off' => 'date',
    ];

    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }
}