<?php

namespace Modules\Content\Repositories\Eloquent;

use Modules\Content\Repositories\ContentRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use DB;

class EloquentContentRepository extends EloquentBaseRepository implements ContentRepository
{

	public function filter($category_id,$role_id)
	{
		$current_date = date('Y-m-d');
		$story        = DB::table('stories as cc')
			->join('content__usergroups as cug', 'cug.content_id', ' =', 'cc.id')
			->join('content__multiplecategorycontents as cm','cm.content_id', '=', 'cc.id')
			->select('cc.*')
			->where('cm.category_id', '=', $category_id)
			->where('cc.expiry_date', '>=', $current_date)
			->where('cug.role_id', '=', $role_id)
			->orderBy('cc.id', 'desc')
			->paginate(12);

		return $story;
	}

	public function getStoryByCategory($category_id,$role_id)
	{
		$current_date = date('Y-m-d');
		$setexist     = DB::table('stories as cc')
			->join('content__usergroups as cug', 'cug.content_id', '=','cc.id')
			->join('content__multiplecategorycontents as cm', 'cm.content_id', '=', 'cc.id')
			->where('cc.expiry_date', '>=', $current_date)
			->where('cm.category_id', '=', $category_id)
			->where('cug.role_id', '=', $role_id)
			->orderBy('cc.id', 'desc')
			->take(5)
			->get();

		return $setexist;
	}

	public function searchByTag($tag, $roleId)
	{
		$currentDate = date('Y-m-d');
		$story       = DB::table('stories as cc')
				->join('content__usergroups as cug', 'cug.content_id', '=', 'cc.id')
				->select('cc.*')
				->where('cc.tags', 'like', '%' . $tag . '%')
				->where('cc.expiry_date', '>=', $currentDate)
				->where('cug.role_id', '=', $roleId)
				->orderBy('cc.id', 'desc')
				->paginate(12);

		return $story;
	}

	public function extractTags()
	{
		return DB::table('stories')->select('tags')->groupBy('tags')->get();
	}

}
