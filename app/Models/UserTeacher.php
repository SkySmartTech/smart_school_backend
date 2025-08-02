<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTeacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacherGrades',
        'subjects',
        'teacherClass',
        'staffNo',
        'medium',
        'userId',
        'userType',
        'userRole',
        'modifiedBy'
    ];

    protected $casts = [
        'teacherGrades' => 'array',
        'teacherClass' => 'array',
        'subjects' => 'array',
        'medium' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
