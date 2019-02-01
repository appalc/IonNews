<?php

namespace Modules\Content\Repositories\Eloquent;

use Modules\Content\Repositories\ContentRepository;
use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use DB;

class EloquentContentRepository extends EloquentBaseRepository implements ContentRepository
{

	public function filter($category_id, $role_id)
	{
		$current_date = date('Y-m-d');
		$story        = DB::table('stories as cc')
			->join('content__usergroups as cug', 'cug.content_id', '=', 'cc.id')
			->join('content__multiplecategorycontents as cm','cm.content_id', '=', 'cc.id')
			->select('cc.*')
			->where('cm.category_id', '=', $category_id)
			->where('cc.expiry_date', '>=', $current_date)
			->where('cug.role_id', '=', $role_id)
			->orderBy('cc.id', 'desc')
			->paginate(12);

		return $story;
	}

	public function getStoryByCategory($categoryId)
	{
		return	DB::table('stories as st')
					->join('story_categories as sc', 'sc.story_id', '=', 'st.id')
					->select('st.*')
					->where('sc.category_id', '=', $categoryId)
					->where('st.expiry_date', '>=', date('Y-m-d'))
					->where('st.type', '=', 'news')
					->orderBy('st.id', 'desc')
					->take(5)
					->get();
	}

	public function searchByTag($tag, $categoryIds)
	{
		return DB::table('stories as st')
				->join('story_categories as sc', 'sc.story_id', '=', 'st.id')
				->select('st.*')
				->where('st.tags', 'like', '%' . $tag . '%')
				->where('st.expiry_date', '>=', date('Y-m-d'))
				->whereIn('sc.category_id', $categoryIds)
				->orderBy('st.id', 'desc')
				->paginate(12);
	}

	public function extractTags()
	{
		return DB::table('stories')->select('tags')->groupBy('tags')->get();
	}

	public function getStoriesByCategory($categoryId)
	{
		return	DB::table('stories as st')
					->join('story_categories as sc', 'sc.story_id', '=', 'st.id')
					->select('st.*')
					->where('sc.category_id', '=', $categoryId)
					->where('st.expiry_date', '>=', date('Y-m-d'))
					->where('st.type', '=', 'news')
					->orderBy('st.id', 'desc')
					->paginate(12);
	}

}
