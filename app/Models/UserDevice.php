<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'ip',
        'device_type',
        'device_model',
        'os_version',
        'app_version',
    ];
}
