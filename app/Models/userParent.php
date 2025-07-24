<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userParent extends Model
{
    use HasFactory;

    protected $fillable = [
        'userType',
        'studentAdmissionNo',
        'parentContact',
        'profession',
        'relation',
        'userId',
    ];
}
