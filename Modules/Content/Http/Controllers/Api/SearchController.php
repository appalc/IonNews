<?php
namespace Modules\Content\Http\Controllers\Api;

use GuzzleHttp\Client;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
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
use Modules\User\Services\UserResetter;
use Modules\Content\Repositories\ContentLikeStoryRepository;
use Modules\Content\Entities\ContentLikeStory;
use Validator;
use Log;
use DB;

class SearchController extends BasePublicController
{
    protected $guard;

	public function __construct(Response $response, Guard $guard, UserRepository $user, CategoryRepository $category, RoleRepository $role, ContentRepository $content, ContentLikeStoryRepository $likestory)
	{
		parent::__construct();

		$this->response  = $response;
		$this->guard     = $guard;
		$this->user      = $user;
		$this->category  = $category;
		$this->role      = $role;
		$this->content   = $content;
		$this->likestory = $likestory;
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
			'category' => DB::table('content__categories')->select('id', 'name', 'slug_name')->where('status', '=', 1)->get(),
			'tag'      => collect($this->content->extractTags())->pluck('tags'),
		];
	}

	/**
	 * return list of tags
	 *
	 * @param  Request $request [description]
	 * @param  Client  $http    [description]
	 *
	 * @return array Array of tags and category
	 */
	public function storyByTag(Request $request, Client $http)
	{
		$validator = Validator::make($request->all(), ['tags' => 'required']);

		if ($validator->fails()) {
			$errors = $validator->errors();
			foreach ($errors->all() as $message) {
				$meserror = $message;
			}

			$this->response->setContent(['message' => $message]);

			return $this->response->setStatusCode(400, $meserror);
		}

		$userData  = $this->user->find($request->user_id);
		$dataset   = $this->content->searchByTag($request->tags, $userData->role_id);
		$positions = DB::table('storypositions')->select('positions')->get();
		$positions = json_decode($positions, true);
		$position  = $positions[0]['positions'];

		$limit  = (12 / $position);
		$offset = 0;
		if ($request->has('page')) {
			$pageno = $request->page;
			$offset = $limit * ($pageno - 1);
		}

		$custom_story = DB::table('content__custom_contentstories as cus')
					->where('cus.tags', 'LIKE', $request->tags)
					->offset($offset)
					->limit($limit)
					->get();

		$custom_story = json_decode($custom_story, true);
		$custom       = [];
		$i            = 0;
		$k            = 0;
		$mul          = 2;
		$positions    = $position;

		foreach ($dataset as $key => $value) {
			$categoryName = DB::table('content__categories')->where('id', '=', $value->category_id)->first()->name;
			unset($value->category_id);
			$value->like_count = $this->likestory->checkLikeorNot($value, $request->user_id);
			$value->islike     = ($value->like_count) ? 1 : 0;

			$custom[$categoryName][$i] = $value;
			if ($i == ($positions - 1) && count($custom_story)) {
				$k                           = ($k >= count($custom_story)) ? 0 : $k;
				$custom[$categoryName][$i++] = $custom_story[$k++];
				$custom[$categoryName][$i]   = $value;
				$positions                   = ($position * $mul);

				$mul += 1;
			}

			unset($dataset[$key]);

			$i++;
		}

		$dataset['total_Count'] = sizeof($custom);
		$dataset['all_data']    = $custom;

		return $dataset;
	}

}
