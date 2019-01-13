<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\Category;
use Modules\Content\Repositories\CategoryRepository;
use Modules\Content\Http\Requests\CreateCategoryRequest;
use Modules\Content\Http\Requests\UpdateCategoryRequest;
use DB;
use Log;


use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class CategoryController extends AdminBaseController
{
    /**
     * @var CategoryRepository
     */
    private $category;

    public function __construct(CategoryRepository $category)
    {
        parent::__construct();

        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $categories = $this->category->all();
         // Log::info($categories);

        return view('content::admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {    $categories = $this->category->all();   
         $categories_size=sizeof(json_decode($categories,true))+1;        

        return view('content::admin.categories.create', compact('categories_size'));
    }

	/**
	* Store a newly created resource in storage.
	*
	* @param  CreateCategoryRequest $request
	* @return Response
	*/
	public function store(CreateCategoryRequest $request)
	{
		$find_all_priority = $this->category->getAllPriority('priority', $request->priority);
		if (sizeof($find_all_priority)) {
			foreach ($find_all_priority as $key => $value) {
				$details = $this->category->updatePriority($value['id'], ($value['priority'] + 1));
			}
		}

		$requestData = $request->all();

		if ($request->hasFile('icon')) {
			$imageName = $_FILES['icon']['name'];

			$request->file('icon')->move(env('IMG_URL') . '/category_icons', $imageName);
			
			$requestData['icon'] = "/category_icons/{$imageName}";
		}

		$this->category->create($requestData);

		return redirect()->route('admin.content.category.index')
			->withSuccess(trans('core::core.messages.resource created', ['name' => trans('content::categories.title.categories')]));
	}

	/**
	* Show the form for editing the specified resource.
	*
	* @param  Category $category
	* @return Response
	*/
	public function edit(Category $category)
	{
		return view('content::admin.categories.edit', compact('category'));
	}

	/**
	* Update the specified resource in storage.
	*
	* @param  Category $category
	* @param  UpdateCategoryRequest $request
	* @return Response
	*/
	public function update(Category $category, UpdateCategoryRequest $request)
	{
		$requestData = $request->all();

		if ($request->hasFile('icon')) {
			$imageName = $_FILES['icon']['name'];

			$request->file('icon')->move(env('IMG_URL') . '/category_icons', $imageName);
			
			$requestData['icon'] = "/category_icons/{$imageName}";
		}

		$this->category->update($category, $requestData);

		return redirect()->route('admin.content.category.index')
			->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('content::categories.title.categories')]));
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category $category
     * @return Response
     */
    public function destroy(Category $category)
    {
        $this->category->destroy($category);

        return redirect()->route('admin.content.category.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('content::categories.title.categories')]));
    }

	public function update_priority(Request $request)
	{
		$priorityData = $_POST['url'];
		foreach ($priorityData as $key => $value) {
			DB::table('categories')->where('id', $key)->update(['priority' => $value]);
		}
	}

}
