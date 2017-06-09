<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\ContentLikeStory;
use Modules\Content\Repositories\ContentLikeStoryRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class ContentLikeStoryController extends AdminBaseController
{
    /**
     * @var ContentLikeStoryRepository
     */
    private $contentlikestory;

    public function __construct(ContentLikeStoryRepository $contentlikestory)
    {
        parent::__construct();

        $this->contentlikestory = $contentlikestory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$contentlikestories = $this->contentlikestory->all();

        return view('content::admin.contentlikestories.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('content::admin.contentlikestories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->contentlikestory->create($request->all());

        return redirect()->route('admin.content.contentlikestory.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('content::contentlikestories.title.contentlikestories')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  ContentLikeStory $contentlikestory
     * @return Response
     */
    public function edit(ContentLikeStory $contentlikestory)
    {
        return view('content::admin.contentlikestories.edit', compact('contentlikestory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ContentLikeStory $contentlikestory
     * @param  Request $request
     * @return Response
     */
    public function update(ContentLikeStory $contentlikestory, Request $request)
    {
        $this->contentlikestory->update($contentlikestory, $request->all());

        return redirect()->route('admin.content.contentlikestory.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('content::contentlikestories.title.contentlikestories')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ContentLikeStory $contentlikestory
     * @return Response
     */
    public function destroy(ContentLikeStory $contentlikestory)
    {
        $this->contentlikestory->destroy($contentlikestory);

        return redirect()->route('admin.content.contentlikestory.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('content::contentlikestories.title.contentlikestories')]));
    }
}
