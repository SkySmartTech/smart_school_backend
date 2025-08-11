<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserParent extends Model
{
    use HasFactory;

    protected $fillable = [
        'studentAdmissionNo',
        'parentContact',
        'profession',
        'relation',
        'userId',
        'userType',
    ];

    public function student()
    {
        return $this->hasOne(UserStudent::class, 'studentAdmissionNo', 'studentAdmissionNo');
    }

}
