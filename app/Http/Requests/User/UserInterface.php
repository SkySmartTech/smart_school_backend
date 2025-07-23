<?php

namespace App\Repositories\All\User;

use App\Repositories\Base\EloquentRepositoryInterface;
use Illuminate\Support\Collection;

interface UserInterface extends EloquentRepositoryInterface {

    /**
     * Search users by keyword.
     *
     * @param string $keyword
     * @return Collection
     */
    public function search(string $keyword): Collection;


}
