<?php

namespace App\Repositories;

use App\Models\Rate;
use App\Models\Vote;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class VoteRepository extends BaseRepository
{
    protected Model $model;

    public function __construct(Vote $model)
    {
        $this->model = $model;
    }
}
