<?php

namespace Modules\Content\Repositories\Eloquent;

use Modules\Content\Repositories\CategoryRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentCategoryRepository extends EloquentBaseRepository implements CategoryRepository
{
	public function getAllPriority($key,$value)
	{
		$priority_data =  $this->model
				->where($key, '>=', $value)
				->get();
         return $priority_data;	

	}
	public function updatePriority($key,$value)
	{
		$Details=$this->model		 	
            ->where('id', $key)
            ->update(['priority' => $value]);
        
	}
}
