<?php

namespace Modules\User\Repositories;

/**
 * Interface CompanyRepository
 * @package Modules\User\Repositories
 */
interface CompanyRepository
{
	/**
	* Return all the company
	* @return mixed
	*/
	public function all();
	
	/**
	* Create a company resource
	* @return mixed
	*/
	public function create($data);
	
	/**
	* Find a company by its id
	* @param $id
	* @return mixed
	*/
	public function find($id);
	
	/**
	* Update a company
	* @param $id
	* @param $data
	* @return mixed
	*/
	public function update($id, $data);
	
	/**
	* Delete a company
	* @param $id
	* @return mixed
	*/
	public function delete($id);
	
	/**
	* Find a company by its name
	* @param  string $name
	* @return mixed
	*/
	public function findByName($name);
}
