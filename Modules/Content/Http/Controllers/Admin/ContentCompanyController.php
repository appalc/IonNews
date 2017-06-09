<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\ContentCompany;
use Modules\Content\Repositories\ContentCompanyRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class ContentCompanyController extends AdminBaseController
{
    /**
     * @var ContentCompanyRepository
     */
    private $contentcompany;

    public function __construct(ContentCompanyRepository $contentcompany)
    {
        parent::__construct();

        $this->contentcompany = $contentcompany;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //$contentcompanies = $this->contentcompany->all();

        return view('content::admin.contentcompanies.index', compact(''));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('content::admin.contentcompanies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->contentcompany->create($request->all());

        return redirect()->route('admin.content.contentcompany.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('content::contentcompanies.title.contentcompanies')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  ContentCompany $contentcompany
     * @return Response
     */
    public function edit(ContentCompany $contentcompany)
    {
        return view('content::admin.contentcompanies.edit', compact('contentcompany'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ContentCompany $contentcompany
     * @param  Request $request
     * @return Response
     */
    public function update(ContentCompany $contentcompany, Request $request)
    {
        $this->contentcompany->update($contentcompany, $request->all());

        return redirect()->route('admin.content.contentcompany.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('content::contentcompanies.title.contentcompanies')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ContentCompany $contentcompany
     * @return Response
     */
    public function destroy(ContentCompany $contentcompany)
    {
        $this->contentcompany->destroy($contentcompany);

        return redirect()->route('admin.content.contentcompany.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('content::contentcompanies.title.contentcompanies')]));
    }
}
