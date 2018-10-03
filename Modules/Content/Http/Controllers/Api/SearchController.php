<?php
namespace Modules\Content\Http\Controllers\Api;

use GuzzleHttp\Client;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Authentication\Events\Confirmnotify;
use Modules\Content\Repositories\CategoryRepository;
use Modules\Content\Repositories\ContentRepository;
use Modules\Core\Http\Controllers\BasePublicController;
use Modules\Services\Repositories\UsertypeRepository;
use Modules\User\Events\UserHasBegunResetProcess;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Repositories\RoleRepository;
use Modules\User\Repositories\UserRepository;
use Modules\Tag\Repositories\TagRepository;
use Modules\User\Services\UserResetter;
use Validator;
use Log;

class SearchController extends BasePublicController
{
    protected $guard;

	public function __construct(Response $response, Guard $guard, UserRepository $user, CategoryRepository $category, RoleRepository $role, TagRepository $tag)
	{
		parent::__construct();

		$this->response = $response;
		$this->guard    = $guard;
		$this->user     = $user;
		$this->category = $category;
		$this->role     = $role;
		$this->tag      = $tag;
	}

	/**
	 * return list of category and tags
	 *
	 * @param  Request $request [description]
	 * @param  Client  $http    [description]
	 *
	 * @return array Array of tags and category
	 */
	public function categoryAndTaglist(Request $request, Client $http)
	{
		return [
			'category' => $this->category->getByAttributes(['status' => 1]),
			'tag'      => $this->tag->all(),
		];
	}

	/**
	 * return list of category and tags
	 *
	 * @param  Request $request [description]
	 * @param  Client  $http    [description]
	 *
	 * @return array Array of tags and category
	 */
	public function searchNews(Request $request, Client $http)
	{
		return [
			'category' => $this->category->getByAttributes(['status' => 1]),
			'tag'      => $this->tag->all(),
		];
	}

}