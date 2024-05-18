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

    public function getCompareTwoArts($likedArtId, $unlikedArtIds = [])
    {
        if (!$likedArtId) {
            $firstId = $this->model
                ->whereNotIn('id', $unlikedArtIds)->inRandomOrder()->first()->id;

        } else {
            $firstId = $likedArtId;
        }
        $unlikedArtIds[] = $firstId;
        $second = $this->model
            ->whereNotIn('id', $unlikedArtIds)->inRandomOrder()->first();
        if (!$second) return null;
        return ["first_id" => $firstId, "second_id" => $second->id];

    }

}
