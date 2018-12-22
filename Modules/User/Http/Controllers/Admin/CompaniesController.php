<?php

namespace Modules\User\Http\Controllers\Admin;

use Modules\User\Http\Requests\CompaniesRequest;
use Modules\User\Permissions\PermissionManager;
use Modules\User\Repositories\CompanyRepository;

class CompaniesController extends BaseUserModuleController
{
	/**
	* @var CompanyRepository
	*/
	private $company;
	
	public function __construct(PermissionManager $permissions, CompanyRepository $company)
	{
		parent::__construct();

		$this->permissions = $permissions;
		$this->company     = $company;
	}

	/**
	* Display a listing of the resource.
	*
	* @return Response
	*/
	public function index()
	{
		$companies = $this->company->all();

		return view('user::admin.companies.index', compact('companies'));
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return Response
	*/
	public function create()
	{
		return view('user::admin.companies.create');
	}

	/**
	* Store a newly created resource in storage.
	*
	* @param  CompaniesRequest $request
	* @return Response
	*/
	public function store(CompaniesRequest $request)
	{
		$this->company->create($request);

		return redirect()->route('admin.user.company.index')->withSuccess('Company created');
	}

	/**
	* Show the form for editing the specified resource.
	*
	* @param  int $id
	* @return Response
	*/
	public function edit($id)
	{
		if (!$company = $this->company->find($id)) {
			return redirect()->route('admin.user.company.index')->withError('Company not found');
		}

		return view('user::admin.companies.edit', compact('company'));
	}

	/**
	* Update the specified resource in storage.
	*
	* @param  int              $id
	* @param  CompaniesRequest $request
	* @return Response
	*/
	public function update($id, CompaniesRequest $request)
	{
		$this->company->update($id, $request);

		return redirect()->route('admin.user.company.index')->withSuccess('Company Details Updated');
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int $id
	* @return Response
	*/
	public function destroy($id)
	{
		$this->company->delete($id);

		return redirect()->route('admin.user.company.index')->withSuccess('Company deleted');
	}
}
