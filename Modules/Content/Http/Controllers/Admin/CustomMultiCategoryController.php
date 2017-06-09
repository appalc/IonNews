<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\CustomMultiCategory;
use Modules\Content\Repositories\CustomMultiCategoryRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class CustomMultiCategoryController extends AdminBaseController
{
    /**
     * @var CustomMultiCategoryRepository
     */
    private $custommulticategory;

    public function __construct(CustomMultiCategoryRepository $custommulticategory)
    {
        parent::__construct();

        $this->custommulticategory = $custommulticategory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$custommulticategories = $this->custommulticategory->all();

        return view('content::admin.custommulticategories.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('content::admin.custommulticategories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->custommulticategory->create($request->all());

        return redirect()->route('admin.content.custommulticategory.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('content::custommulticategories.title.custommulticategories')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  CustomMultiCategory $custommulticategory
     * @return Response
     */
    public function edit(CustomMultiCategory $custommulticategory)
    {
        return view('content::admin.custommulticategories.edit', compact('custommulticategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CustomMultiCategory $custommulticategory
     * @param  Request $request
     * @return Response
     */
    public function update(CustomMultiCategory $custommulticategory, Request $request)
    {
        $this->custommulticategory->update($custommulticategory, $request->all());

        return redirect()->route('admin.content.custommulticategory.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('content::custommulticategories.title.custommulticategories')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  CustomMultiCategory $custommulticategory
     * @return Response
     */
    public function destroy(CustomMultiCategory $custommulticategory)
    {
        $this->custommulticategory->destroy($custommulticategory);

        return redirect()->route('admin.content.custommulticategory.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('content::custommulticategories.title.custommulticategories')]));
    }
}
