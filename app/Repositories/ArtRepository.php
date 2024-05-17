<?php

namespace App\Repositories;

use App\Models\Art;
use App\Repositories\Base\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class ArtRepository extends BaseRepository
{
    protected Model $model;

    public function __construct(Art $model)
    {
        $this->model = $model;
    }

}
