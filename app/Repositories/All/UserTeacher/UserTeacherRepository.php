<?php

namespace App\Repositories\All\UserTeacher;

use App\Models\UserTeacher;
use App\Repositories\All\UserTeacher\UserTeacherInterface;
use App\Repositories\Base\BaseRepository;

class UserTeacherRepository extends BaseRepository implements UserTeacherInterface
{
    /**
     * @var UserTeacher
     */
    protected $model;

    /**
     *
     * @param UserTeacher $model
     */
    public function __construct(UserTeacher $model)
    {
        $this->model = $model;
    }

    public function updateByUserId($userId, $data)
    {
        return UserTeacher::where('userId', $userId)->update($data);
    }
}
