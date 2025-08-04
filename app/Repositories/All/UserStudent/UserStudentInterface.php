<?php

namespace App\Repositories\All\UserStudent;

use App\Repositories\Base\EloquentRepositoryInterface;

interface UserStudentInterface extends EloquentRepositoryInterface {
        public function updateByUserId($userId, array $data);

}
