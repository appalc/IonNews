<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\Company;
use Modules\Content\Repositories\CompanyRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Content\Http\Requests\CreateCompanyRequest;
use Modules\Content\Http\Requests\UpdateCompanyRequest;
class CompanyController extends AdminBaseController
{
	/**
	* @var CompanyRepository
	*/
	private $company;
	
	public function __construct(CompanyRepository $company)
	{
		parent::__construct();

		$this->company = $company;
	}

	/**
	* Display a listing of the resource.
	*
	* @return Response
	*/
	public function index()
	{
		$companies = $this->company->all();

		return view('content::admin.companies.index', compact('companies'));
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return Response
	*/
	public function create()
	{
		return view('content::admin.companies.create');
	}

	/**
	* Store a newly created resource in storage.
	*
	* @param  Request $request
	* @return Response
	*/
	public function store(CreateCompanyRequest $request)
	{
		$this->company->create($request->all());

		return redirect()->route('admin.content.company.index')
			->withSuccess('Company created', ['name' => 'Company']);
	}

	/**
	* Show the form for editing the specified resource.
	*
	* @param  Integer $companyId
	* @return Response
	*/
	public function edit($companyId)
	{
		if (!$company = $this->company->find($companyId)) {
			return redirect()->route('admin.content.company.index')->withError('Company not found');
		}

		return view('content::admin.companies.edit', compact('company'));
	}

	/**
	* Update the specified resource in storage.
	*
	* @param  Company $company
	* @param  Request $request
	* @return Response
	*/
	public function update(Company $company, Request $request)
	{
		$this->company->update($company, $request->all());

		return redirect()->route('admin.content.company.index')
			->withSuccess('Company updated', ['name' => 'Company']);
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  Company $company
	* @return Response
	*/
	public function destroy(Company $company)
	{
		$this->company->destroy($company);

		return redirect()->route('admin.content.company.index')
			->withSuccess('Company deleted', ['name' => 'Company']);
	}
}
