<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Crew extends Model
{
    protected $fillable = [
        'user_id',
        'crew_id',
        'photo',
        'place_of_birth',
        'full_name',
        'rank_applied_for',
        'nationality',
        'date_of_birth',
        'gender',
        'mobile',
        'email',
        'current_location',
        'available_from',
        'height',
        'weight',
        'blood_group',
        'shoe_size',
        'shoe_size_standard',
        'boiler_suit_size',
        'english_proficiency',
        'chinese_proficiency',
        'remarks',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'available_from' => 'date',
        ];
    }

    public function getProfileCompletionAttribute(): int
    {
        $sections = [
            'personal' => [
                'weight' => 30,
                'completed' => filled($this->full_name)
                && filled($this->rank_applied_for)
                && filled($this->nationality)
                && filled($this->date_of_birth)
                && filled($this->gender)
                && filled($this->mobile)
                && filled($this->email)
                && filled($this->place_of_birth)
                && filled($this->current_location)
                && filled($this->available_from),
            ],

            'documents' => [
                'weight' => 25,
                'completed' => $this->documents()->exists(),
            ],

            'services' => [
                'weight' => 20,
                'completed' => $this->services()->exists(),
            ],

            'next_of_kin' => [
                'weight' => 10,
                'completed' => $this->nextOfKins()->exists(),
            ],

            'stcw' => [
                'weight' => 15,
                'completed' => $this->stcws()->exists(),
            ],
        ];

        $percentage = 0;

        foreach ($sections as $key => $section) {
            if ($section['completed']) {
                $percentage += $section['weight'];
            }
        }

        return $percentage;
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CrewDocument::class);
    }

    public function services()
    {
        return $this->hasMany(CrewService::class, 'crew_id');
    }

    public function nextOfKins()
    {
        return $this->hasMany(CrewNextOfKin::class,'crew_id');
    }
    public function stcws()
    {
        return $this->hasMany(CrewStcw::class, 'crew_id');
    }
    
    public function correctionItems(){
        return $this->hasMany(CrewCorrectionItem::class,'crew_id');
    }
}

