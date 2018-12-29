<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\UserGroup;
use Modules\Content\Repositories\UserGroupRepository;
use Modules\Content\Repositories\CompanyGroupRepository;
use Modules\Content\Repositories\CategoryRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Content\Http\Requests\CreateUserGroupRequest;
use Modules\Content\Http\Requests\UpdateUserGroupRequest;

class UserGroupController extends AdminBaseController
{
	/**
	* @var UserGroupRepository
	*/
	private $usergroup;

	/**
	* @var CategoryRepository
	*/
	private $cateogry;

	/**
	* @var CompanyGroupRepository
	*/
	private $companyGroup;

	public function __construct(UserGroupRepository $usergroup, CategoryRepository $cateogry, CompanyGroupRepository $companyGroup)
	{
		parent::__construct();

		$this->usergroup    = $usergroup;
		$this->cateogry     = $cateogry;
		$this->companyGroup = $companyGroup;
	}

	/**
	* Display a listing of the resource.
	*
	* @return Response
	*/
	public function index()
	{
		$categories    = $this->cateogry->all()->mapWithKeys(function ($category) { return [$category->id => $category->name]; });
		$companyGroups = $this->companyGroup->all()->mapWithKeys(function ($group) { return [$group->id => $group->name]; });
		$usergroups    = $this->usergroup->all();

		return view('content::admin.usergroups.index', compact('usergroups', 'categories', 'companyGroups'));
	}

	/**
	* Show the form for creating a new resource.
	*
	* @param  CategoryRepository      $cateogry
	* @param  CompanyGroupRepository  $companyGroup
	* @return Response
	*/
	public function create(CategoryRepository $cateogry, CompanyGroupRepository $companyGroup)
	{
		return view('content::admin.usergroups.create')->with([
			'categories'    => $cateogry->all(),
			'companygroups' => $companyGroup->all(),
		]);
	}

	/**
	* Store a newly created resource in storage.
	*
	* @param  Request $request
	* @return Response
	*/
	public function store(CreateUserGroupRequest $request)
	{
		$this->usergroup->create($request->all());

		return redirect()->route('admin.content.usergroup.index')
		->withSuccess(trans('core::core.messages.resource created', ['name' => trans('content::usergroups.title.usergroups')]));
	}

	/**
	* Show the form for editing the specified resource.
	*
	* @param  UserGroup $usergroup
	* @return Response
	*/
	public function edit($userGroupId)
	{
		return view('content::admin.usergroups.edit')->with([
			'categories'    => $this->cateogry->all(),
			'companygroups' => $this->companyGroup->all(),
			'usergroup'     => $this->usergroup->find($userGroupId),
		]);
	}

	/**
	* Update the specified resource in storage.
	*
	* @param  UserGroup              $usergroup
	* @param  UpdateUserGroupRequest $request
	* @return Response
	*/
	public function update(UserGroup $usergroup, UpdateUserGroupRequest $request)
	{
		$this->usergroup->update($usergroup, $request->all());

		return redirect()->route('admin.content.usergroup.index')
		->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('content::usergroups.title.usergroups')]));
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  UserGroup $usergroup
	* @return Response
	*/
	public function destroy(UserGroup $usergroup)
	{
		$this->usergroup->destroy($usergroup);

		return redirect()->route('admin.content.usergroup.index')
		->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('content::usergroups.title.usergroups')]));
	}
}
