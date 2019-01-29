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
		$userId = !empty($_GET['user_id']) ? $_GET['user_id'] : $request->user_id;

		return [
			'category' => $this->category->getCategoriesByUser($userId)->map(function ($cat) {
				return ['id' => $cat->id, 'name' => $cat->name, 'slug_name' => $cat->slug_name];
			}),
			'tag'      => collect($this->content->extractTags())->map(function($tag) {
				$parsedTag = str_replace('#', ',', $tag->tags);
				$parsedTag = str_replace(' ,', ',', $parsedTag);
				$parsedTag = str_replace(', ', ',', $parsedTag);

				return explode(',', trim($parsedTag));
			})->flatten(1)->filter()->values(),
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

		$userData     = $this->user->find($request->user_id);
		$userCategory = $this->category->getCategoriesByGroup($userData->user_group_id);
		$dataset      = $this->content->searchByTag($request->tags, $userCategory->pluck('id'));

		$positions = DB::table('storypositions')->select('positions')->get();
		$positions = json_decode($positions, true);
		$position  = $positions[0]['positions'];

		$limit  = (12 / $position);
		$offset = 0;
		if ($request->has('page')) {
			$pageno = $request->page;
			$offset = $limit * ($pageno - 1);
		}

		$custom_story = DB::table('stories as cus')
					->where('cus.tags', 'LIKE', '%' . $request->tags . '%')
					->where('cus.type', '=', 'ads')
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
			$allCategory     = json_decode($value->all_category, true);
			$primaryCategory = DB::table('categories')->where('id', '=', $allCategory[0])->first();
			$categoryName    = $primaryCategory->name;

			$value->category_id = $primaryCategory->id;
			$value->priority    = $primaryCategory->priority;
			$value->like_count  = $this->likestory->checkLikeorNot($value, $request->user_id);
			$value->islike      = ($value->like_count) ? 1 : 0;

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

			$custom[$categoryName] = array_values($custom[$categoryName]);
		}

		$dataset['total_Count'] = sizeof($custom);
		$dataset['all_data']    = $custom;

		return $dataset;
	}

}
