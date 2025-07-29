<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'classId',
        'class',
        'description',
        'gradeId',
    ];
}
