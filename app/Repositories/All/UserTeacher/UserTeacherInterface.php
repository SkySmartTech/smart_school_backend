<?php

namespace App\Repositories\All\UserTeacher;

use App\Repositories\Base\EloquentRepositoryInterface;

interface UserTeacherInterface extends EloquentRepositoryInterface {
    public function updateByUserId($userId, array $data);
}
