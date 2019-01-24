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
use Modules\Content\Entities\ContentLikeStory;
use Modules\Content\Repositories\CategoryRepository;
use Modules\Content\Repositories\ContentLikeStoryRepository;
use Modules\Content\Repositories\ContentRepository;
use Modules\Content\Repositories\MultipleCategoryContentRepository;
use Modules\Core\Http\Controllers\BasePublicController;
use Modules\Services\Repositories\UsertypeRepository;
use Modules\User\Events\UserHasBegunResetProcess;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Repositories\RoleRepository;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\UserResetter;
use DB;
use Log;
use Validator;

class StoryController extends BasePublicController
{
	protected $guard;

	public function __construct(
		Response $response,
		Guard $guard,
		UserRepository $user,
		ContentRepository $content,
		CategoryRepository $category,
		ContentLikeStoryRepository $likestory,
		MultipleCategoryContentRepository $multiContCategory
	) {
		parent::__construct();

		$this->response          = $response;
		$this->guard             = $guard;
		$this->user              = $user;
		$this->content           = $content;
		$this->category          = $category;
		$this->likestory         = $likestory;
		$this->multiContCategory = $multiContCategory;

		//$this->middleware('auth:api');
		// $this->middleware('oauth');
	}

	public function story(Request $request, Client $http)
	{
		$validator = Validator::make($request->all(), ['category_id' => 'required']);

		if ($validator->fails()) {
			$errors = $validator->errors();
			foreach ($errors->all() as $message) {
				$meserror = $message;
			}

			$this->response->setContent(array('message' => $message));

			return $this->response->setStatusCode(400, $meserror);
		}

		$user_id  = $request->user_id;
		$stories  = $this->content->getStoriesByCategory($request->category_id);
		$position = DB::table('storypositions')->pluck('positions')[0];
		$limit    = 12/$position;

		if ($request->has('page')) {
			$pageno = $request->page;
			$offset = $limit * ($pageno - 1);
		} else {
			$offset = 0;
		}

		$custom_story = DB::table('stories as cus')
					->join('story_categories as cuc', 'cuc.story_id', '=', 'cus.id')
					->where('cuc.category_id', '=', $request->category_id)
					->offset($offset)
					->limit($limit)
					->get();

		if (!count($custom_story)) {
			$custom_story = DB::table('stories as cus')
					->join('story_categories as cuc', 'cuc.story_id', '=', 'cus.id')
					->where('cuc.category_id', '=', $request->category_id)
					->limit($limit)
					->get();
		}

		$custom_story = json_decode($custom_story, true);
		$custom       = [];
		$i            = 0;
		$k            = 0;
		$mul          = 2;
		$positions    = $position;

		foreach ($stories as $key => $value) {
			unset($value->category_id);
			$value->like_count = $this->likestory->checkLikeorNot($value, $user_id);

			$value->islike = ($value->like_count) ? 1 : 0;

			$custom[$i] = $value;
			if ($i == $positions-1 && count($custom_story)) {
				if ($k >= count($custom_story))
					$k = 0;

				$custom[$i++] = $custom_story[$k];
				$k            = ($k + 1);
				$custom[$i]   = $value;
				$positions    = ($position * $mul);

				$mul += 1;
			}

			unset($stories[$key]);

			$i++;
		}

		$stories['total_Count'] = sizeof($custom);
		$stories['all_data']    = $custom;

		return $stories;
	}

	public function homepage(Request $request, Client $http)
	{
		$validator = Validator::make($request->all(), ['user_id' => 'required']);

		if ($validator->fails()) {
			$errors = $validator->errors();
			foreach ($errors->all() as $message) {
				$meserror = $message;
			}

			$this->response->setContent(array('message' => $message));

			return $this->response->setStatusCode(400, $meserror);
		}

		$user_id      = !empty($_GET['user_id']) ? $_GET['user_id'] : $request->user_id;
		$categorylist = $this->category->getCategoriesByUser($user_id);
		$dataresponse = [];
		$current_date = date('Y-m-d');

		foreach ($categorylist as $category) {
			$setexist = $this->content->getStoryByCategory($category->id);
			if (!empty($setexist) && (count($setexist) != 0)) {
				foreach ($setexist as $key => $value) {
					$value->priority = $category->priority;
				}

				$dataresponse[$category->name]['stories'] = $setexist;
			}

			$dataresponse[$category->name]['icon'] = $category->icon;
		}

		// To sort it by decending order of created date
		$dataresponse = collect($dataresponse)->->filter(function ($stories) {
			return !empty($stories['stories']);
		})->mapWithKeys(function ($stories, $catName) {
			return [
				$catName => [
					'stories' => collect($stories['stories'])->sortByDesc('created_at')->values(),
					'icon'    => !empty($stories['icon']) ? env('IMG_URL1') . $stories['icon'] : '',
				],
			];
		})->sortByDesc(function ($story, $category) {
			return strtotime($story['stories'][0]->created_at);
		});

		if(sizeof($dataresponse) == 0)
			$dataresponse["status"] = "No Story";

		return response($dataresponse);
	}

