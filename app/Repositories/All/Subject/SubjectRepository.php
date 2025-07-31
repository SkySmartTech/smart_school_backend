<?php

namespace App\Repositories\All\Subject;

use App\Models\Subject;
use App\Repositories\All\Subject\SubjectInterface;
use App\Repositories\Base\BaseRepository;

class SubjectRepository extends BaseRepository implements SubjectInterface
{
    /**
     * @var Subject
     */
    protected $model;

    /**
     *
     * @param Subject $model
     */
    public function __construct(Subject $model)
    {
        $this->model = $model;
    }
}
