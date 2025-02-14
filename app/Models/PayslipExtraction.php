<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayslipExtraction extends Model
{
    public $fillable = [
        'extracted_text',
        'extracted_at'
    ];

    public $casts = [
        'extracted_at' => 'datetime'
    ];

    public $hidden = [
        'extracted_text',
    ];
}
