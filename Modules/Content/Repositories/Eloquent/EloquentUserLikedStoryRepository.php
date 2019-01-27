<?php

namespace Modules\Content\Repositories\Eloquent;

use Modules\Content\Repositories\UserLikedStoryRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentUserLikedStoryRepository extends EloquentBaseRepository implements UserLikedStoryRepository
{
	public function checkLikeorNot($data, $user_id)
	{
		return count($this->model->where('story_id', '=', $data->id)->where('user_id', '=', $user_id)->get());
	}

}
