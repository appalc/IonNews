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
use Modules\Content\Entities\StoryCategory;
use Modules\Content\Entities\UserGroup;

use Modules\Content\Repositories\CategoryRepository;
use Modules\Content\Repositories\ContentRepository;
use Modules\Content\Repositories\UserGroupRepository;
use Modules\Core\Http\Controllers\BasePublicController;
use Modules\Services\Repositories\UsertypeRepository;
use Modules\User\Events\UserHasBegunResetProcess;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Repositories\UserRepository;
use Modules\User\Services\UserResetter;
use Modules\User\Entities\Sentinel\User;
use DB;
use Log;
use Validator;

class ContentController extends BasePublicController
{
    protected $guard;

	public function __construct(
		Response $response,
		Guard $guard,
		UserRepository $user,
		CategoryRepository $category,
		ContentRepository $content,
		StoryCategory $storyCategory,
	 	UserGroupRepository $userGroup
	) {
		parent::__construct();

		$this->category      = $category;
		$this->content       = $content;
		$this->guard         = $guard;
		$this->response      = $response;
		$this->user          = $user;
		$this->userGroup     = $userGroup;
		$this->storyCategory = $storyCategory;
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
		$Alldata = $request->all();
		$image   = '';

		if (empty($Alldata['tags'])) {
			$categoryName    = $this->category->find($Alldata['category_id'])->pluck('name')->first();
			$Alldata['tags'] = !empty($categoryName) ? '#' . $categoryName : '';
		}

		if ($request->hasFile('img')) {
			$image_name = $_FILES['img']['name'];
			$request->file('img')->move(env('IMG_URL') . '/crawle_image', $image_name);

			$image            = env('IMG_URL1') . '/crawle_image/' . $image_name;
			$Alldata['image'] = $image;
		} elseif (!array_key_exists('image', $Alldata)) {
			$Alldata['image'] = (array_key_exists('img1', $Alldata)) ? $Alldata['img1'] : $image;
		}

		$categoriesWithName      = $this->category->all()->pluck('id', 'slug_name');
		$storyCategoryData       = collect($Alldata['category_id'])->map(function ($cateSlug) use ($categoriesWithName) {
			return !empty($categoriesWithName[$cateSlug]) ? $categoriesWithName[$cateSlug] : '';
		})->filter()->toArray();
		$Alldata['all_category'] = json_encode($storyCategoryData);
		$Alldata['category_id']  = sizeof($storyCategoryData);
		$Alldata['content']      = trim($Alldata['content']);

		$id = $this->content->create($Alldata)->id;

		if (sizeof($storyCategoryData)) {
			foreach ($storyCategoryData as $value) {
				$this->storyCategory->create(['category_id' => $value, 'story_id' => $id]);
			}
		}

		$this->generateStoryNotifications([
			'title'     => $Alldata['title'],
			'message'   => $Alldata['content'],
			'imageUrl'  => (array_key_exists('image', $Alldata)) ? $Alldata['image'] : '',
			'crawl_url' => $Alldata['crawl_url'],
		], $storyCategoryData);

		return true;
	}

	/**
	* To retrieve user's device info's and generate push notifictions
	*
	* @param array $notification  Array of ntification infos
	* @param array $allCategories Array of categories
	*
	* @return Response
	*/
	private function generateStoryNotifications($notification, $allCategories)
	{
		return true;

		$results = UserGroup::select('id');
		foreach ($allCategories as $categoryId) {
			$results->orWhereRaw('json_contains(category_id, \'["' . $categoryId . '"]\')');
		}

		$userGroups  = $results->get()->pluck('id');
		$usersToSend = User::where('user_group_id', '=', $userGroups)->get()->pluck('device_code', 'device_type')->toArray();

		foreach ($usersToSend as $deviceType => $deviceCode) {
			if (($deviceType == "iphone") && ($deviceCode)) {
				$this->push_notificationsIOS($message, $deviceCode);
			}

			if (($deviceType == "android") && ($deviceCode)) {
				$this->push_notifications($message, $deviceCode);
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
