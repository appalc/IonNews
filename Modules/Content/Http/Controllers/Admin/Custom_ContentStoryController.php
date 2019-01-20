<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Core\Http\Controllers\Admin\AdminBaseController;

use Modules\Content\Entities\Custom_ContentStory;
use Modules\Content\Entities\Content;
use Modules\Content\Entities\StoryCategory;

use Modules\Content\Repositories\ContentRepository;
use Modules\Content\Repositories\Custom_ContentStoryRepository;
use Modules\Content\Repositories\CategoryRepository;
use Modules\User\Repositories\RoleRepository;

use DB;
use Log;

class Custom_ContentStoryController extends AdminBaseController
{
	/**
	 * @var Custom_ContentStoryRepository
	 */
	private $custom_contentstory;

	/**
	 * @var string Story type
	 */
	private $storyType = 'ads';

	public function __construct(ContentRepository $content, CategoryRepository $category, RoleRepository $role, StoryCategory $storyCategory)
	{
		parent::__construct();

		$this->content       = $content;
		$this->category      = $category;
		$this->role          = $role;
		$this->storyCategory = $storyCategory;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$customStories = content::where('type', '=', $this->storyType)->get();
		$position      = DB::table('storypositions')->pluck('positions');
		$position      = (sizeof($position)) ? $position[0] : 2;

		return view('content::admin.custom_contentstories.index', compact('customStories', 'position'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$categories = $this->category->getByAttributes(['status' => 1])->pluck('name', 'id');

		return view('content::admin.custom_contentstories.create', compact('categories'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$customStory = $request->all();
		$image   = '';

		$storyCategories = '';
		if (array_key_exists('category_id', $customStory)) {
			$storyCategories       = $customStory['category_id'];
			$customStory['all_category'] = json_encode($storyCategories);
			$customStory['category_id']  = count($customStory['category_id']);
		}

		if ($request->hasFile('img')) {
			$image_name = $_FILES['img']['name'];
			$request->file('img')->move(env('IMG_URL') . '/crawle_image', $image_name);

			$image = env('IMG_URL1') . '/crawle_image/' . $image_name;
		}

		$customStory['image'] = $image;
		$customStory['type']  = $this->storyType;
		$storyId              = $this->content->create($customStory)->id;

		if(!$storyCategories) {
			$storyCategories             = $this->category->getByAttributes(['status' => 1])->pluck('id');
			$customStory['all_category'] = json_encode($storyCategories);

		}

		foreach ($storyCategories as $value) {
			$this->storyCategory->create(['category_id' => $value, 'story_id' => $storyId]);
		}

		return redirect()->route('admin.content.custom_contentstory.index')
			->withSuccess(trans('core::core.messages.resource created', ['name' => trans('content::custom_contentstories.title.custom_contentstories')]));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  Custom_ContentStory $custom_contentstory
	 * @return Response
	 */
	public function edit(Content $content)
	{
		$categories = $this->category->getByAttributes(['status' => 1])->pluck('name', 'id');

		return view('content::admin.custom_contentstories.edit', compact('content', 'categories'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Content $content
	 * @param  Request $request
	 * @return Response
	 */
	public function update(Content $content, Request $request)
	{
		$storyId = $content->id;
		$setData = $request->all();
		$image   = '';
		if ($request->hasFile('img')) {
			$image_name = $_FILES['img']['name'];
			$request->file('img')->move(env('IMG_URL').'/crawle_image',$image_name);
			$image      = env('IMG_URL1').'/crawle_image/'.$image_name;   
		} else {
			$image = $content->image;
		}

		// To Manage Category
		$categoryID = DB::table('story_categories')->where('story_id', '=', $storyId)->delete();
		foreach ($setData['category_id'] as $catId) {
			$this->storyCategory->create(['category_id' => $catId, 'story_id' => $storyId]);
		}

		$setData['image']        = $image;
		$setData['all_category'] = json_encode($setData['category_id']);
		$setData['category_id']  = count($setData['category_id']);

		$this->content->update($content, $setData);

		return redirect()->route('admin.content.custom_contentstory.index')
			->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('content::custom_contentstories.title.custom_contentstories')]));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  Content $content
	 * @return Response
	 */
	public function destroy(Content $content)
	{
		$categoryID = DB::table('story_categories')->where('story_id', '=', $content->id)->delete();

		$this->content->destroy($content);

		return redirect()->route('admin.content.custom_contentstory.index')
			->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('content::custom_contentstories.title.custom_contentstories')]));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  Request $request
	 * @return Response
	 */
	public function setPosition(Request $request)
	{
		$data = DB::table('storypositions')->get();
		if(sizeof($data)) {
			DB::table('storypositions')->update(['positions' => $request->position]);

			return $request;
		}

		DB::table('storypositions')->insert(['positions' => $request->position]);

		return $request;
	}

}
