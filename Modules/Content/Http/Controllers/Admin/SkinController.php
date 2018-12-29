<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\Skin;
use Modules\Content\Http\Requests\CreateSkinRequest;
use Modules\Content\Http\Requests\UpdateSkinRequest;
use Modules\Content\Repositories\SkinRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class SkinController extends AdminBaseController
{
    /**
     * @var SkinRepository
     */
    private $skin;

    public function __construct(SkinRepository $skin)
    {
        parent::__construct();

        $this->skin = $skin;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$skins = $this->skin->all();

        return view('content::admin.skins.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('content::admin.skins.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateSkinRequest $request
     * @return Response
     */
    public function store(CreateSkinRequest $request)
    {
        $this->skin->create($request->all());

        return redirect()->route('admin.content.skin.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('content::skins.title.skins')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Skin $skin
     * @return Response
     */
    public function edit(Skin $skin)
    {
        return view('content::admin.skins.edit', compact('skin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Skin $skin
     * @param  UpdateSkinRequest $request
     * @return Response
     */
    public function update(Skin $skin, UpdateSkinRequest $request)
    {
        $this->skin->update($skin, $request->all());

        return redirect()->route('admin.content.skin.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('content::skins.title.skins')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Skin $skin
     * @return Response
     */
    public function destroy(Skin $skin)
    {
        $this->skin->destroy($skin);

        return redirect()->route('admin.content.skin.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('content::skins.title.skins')]));
    }
}
