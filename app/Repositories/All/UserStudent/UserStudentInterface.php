<?php

namespace App\Repositories\All\UserStudent;

use App\Repositories\Base\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface UserStudentInterface extends EloquentRepositoryInterface {
        public function updateByUserId($userId, array $data);
        public function search(string $keyword): Collection;


}
