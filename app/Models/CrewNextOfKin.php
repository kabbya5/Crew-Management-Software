<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrewNextOfKin extends Model
{
    protected $table = 'crew_next_of_kins';
    
    protected $fillable = [
        'full_name',
        'relationship',
        'mobile',
        'email',
        'address',
        'priority',
    ];

    protected function casts(): array
    {
        return [
            'mobile' => 'encrypted',
            'email' => 'encrypted',
            'address' => 'encrypted',
        ];
    }
}
