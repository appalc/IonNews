<?php

namespace Modules\Content\Repositories\Eloquent;

use Modules\Content\Repositories\CategoryRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

class EloquentCategoryRepository extends EloquentBaseRepository implements CategoryRepository
{

	public function getAllPriority($key, $value)
	{
		return $this->model->where($key, '>=', $value)->get();
	}

	public function updatePriority($key, $value)
	{
		return $this->model->where('id', $key)->update(['priority' => $value]);
	}

}
