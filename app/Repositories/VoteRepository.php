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

    public function updateLikes($userId, $likedId, $didntLikeId): void
    {
        $this->model->where('user_id', $userId)->where("is_liked", 1)->delete();
        $this->model->where('user_id', $userId)->where("art_id", $didntLikeId)->delete();

        $this->model->create([
            "user_id" => $userId,
            "art_id" => $likedId,
            "is_voted" => 1,
            "is_liked" => 1,
        ]);
        $this->model->create([
            "user_id" => $userId,
            "art_id" => $didntLikeId,
            "is_voted" => 1,
            "is_liked" => 0,
        ]);
    }

    public function getArtsThatUserDidntLike($userId)
    {
        return $this->model
            ->where("user_id", $userId)
            ->where("is_voted", 1)
            ->where("is_liked", 0)
            ->get()->pluck('art_id')->toarray();
    }

    public function getArtIdThatUserLiked($userId)
    {

        $likedVote = $this->model
            ->where("user_id", $userId)
            ->where("is_voted", 1)
            ->where("is_liked", 1)
            ->first();
        if (!$likedVote)
            return null;
        return $likedVote->art_id;
    }

}
