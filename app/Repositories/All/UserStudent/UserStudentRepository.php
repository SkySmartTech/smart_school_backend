<?php

namespace App\Repositories\All\UserStudent;

use App\Models\UserStudent;
use App\Repositories\All\UserStudent\UserStudentInterface;
use App\Repositories\Base\BaseRepository;

class UserStudentRepository extends BaseRepository implements UserStudentInterface
{
    /**
     * @var UserStudent
     */
    protected $model;

    /**
     *
     * @param UserStudent $model
     */
    public function __construct(UserStudent $model)
    {
        $this->model = $model;
    }

    public function updateByUserId($userId, $data)
    {
        return UserStudent::where('userId', $userId)->update($data);
    }
}
