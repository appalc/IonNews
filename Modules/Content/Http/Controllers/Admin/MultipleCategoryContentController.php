<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\MultipleCategoryContent;
use Modules\Content\Repositories\MultipleCategoryContentRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class MultipleCategoryContentController extends AdminBaseController
{
    /**
     * @var MultipleCategoryContentRepository
     */
    private $multiplecategorycontent;

    public function __construct(MultipleCategoryContentRepository $multiplecategorycontent)
    {
        parent::__construct();

        $this->multiplecategorycontent = $multiplecategorycontent;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$multiplecategorycontents = $this->multiplecategorycontent->all();

        return view('content::admin.multiplecategorycontents.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('content::admin.multiplecategorycontents.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->multiplecategorycontent->create($request->all());

        return redirect()->route('admin.content.multiplecategorycontent.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('content::multiplecategorycontents.title.multiplecategorycontents')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  MultipleCategoryContent $multiplecategorycontent
     * @return Response
     */
    public function edit(MultipleCategoryContent $multiplecategorycontent)
    {
        return view('content::admin.multiplecategorycontents.edit', compact('multiplecategorycontent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  MultipleCategoryContent $multiplecategorycontent
     * @param  Request $request
     * @return Response
     */
    public function update(MultipleCategoryContent $multiplecategorycontent, Request $request)
    {
        $this->multiplecategorycontent->update($multiplecategorycontent, $request->all());

        return redirect()->route('admin.content.multiplecategorycontent.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('content::multiplecategorycontents.title.multiplecategorycontents')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  MultipleCategoryContent $multiplecategorycontent
     * @return Response
     */
    public function destroy(MultipleCategoryContent $multiplecategorycontent)
    {
        $this->multiplecategorycontent->destroy($multiplecategorycontent);

        return redirect()->route('admin.content.multiplecategorycontent.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('content::multiplecategorycontents.title.multiplecategorycontents')]));
    }
}
