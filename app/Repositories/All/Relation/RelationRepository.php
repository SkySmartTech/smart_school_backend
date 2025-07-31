<?php

namespace App\Repositories\All\Relation;

use App\Models\Relation;
use App\Repositories\All\Relation\RelationInterface;
use App\Repositories\Base\BaseRepository;

class RelationRepository extends BaseRepository implements RelationInterface
{
    /**
     * @var Relation
     */
    protected $model;

    /**
     *
     * @param Relation $model
     */
    public function __construct(Relation $model)
    {
        $this->model = $model;
    }
}
