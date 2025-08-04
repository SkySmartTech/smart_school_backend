<?php

namespace App\Repositories\All\UserParent;

use App\Repositories\Base\EloquentRepositoryInterface;

interface UserParentInterface extends EloquentRepositoryInterface {
    public function updateByUserId($userId, array $data);

}
