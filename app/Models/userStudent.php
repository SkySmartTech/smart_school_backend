<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userStudent extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'userType',
        'studentGrade',
        'medium',
        'studentClass',
        'parentNo',
        'parentProfession',
        'userRole',
        'modifiedBy',
        'studentAdmissionNo',
        'userId',
    ];
}
