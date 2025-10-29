<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassTeacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacherGrade',
        'teacherClass',
        'name',
        'staffNo',
    ];

    public function userTeacher()
    {
        return $this->belongsTo(UserTeacher::class, 'staffNo', 'staffNo');
    }
}
