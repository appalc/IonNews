<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\UserGroup;
use Modules\Content\Repositories\UserGroupRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;

class UserGroupController extends AdminBaseController
{
	/**
	* @var UserGroupRepository
	*/
	private $usergroup;

	public function __construct(UserGroupRepository $usergroup)
	{
		parent::__construct();

		$this->usergroup = $usergroup;
	}

	/**
	* Display a listing of the resource.
	*
	* @return Response
	*/
	public function index()
	{
		$usergroups = $this->usergroup->all();

		return view('content::admin.usergroups.index', compact('usergroups'));
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return Response
	*/
	public function create()
	{
		return view('content::admin.usergroups.create');
	}

	/**
	* Store a newly created resource in storage.
	*
	* @param  Request $request
	* @return Response
	*/
	public function store(Request $request)
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
	public function edit(UserGroup $usergroup)
	{
		return view('content::admin.usergroups.edit', compact('usergroup'));
	}

	/**
	* Update the specified resource in storage.
	*
	* @param  UserGroup $usergroup
	* @param  Request $request
	* @return Response
	*/
	public function update(UserGroup $usergroup, Request $request)
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
