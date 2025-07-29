<?php

namespace App\Repositories\All\GradeClass;

use App\Models\GradeClass;
use App\Repositories\All\GradeClass\GradeClassInterface;
use App\Repositories\Base\BaseRepository;

class GradeClassRepository extends BaseRepository implements GradeClassInterface
{
    /**
     * @var GradeClass
     */
    protected $model;

    /**
     *
     * @param GradeClass $model
     */
    public function __construct(GradeClass $model)
    {
        $this->model = $model;
    }
}
