<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\Skin;
use Modules\Content\Http\Requests\CreateSkinRequest;
use Modules\Content\Http\Requests\UpdateSkinRequest;
use Modules\Content\Repositories\SkinRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\User\Contracts\Authentication;

class SkinController extends AdminBaseController
{
	/**
	 * @var SkinRepository
	 */
	private $skin;

	/**
	* @var Authentication
	*/
	private $auth;

	public function __construct(SkinRepository $skin, Authentication $auth)
	{
		parent::__construct();

		$this->auth = $auth;
		$this->skin = $skin;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$skins = $this->skin->all();

		return view('content::admin.skins.index', compact('skins'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$currentUser = $this->auth->user();

		return view('content::admin.skins.create', compact('currentUser'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  CreateSkinRequest $request
	 * @return Response
	 */
	public function store(CreateSkinRequest $request)
	{
		$this->skin->create($request->all());

		return redirect()->route('admin.content.skin.index')->withSuccess('Skin created', ['name' => 'Skin']);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  Integer $skinId
	 * @return Response
	 */
	public function edit($skinId)
	{
		if (!$skin = $this->skin->find($skinId)) {
			return redirect()->route('admin.content.skin.index')->withError('Skin not found');
		}

		$currentUser = $this->auth->user();

		return view('content::admin.skins.edit', compact('skin', 'currentUser'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  UpdateSkinRequest $request
	 * @param  Integer           $id
	 * @return Response
	 */
	public function update(UpdateSkinRequest $request, $id)
	{
		if (!skin::find($id)->update($request->all())) {
			return redirect()->route('admin.content.skin.edit', $id)->withError('Cannot update Skin Info, Please Try Again');
		}

		return redirect()->route('admin.content.skin.index')->withSuccess('Skin updated', ['name' => 'Skin']);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  Skin $skin
	 * @return Response
	 */
	public function destroy(Skin $skin)
	{
		$this->skin->destroy($skin);
		
		return redirect()->route('admin.content.skin.index')->withSuccess('Skin Deleted', ['name' => 'Skin']);
	}
}
