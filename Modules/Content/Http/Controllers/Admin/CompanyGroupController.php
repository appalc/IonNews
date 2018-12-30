<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\CompanyGroup;
use Modules\Content\Repositories\CompanyGroupRepository;
use Modules\Content\Repositories\CompanyRepository;
use Modules\Content\Repositories\SkinRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Content\Http\Requests\CreateCompanyGroupRequest;
use Modules\Content\Http\Requests\UpdateCompanyGroupRequest;

class CompanyGroupController extends AdminBaseController
{
	/**
	* @var CompanyGroupRepository
	*/
	private $companygroup;
	/**
	* @var CompanyRepository
	*/
	private $company;

	/**
	* @var SkinRepository
	*/
	private $skin;

	public function __construct(CompanyGroupRepository $companygroup, CompanyRepository $company, SkinRepository $skin)
	{
		parent::__construct();

		$this->companygroup = $companygroup;
		$this->company      = $company;
		$this->skin         = $skin;
	}

	/**
	* Display a listing of the resource.
	*
	* @return Response
	*/
	public function index()
	{
		$companies     = $this->company->all()->mapWithKeys(function ($company) {
			return [$company->id => $company->name];
		})->toArray();
		$skins         = $this->skin->all()->mapWithKeys(function ($skin) {
			return [$skin->id => $skin->name];
		})->toArray();
		$companyGroups = $this->companygroup->all();

		return view('content::admin.companygroups.index', compact('companyGroups', 'companies', 'skins'));
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return Response
	*/
	public function create()
	{
		$companies = $this->company->all();
		$skins     = $this->skin->all();

		return view('content::admin.companygroups.create', compact('companies', 'skins'));
	}

	/**
	* Store a newly created resource in storage.
	*
	* @param  Request $request
	* @return Response
	*/
	public function store(CreateCompanyGroupRequest $request)
	{
		$this->companygroup->create($request->all());

		return redirect()->route('admin.content.companygroup.index')->withSuccess('Company Group created');
	}

	/**
	* Show the form for editing the specified resource.
	*
	* @param  Integer $companyGroupId
	* @return Response
	*/
	public function edit($companyGroupId)
	{
		if (!$companygroup = $this->companygroup->find($companyGroupId)) {
			return redirect()->route('admin.content.companygroup.index')->withError('Company Group not found');
		}

		$companies = $this->company->all()->mapWithKeys(function($comp) { return [$comp->id => $comp->name]; })->toArray();
		$skins     = $this->skin->all()->mapWithKeys(function($sk) { return [$sk->id => $sk->name]; })->toArray();

		return view('content::admin.companygroups.edit', compact('companygroup', 'companies', 'skins'));
	}

	/**
	* Update the specified resource in storage.
	*
	* @param  UpdateCompanyGroupRequest $request
	* @param  integer                   $id
	* @return Response
	*/
	public function update(UpdateCompanyGroupRequest $request, $id)
	{
		if (!companygroup::find($id)->update($request->all())) {
			return redirect()->route('admin.content.companygroup.edit', $id)->withError('Cannot update Company Groups, Please Try Again');
		}

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
