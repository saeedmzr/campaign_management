<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;


class UserRepository extends BaseRepository
{
    protected Model $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

}
