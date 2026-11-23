<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatingHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'day',
        'time',
        'is_closed',
        'sort_order',
    ];

    protected $casts = [
        'is_closed' => 'boolean',
        'sort_order' => 'integer',
    ];
}
