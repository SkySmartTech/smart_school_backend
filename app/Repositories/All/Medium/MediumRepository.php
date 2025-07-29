<?php

namespace App\Repositories\All\Medium;

use App\Models\Medium;
use App\Repositories\All\Medium\MediumInterface;
use App\Repositories\Base\BaseRepository;

class MediumRepository extends BaseRepository implements MediumInterface
{
    /**
     * @var Medium
     */
    protected $model;

    /**
     *
     * @param Medium $model
     */
    public function __construct(Medium $model)
    {
        $this->model = $model;
    }
}
