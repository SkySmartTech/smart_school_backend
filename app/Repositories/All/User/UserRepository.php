<?php

namespace App\Repositories\All\User;

use App\Models\User;
use App\Repositories\All\User\UserInterface;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository implements UserInterface
{
    /**
     * @var User
     */
    protected $model;

    /**
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function search(string $keyword): Collection
    {
        return User::where('employeeName', 'like', "%{$keyword}%")
                ->orWhere('username', 'like', "%{$keyword}%")
                ->orWhere('epf', 'like', "%{$keyword}%")
                ->orWhere('department', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%")
                ->orWhere('contact', 'like', "%{$keyword}%")
                ->orWhere('userType', 'like', "%{$keyword}%")
                ->get();
    }
}
