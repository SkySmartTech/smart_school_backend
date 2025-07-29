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
        return User::where('name', 'like', "%{$keyword}%")
                ->orWhere('username', 'like', "%{$keyword}%")
                ->orWhere('address', 'like', "%{$keyword}%")
                ->orWhere('birthDay', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%")
                ->orWhere('contact', 'like', "%{$keyword}%")
                ->orWhere('medium', 'like', "%{$keyword}%")
                ->orWhere('gender', 'like', "%{$keyword}%")
                ->orWhere('userType', 'like', "%{$keyword}%")
                ->orWhere('grade', 'like', "%{$keyword}%")
                ->orWhere('subject', 'like', "%{$keyword}%")
                ->orWhere('class', 'like', "%{$keyword}%")
                ->orWhere('profession', 'like', "%{$keyword}%")
                ->orWhere('parentContact', 'like', "%{$keyword}%")
                ->orWhere('userRole', 'like', "%{$keyword}%")
                ->get();
    }
}
