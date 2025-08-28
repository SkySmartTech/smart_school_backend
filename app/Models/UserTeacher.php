<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTeacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacherGrade',
        'subject',
        'teacherClass',
        'staffNo',
        'medium',
        'userId',
        'userType',
        'modifiedBy'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}
