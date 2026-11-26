<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrewDocument extends Model
{
    protected $fillable = [
        'document_type',
        'custom_name',
        'document_number',
        'issue_date',
        'expiry_date',
        'no_expiry',
        'file_path',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'expiry_date' => 'date',
            'no_expiry' => 'boolean',
        ];
    }
}
