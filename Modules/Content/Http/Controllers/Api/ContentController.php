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
use Modules\Content\Repositories\UserGroupRepository;
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

class ContentController extends BasePublicController
{
    protected $guard;

	public function __construct(Response $response, Guard $guard, UserRepository $user, CategoryRepository $category, RoleRepository $role, ContentRepository $content, ContentLikeStoryRepository $likestory, MultipleCategoryContentRepository $multiContCategory, UserGroupRepository $userGroup)
	{
		parent::__construct();

		$this->category          = $category;
		$this->content           = $content;
		$this->guard             = $guard;
		$this->likestory         = $likestory;
		$this->multiContCategory = $multiContCategory;
		$this->response          = $response;
		$this->role              = $role;
		$this->user              = $user;
		$this->userGroup         = $userGroup;
	}

	/**
	 * Create story
	 *
	 * @param  Request $request [description]
	 * @param  Client  $http    [description]
	 *
	 * @return array Array of tags and category
	 */
	public function createStory(Request $request, Client $http)
	{
		$Alldata    = $request->all();
		$tags       = '';
		$user_roles = $Alldata['user_roles'];

		$Alldata['all_users'] = json_encode($user_roles);
		if (!$Alldata['tags']) {
			$categoryName = $this->category->find($Alldata['category_id']);
			$categoryName = json_decode($categoryName, true);
			if (sizeof($categoryName)) {
				foreach ($categoryName as $value) {
					$tags = $tags . "#" . $value['name'];
					break;
				}
			}

			$Alldata['tags'] = $tags;
		}

		$Alldata['content'] = trim($Alldata['content']);
		$image              = "";

		if ($request->hasFile('img')) {
			$image_name = $_FILES['img']['name'];
			$request->file('img')->move(env('IMG_URL') . '/crawle_image', $image_name);

			$image            = env('IMG_URL1') . '/crawle_image/' . $image_name;
			$Alldata['image'] = $image;
		} elseif (!array_key_exists('image', $Alldata)) {
			$Alldata['image'] = (array_key_exists('img1', $Alldata)) ? $Alldata['img1'] : $image;
		}

		$sizeofCategories        = sizeof($Alldata['category_id']);
		$multiContCategoryData   = $Alldata['category_id'];
		$Alldata['all_category'] = json_encode($Alldata['category_id']);
		$Alldata['category_id']  = $sizeofCategories;

		$ids = $this->content->create($Alldata);

		$id = json_decode($ids, true);
		$id = $ids['id'];

		if (!in_array(-1, $user_roles)) {
			foreach ($user_roles as $key => $value) {
				$abc['role_id']    = $value;
				$abc['content_id'] = $id;

				$this->userGroup->create($abc);
			}
		} else {
			$all_roles = json_decode($this->role->all(), true);
			foreach ($all_roles as $key => $value) {
				if($value['id'] != 1) {
					$abc['role_id']    = $value['id'];
					$abc['content_id'] = $id;
					$this->userGroup->create($abc);
				}
			}
		}

		if (sizeof($multiContCategoryData)) {
			foreach ($multiContCategoryData as $value) {
				$abc['category_id'] = $value;
				$abc['content_id']  = $id;

				$this->multiContCategory->create($abc);
			}
		}

		$company_name = [];
		$i            = 0;  
		$device_code  = []; 
		$users        = json_decode(User::all(), true);
		$role_ids     = $Alldata['user_roles'];
		$final_users  = [];

		if (!in_array(-1, $role_ids)) {
			$user_roll = $this->role->find($role_ids);
			$all_roles = json_decode($user_roll, true);

			foreach ($all_roles as $key => $value) {
				$find[] = $value['slug'];
			}

			foreach ($users as $key => $value) { 
				if (in_array($value['role'], $find)) {
					$final_users[] = $value;
				}
			}
		} else {
			$final_users = $users;
		}

		foreach ($users as $key => $value) {
			if (!empty($final_users[$i]) && ($value['id'] == $final_users[$i]['id'])) {
				$company_name[] = $value['company'];
				$i++;
				if($value['device_type'])
					$device_code[$value['device_type']][$value['id']] = $value['device_code'];
			}

			if ($i >= sizeof($final_users))
				break;
		}

		$message = [
			'title'     => $Alldata['title'],
			'message'   => $Alldata['content'],
			'imageUrl'  => (array_key_exists('image', $Alldata)) ? $Alldata['image'] : '',
			'crawl_url' => $Alldata['crawl_url'],
		];

		foreach ($device_code as $deviceType => $value) {
			foreach ($value as $deviceIds) {
				if ($value && $deviceType == 'iphone') {
		//			$this->push_notificationsIOS($message, $deviceIds);
				} elseif ($value && $deviceType == 'android') {
		//			$this->push_notifications($message, $deviceIds);
				}
			}
		}

		return true;
	}

	/**
	 * [push_notifications description]
	 * @param  array  $msg             [description]
	 * @param  [type] $registrationIds [description]
	 * @return [type]                  [description]
	 */
	public function push_notifications($msg = [], $registrationIds)
	{
		$fields = [
			'registration_ids' => [$registrationIds],
			'data'             => $msg,
		];

		$headers = [
			'Authorization: key=' . env("API_ACCESS_KEY"),
			'Content-Type : application/json'
		];

		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, 'http://android.googleapis.com/gcm/send');
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		curl_close($ch);

		return response($result);
	}

	public function push_notificationsIOS($msg = [], $registrationIds)
	{
		$fields = [
			'to'           => $registrationIds,
			'data'         => $msg,
			'notification' => ['title' => 'ION NEWS'],
		];

		$headers = [
			'Authorization: key=' . env("API_ACCESS_KEY"),
			'Content-Type: application/json'
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://android.googleapis.com/gcm/send');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		curl_close($ch);

		return response($result);
	}

}
