<?php

namespace App\Repositories\All\Marks;

use App\Models\Marks;
use App\Repositories\All\Marks\MarksInterface;
use App\Repositories\Base\BaseRepository;

class MarksRepository extends BaseRepository implements MarksInterface
{
    /**
     * @var Marks
     */
    protected $model;

    /**
     *
     * @param Marks $model
     */
    public function __construct(Marks $model)
    {
        $this->model = $model;
    }
}
