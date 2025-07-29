<?php

namespace App\Repositories\All\Grade;

use App\Models\Grade;
use App\Repositories\All\Grade\GradeInterface;
use App\Repositories\Base\BaseRepository;

class GradeRepository extends BaseRepository implements GradeInterface
{
    /**
     * @var Grade
     */
    protected $model;

    /**
     *
     * @param Grade $model
     */
    public function __construct(Grade $model)
    {
        $this->model = $model;
    }
}
