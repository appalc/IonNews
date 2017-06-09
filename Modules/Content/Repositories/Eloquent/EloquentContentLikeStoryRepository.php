<?php

namespace Modules\Content\Repositories\Eloquent;

use Modules\Content\Repositories\ContentLikeStoryRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentContentLikeStoryRepository extends EloquentBaseRepository implements ContentLikeStoryRepository
{
		 public function checkLikeorNot($data ,$user_id)
      {  
        $likeData=$this->model
                      ->where('content_id','=',$data->id)
                      ->where('user_id','=',$user_id)
                      ->get();
                      $like_count=count($likeData);
                      return $like_count;                              

      }
}
