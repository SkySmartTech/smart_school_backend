<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'studentGrade',
        'medium',
        'studentClass',
        'studentAdmissionNo',
        'parentNo',
        'parentProfession',
        'userType',
        'userId',
        'userRole',
        'modifiedBy'
    ];

    public function parent()
    {
        return $this->hasOne(UserParent::class, 'studentAdmissionNo', 'studentAdmissionNo');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}
