<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTeacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'userType',
        'teacherGrades',
        'teacherClass',
        'subjects',
        'modifiedBy',
        'userRole',
        'medium',
        'staffNo',
        'userId',
    ];

    protected $casts = [
        'teacherGrades' => 'array',
        'subjects' => 'array',
    ];
}
