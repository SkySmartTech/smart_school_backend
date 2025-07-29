<?php

namespace App\Repositories\All\UserType;

use App\Models\UserType;
use App\Repositories\All\UserType\UserTypeInterface;
use App\Repositories\Base\BaseRepository;

class UserTypeRepository extends BaseRepository implements UserTypeInterface
{
    /**
     * @var UserType
     */
    protected $model;

    /**
     *
     * @param UserType $model
     */
    public function __construct(UserType $model)
    {
        $this->model = $model;
    }
}