	public function story_like(Request $request)
	{ 
		$validator = Validator::make($request->all(), ['content_id' => 'required']);

		if ($validator->fails()) {
			$errors   = $validator->errors();
			foreach ($errors->all() as $message) {
				$meserror = $message;
			}

			$this->response->setContent(['message'=> $message]);

			return $this->response->setStatusCode(400, $meserror);
		} else {
			$user_id    = $request->user_id;
			$content_id = $request->content_id;
			$data       = DB::table('content__contentlikestories')
				->where('content_id', '=', $content_id)
				->where('user_id', '=', $user_id)
				->get();

			if (sizeof($data) > 0) {
				$data = DB::table('content__contentlikestories')
					->where('content_id', '=', $content_id)
					->where('user_id', '=', $user_id)
					->delete();
			} else {
				$abc['user_id']    = $user_id;
				$abc['content_id'] = $content_id;
				$data              = $this->likestory->create($abc);
			}

			$response['status'] = "successful";

			return response($response);
		}
	}

	public function getAllLikeStory(Request $request)
	{
		$current_date = date('Y-m-d');      
		$dataset      = DB::table('stories as cc')
			->join('content__contentlikestories as ccl', 'cc.id', '=', 'ccl.content_id')
			->select('cc.*' )
			->where('cc.expiry_date', '>=', $current_date)
			->where('ccl.user_id', '=', $request->user_id)
			->paginate(12);

			foreach ($dataset as $key => $value) {
				$value->islike = 1;
			}

		return response($dataset);
	}

	public function move_to_archive(Request $request)
	{
		$current_date = date('Y-m-d');
		$exipre_story = DB::table('stories')
							->where('expiry_date', '<', $current_date)
							->orderBy('id', 'desc')
							->get();

		$categories  = $this->category->getByAttributes(['status' => 1, 'slug_name' => 'archive']);
		$categories  = json_decode($categories);
		$category_id = $categories[0]->id;
		$cat[]       = $category_id;

		foreach ($exipre_story as $key => $value) {
			DB::table('stories')
				->where('id', $value->id)
				->update(['category_id' => $category_id, 'all_category' => json_encode($cat)]);

			$categoryID = DB::table('content__multiplecategorycontents')->where('content_id', '=', $value->id)->delete();

			$abc['category_id'] = $category_id;
			$abc['content_id']  = $value->id;
			$this->multiContCategory->create($abc);
		}

		return $exipre_story;
	}

	public function updateDatabase(Request $request)
	{
		$current_date = date('Y-m-d');
		$exipre_story = DB::table('stories')
							->where('expiry_date', '<', $current_date)
							->orderBy('id', 'desc')
							->get();

		$categories = $this->category->getByAttributes(['status' => 1, 'slug_name' => 'archive']);
		print_r($categories); exit;

		$categories  = json_decode($categories);
		$category_id = $categories[0]->id;
		$cat[]       = $category_id;
		foreach ($exipre_story as $key => $value) {
			DB::table('stories')
				->where('id', $value->id)
				->update(['category_id' => $category_id, 'all_category' => json_encode($cat)]);

			$categoryID = DB::table('content__multiplecategorycontents')->where('content_id', '=', $value->id)->delete();

			$abc['category_id'] = $category_id;
			$abc['content_id']  = $value->id;
			$this->multiContCategory->create($abc);
		}

		return $exipre_story;
		exit;

		//	$update = DB::table('users')->get();
		//	$update = json_decode($update, true);
		//	foreach ($update as $key => $value) {
		//		if ($value['role']) {
		//			DB::table("users")->where('role', '=', 'user')->update(['role' => 'user', 'role_id' => 2]);
		//		}
		//	}

		//	DB::table("users")->where('id', '=', 1)->update(['role' => 'admin', 'role_id' => 1]);
		//	return $update;

		$categorylist = $this->category->getByAttributes(['status' => 1], 'priority');
		$AllContent   = $this->content->all();
		$now          = date("Y-m-d H:i:s");
		$allRoles     = DB::table('roles')->get();

		foreach ($allRoles as $key => $value) {
			if ($value->id != 1)
				$roles[] = $value->id;
		}

		foreach ($AllContent as $content) {
			foreach ($roles as $role) {
				$data1 = DB::table('content__usergroups')
							->where('role_id', '=', $role)
							->where('content_id', '=', $content->id)
							->get();

				if (sizeof($data1) == 0) {
					DB::table('content__usergroups')->insert(['role_id' => $role, 'content_id' => $content->id, 'created_at' => $now,'updated_at' => $now]);
				}
			}
		}

		foreach ($categorylist as $category) {
			$Allcategory[] = $category->id;
			foreach ($AllContent as $content) {
				$data = DB::table('content__multiplecategorycontents')
							->where('category_id', '=', $category->id)
							->where('content_id', '=', $content->id)
							->get();

				if (sizeof($data) == 0) {
					DB::table('content__multiplecategorycontents')->insert(
						['category_id' => $category->id, 'content_id' => $content->id, 'created_at' => $now, 'updated_at' => $now]
					);
				}
			}
		}

		return 'successful update';
	}

}
