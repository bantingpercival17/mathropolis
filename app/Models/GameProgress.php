<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameProgress extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'coins',
        'progress_data',
        'budget_plan',
    ];
}
