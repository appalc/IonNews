<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\ContentUser;
use Modules\Content\Repositories\ContentUserRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class ContentUserController extends AdminBaseController
{
    /**
     * @var ContentUserRepository
     */
    private $contentuser;

    public function __construct(ContentUserRepository $contentuser)
    {
        parent::__construct();

        $this->contentuser = $contentuser;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$contentusers = $this->contentuser->all();

        return view('content::admin.contentusers.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('content::admin.contentusers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->contentuser->create($request->all());

        return redirect()->route('admin.content.contentuser.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('content::contentusers.title.contentusers')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  ContentUser $contentuser
     * @return Response
     */
    public function edit(ContentUser $contentuser)
    {
        return view('content::admin.contentusers.edit', compact('contentuser'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ContentUser $contentuser
     * @param  Request $request
     * @return Response
     */
    public function update(ContentUser $contentuser, Request $request)
    {
        $this->contentuser->update($contentuser, $request->all());

        return redirect()->route('admin.content.contentuser.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('content::contentusers.title.contentusers')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ContentUser $contentuser
     * @return Response
     */
    public function destroy(ContentUser $contentuser)
    {
        $this->contentuser->destroy($contentuser);

        return redirect()->route('admin.content.contentuser.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('content::contentusers.title.contentusers')]));
    }
}
