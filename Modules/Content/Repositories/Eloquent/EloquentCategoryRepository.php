<?php

namespace Modules\Content\Repositories\Eloquent;

use Modules\Content\Repositories\CategoryRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;

use DB;

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

	public function getCategoriesByUser($userId)
	{
		$categories = DB::table('users as usr')
			->join('user_groups as ug', 'ug.id', '=', 'usr.user_group_id')
			->select('ug.category_id')
			->where('usr.id', '=', $userId)
			->get()
			->pluck('category_id')
			->first();

			if (empty($categories)) {
				return [];
			}

		return DB::table('categories as cat')
			->where('cat.status', '=', 1)
			->whereIn('cat.id', json_decode($categories, true))
			->get();
	}

}
