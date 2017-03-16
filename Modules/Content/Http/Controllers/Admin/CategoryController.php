<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\Category;
use Modules\Content\Repositories\CategoryRepository;
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

        return view('content::admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('content::admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->category->create($request->all());

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
     * @param  Request $request
     * @return Response
     */
    public function update(Category $category, Request $request)
    {
        $this->category->update($category, $request->all());

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
}