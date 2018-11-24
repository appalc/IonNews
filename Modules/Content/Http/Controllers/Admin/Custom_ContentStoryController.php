<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\Custom_ContentStory;
use Modules\Content\Repositories\Custom_ContentStoryRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Content\Repositories\CategoryRepository;
use Modules\User\Repositories\RoleRepository;
use Modules\Content\Repositories\CustomMultiCategoryRepository;


use DB;
use Log;

class Custom_ContentStoryController extends AdminBaseController
{
	/**
	 * @var Custom_ContentStoryRepository
	 */
	private $custom_contentstory;

	public function __construct(Custom_ContentStoryRepository $custom_contentstory,CategoryRepository $category, RoleRepository $role, CustomMultiCategoryRepository $multiCustomCategory)
	{
		parent::__construct();

		$this->custom_contentstory = $custom_contentstory;
		$this->category            = $category;
		$this->role                = $role;
		$this->multiCustomCategory = $multiCustomCategory;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$custom_contentstories = $this->custom_contentstory->all();
		$position              = DB::table('storypositions')->get();
		$position              = json_decode($position,true);

		if (sizeof($position))
			$position = $position[0]['positions'];
		else
			$position = 2;

		return view('content::admin.custom_contentstories.index', compact('custom_contentstories', 'position'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$categories = $this->category->getByAttributes(['status' => 1]);
		Log::info($categories);

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
		$setData = $request->all();
		$image   = '';

		$multiContCategoryData = '';
		if (array_key_exists('category_id', $setData)) {
			$multiContCategoryData   = $setData['category_id'];
			$setData['all_category'] = json_encode($multiContCategoryData);
		}

		if ($request->hasFile('img')) {
			$image_name = $_FILES['img']['name'];
			$request->file('img')->move(env('IMG_URL') . '/crawle_image', $image_name);

			$image = env('IMG_URL1') . '/crawle_image/' . $image_name;
		}

		$setData['image'] = $image;
		$story            = $this->custom_contentstory->create($setData);
		$story_id         = $story->id;

		if(!$multiContCategoryData) {
			$categories = $this->category->getByAttributes(['status' => 1]);
			$categories = json_decode($categories,true);
			foreach ($categories as $key => $value) {
				$abc['category_id']       = $value['id'];
				$abc['custom_content_id'] = $story_id;
				$this->multiCustomCategory->create($abc);
			}
		} else {
			foreach ($multiContCategoryData as $value) {
				Log::info($value);
				$abc['category_id']       = $value;
				$abc['custom_content_id'] = $story_id;
				$this->multiCustomCategory->create($abc);
			}
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
	public function edit(Custom_ContentStory $custom_contentstory)
	{
		$categories = $this->category->getByAttributes(['status' => 1]);
		return view('content::admin.custom_contentstories.edit', compact('custom_contentstory','categories'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Custom_ContentStory $custom_contentstory
	 * @param  Request $request
	 * @return Response
	 */
	public function update(Custom_ContentStory $custom_contentstory, Request $request)
	{   
		$setData = $request->all();
		$image   = '';
		if ($request->hasFile('img')) {
			$image_name = $_FILES['img']['name'];
			$request->file('img')->move(env('IMG_URL').'/crawle_image',$image_name);
			$image      = env('IMG_URL1').'/crawle_image/'.$image_name;   
		} else {
			$image = $custom_contentstory->image;
		}

		$setData['image'] = $image;

		$categoryID = DB::table('content__custommulticategories')->where('custom_content_id', '=', $custom_contentstory->id)->delete();

		$setData['all_category'] = json_encode($setData['category_id']);
		foreach ($setData['all_category'] as $catId) {
			$this->multiCustomCategory->create(['category_id' => $catId, 'custom_content_id' => $custom_contentstory->id]);
		}

		$this->custom_contentstory->update($custom_contentstory, $setData);

		return redirect()->route('admin.content.custom_contentstory.index')
			->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('content::custom_contentstories.title.custom_contentstories')]));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  Custom_ContentStory $custom_contentstory
	 * @return Response
	 */
	public function destroy(Custom_ContentStory $custom_contentstory)
	{
		$this->custom_contentstory->destroy($custom_contentstory);

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
		$positions = $request->position;
		$data      = DB::table('storypositions')->get();
		if(sizeof($data)) {
			DB::table('storypositions')->update(['positions' => $positions]);
		} else {
			  DB::table('storypositions')->insert(['positions' => $positions]);
		}

		return $request;
	}

}
