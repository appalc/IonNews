<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\Preference;
use Modules\Content\Http\Requests\CreatePreferenceRequest;
use Modules\Content\Http\Requests\UpdatePreferenceRequest;
use Modules\Content\Repositories\PreferenceRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class PreferenceController extends AdminBaseController
{
    /**
     * @var PreferenceRepository
     */
    private $preference;

    public function __construct(PreferenceRepository $preference)
    {
        parent::__construct();

        $this->preference = $preference;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$preferences = $this->preference->all();

        return view('content::admin.preferences.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('content::admin.preferences.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreatepreferenceRequest $request
     * @return Response
     */
    public function store(CreatePreferenceRequest $request)
    {
        $this->preference->create($request->all());

        return redirect()->route('admin.content.preference.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('content::preferences.title.preferences')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  preference $preference
     * @return Response
     */
    public function edit(Preference $preference)
    {
        return view('content::admin.preferences.edit', compact('preference'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  preference $preference
     * @param  UpdatepreferenceRequest $request
     * @return Response
     */
    public function update(preference $preference, UpdatePreferenceRequest $request)
    {
        $this->preference->update($preference, $request->all());

        return redirect()->route('admin.content.preference.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('content::preferences.title.preferences')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  preference $preference
     * @return Response
     */
    public function destroy(Preference $preference)
    {
        $this->preference->destroy($preference);

        return redirect()->route('admin.content.preference.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('content::preferences.title.preferences')]));
    }
}
