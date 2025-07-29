<?php

namespace App\Repositories\All\UserRole;

use App\Models\UserRole;
use App\Repositories\All\UserRole\UserRoleInterface;
use App\Repositories\Base\BaseRepository;

class UserRoleRepository extends BaseRepository implements UserRoleInterface
{
    /**
     * @var UserRole
     */
    protected $model;

    /**
     *
     * @param UserRole $model
     */
    public function __construct(UserRole $model)
    {
        $this->model = $model;
    }
}
