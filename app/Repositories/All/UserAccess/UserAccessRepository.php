<?php

namespace App\Repositories\All\UserAccess;

use App\Models\UserAccess;
use App\Repositories\All\UserAccess\UserAccessInterface;
use App\Repositories\Base\BaseRepository;

class UserAccessRepository extends BaseRepository implements UserAccessInterface
{
    /**
     * @var UserAccess
     */
    protected $model;

    /**
     *
     * @param UserAccess $model
     */
    public function __construct(UserAccess $model)
    {
        $this->model = $model;
    }
}
