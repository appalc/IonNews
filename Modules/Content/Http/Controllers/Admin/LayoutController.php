<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\Layout;
use Modules\Content\Http\Requests\CreateLayoutRequest;
use Modules\Content\Http\Requests\UpdateLayoutRequest;
use Modules\Content\Repositories\LayoutRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class LayoutController extends AdminBaseController
{
    /**
     * @var LayoutRepository
     */
    private $layout;

    public function __construct(LayoutRepository $layout)
    {
        parent::__construct();

        $this->layout = $layout;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
		$layouts = $this->layout->all();

        return view('content::admin.layouts.index', compact('layouts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('content::admin.layouts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateLayoutRequest $request
     * @return Response
     */
    public function store(CreateLayoutRequest $request)
    {
        $this->layout->create($request->all());

        return redirect()->route('admin.content.layout.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('content::layouts.title.layouts')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Layout $layout
     * @return Response
     */
    public function edit(Layout $layout)
    {
        return view('content::admin.layouts.edit', compact('layout'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Layout $layout
     * @param  UpdateLayoutRequest $request
     * @return Response
     */
    public function update(Layout $layout, UpdateLayoutRequest $request)
    {
        $this->layout->update($layout, $request->all());

        return redirect()->route('admin.content.layout.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('content::layouts.title.layouts')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Layout $layout
     * @return Response
     */
    public function destroy(Layout $layout)
    {
        $this->layout->destroy($layout);

        return redirect()->route('admin.content.layout.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('content::layouts.title.layouts')]));
    }
}
