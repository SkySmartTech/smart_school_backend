<?php

namespace App\Repositories\All\School;

use App\Models\School;
use App\Repositories\All\School\SchoolInterface;
use App\Repositories\Base\BaseRepository;

class SchoolRepository extends BaseRepository implements SchoolInterface
{
    /**
     * @var School
     */
    protected $model;

    /**
     *
     * @param School $model
     */
    public function __construct(School $model)
    {
        $this->model = $model;
    }
}
