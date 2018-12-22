<?php

namespace Modules\User\Repositories\Sentinel;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Modules\User\Repositories\CompanyRepository;

class SentinelCompanyRepository implements CompanyRepository
{
	/**
	* @var \Cartalyst\Sentinel\Companies\EloquentRole
	*/
	protected $company;

	public function __construct()
	{
		$this->company = Sentinel::getCompanyRepository()->createModel();
	}

	/**
	* Return all the companies
	* @return mixed
	*/
	public function all()
	{
		return $this->company->all();
	}

	/**
	* Create a company resource
	* @return mixed
	*/
	public function create($data)
	{
		return $this->company->create($data);
	}

	/**
	* Find a company by its id
	* @param $id
	* @return mixed
	*/
	public function find($id)
	{
		return $this->company->find($id);
	}

	/**
	* Update a company
	* @param $id
	* @param $data
	* @return mixed
	*/
	public function update($id, $data)
	{
		$company = $this->company->find($id);

		$company->fill($data);

		$company->save();

		return $company;
	}

	/**
	* Delete a company
	* @param $id
	* @return mixed
	*/
	public function delete($id)
	{
		$company = $this->company->find($id);

		return $company->delete();
	}

	/**
	* Find a company by its name
	* @param  string $name
	* @return mixed
	*/
	public function findByName($name)
	{
		return Sentinel::findRoleByName($name);
	}
}
