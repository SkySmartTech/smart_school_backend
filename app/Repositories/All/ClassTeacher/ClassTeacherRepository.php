<?php

namespace App\Repositories\All\ClassTeacher;

use App\Models\ClassTeacher;
use App\Repositories\All\ClassTeacher\ClassTeacherInterface;
use App\Repositories\Base\BaseRepository;

class ClassTeacherRepository extends BaseRepository implements ClassTeacherInterface
{
    /**
     * @var ClassTeacher
     */
    protected $model;

    /**
     *
     * @param ClassTeacher $model
     */
    public function __construct(ClassTeacher $model)
    {
        $this->model = $model;
    }
}
