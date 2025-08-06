<?php

namespace App\Repositories\All\UserParent;

use App\Models\UserParent;
use App\Repositories\All\UserParent\UserParentInterface;
use App\Repositories\Base\BaseRepository;

class UserParentRepository extends BaseRepository implements UserParentInterface
{
    /**
     * @var UserParent
     */
    protected $model;

    /**
     *
     * @param UserParent $model
     */
    public function __construct(UserParent $model)
    {
        $this->model = $model;
    }

    public function updateByUserId($userId, $data)
    {
        return UserParent::where('userId', $userId)->update($data);
    }
}
