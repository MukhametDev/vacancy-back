<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    protected $table = 'vacancies';

    protected $fillable = [
        'title',
        'company',
        'description',
        'skills',
        'additional_info',
        'status',
        'user_id',
    ];
}
