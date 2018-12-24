<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\CompanyGroup;
use Modules\Content\Repositories\CompanyGroupRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class CompanyGroupController extends AdminBaseController
{
	/**
	* @var CompanyGroupRepository
	*/
	private $companygroup;

	public function __construct(CompanyGroupRepository $companyGroup)
	{
		parent::__construct();

		$this->companygroup = $companyGroup;
	}

	/**
	* Display a listing of the resource.
	*
	* @return Response
	*/
	public function index()
	{
		$companyGroups = $this->companygroup->all();

		return view('content::admin.companygroups.index', compact('companyGroups'));
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return Response
	*/
	public function create()
	{
		return view('content::admin.companygroups.create');
	}

	/**
	* Store a newly created resource in storage.
	*
	* @param  Request $request
	* @return Response
	*/
	public function store(Request $request)
	{
		$this->companygroup->create($request->all());

		return redirect()->route('admin.content.companygroup.index')->withSuccess('Company Group created');
	}

	/**
	* Show the form for editing the specified resource.
	*
	* @param  CompanyGroup $companygroup
	* @return Response
	*/
	public function edit(CompanyGroup $companygroup)
	{
		return view('content::admin.companygroups.edit', compact('companygroup'));
	}

	/**
	* Update the specified resource in storage.
	*
	* @param  CompanyGroup $companygroups
	* @param  Request      $request
	* @return Response
	*/
	public function update(CompanyGroup $companygroup, Request $request)
	{
		$this->companygroup->update($companygroup, $request->all());

		return redirect()->route('admin.content.companygroup.index')->withSuccess('Company Groups updated');
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  CompanyGroup $companygroup
	* @return Response
	*/
	public function destroy(CompanyGroup $companygroup)
	{
		$this->companygroup->destroy($companygroup);

		return redirect()->route('admin.content.companygroup.index')->withSuccess('Company Groups deleted', ['name' => 'Company Groups']);
	}
}
