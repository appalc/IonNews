<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Content\Entities\Content;
use Modules\Content\Entities\ContentImages;
use Modules\Content\Entities\ContentUser;
use Modules\Content\Entities\ContentCompany;
use Modules\Content\Entities\StoryCategory;
use Modules\Content\Entities\UserGroup;
use Modules\User\Entities\Sentinel\User;

use Modules\Content\Repositories\ContentRepository;
use Modules\Content\Repositories\ContentUserRepository;
use Modules\Content\Repositories\UserGroupRepository;
use Modules\Content\Repositories\CategoryRepository;
use Modules\User\Repositories\UserRepository;
use Modules\User\Contracts\Authentication;

use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Content\Http\Requests\CreateContentRequest;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Modules\User\Repositories\RoleRepository;
use Log;
use DB;

class ContentController extends AdminBaseController
{

	/**
	* @var ContentRepository
	*/
	private $content;

	/**
	 * @var string Story type
	 */
	private $storyType = 'news';

	public function __construct(
		ContentRepository $content,
		CategoryRepository $category,
		ContentUserRepository $contentUser,
		RoleRepository $role,
		UserGroupRepository $userGroup,
		StoryCategory $storyCategory
	) {
		parent::__construct();

		$this->category      = $category;
		$this->content       = $content;
		$this->contentUser   = $contentUser;
		$this->role          = $role;
		$this->storyCategory = $storyCategory;
		$this->userGroup     = $userGroup;
	}

	/**
	* Display a listing of the resource.
	*
	* @return Response
	*/
	public function index()
	{
		$categories = $this->category->getByAttributes(['status' => 1]);
		$contents   = content::where('type', '=', $this->storyType)->get();

		return view('content::admin.contents.index', compact('contents', 'categories'));
	}

	/**
	* Show the form for creating a new resource.
	*
	* @return Response
	*/
	public function create()
	{
		$categories = $this->category->getByAttributes(['status' => 1], 'priority', 'desc')->pluck('name', 'id');

		return view('content::admin.contents.create', compact('categories'));
	}

	/**
	* TO crawl information from a url
	*
	* @param Request $request [description]
	*
	* @return array Response array of url info
	*/
	public function ajaxcall(Request $request)
	{
		$crawlResult   = ['title' => '', 'sub_title' => '', 'status' => 400, 'count' => 0, 'img_count' => 0];
		$urlToCrawl    = !empty($_GET['url']) ? $_GET['url'] : '';
		$imgExtenstion = ['gif','cms','js','html'];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $urlToCrawl);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// stop CURL from verifying the peer's certificate
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_POST, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$result = curl_exec($ch);
		curl_close($ch);

		$dom = new \DOMDocument();
		libxml_use_internal_errors(true);
		$dom->loadHTML($result);

		// Can't crawl the url
		if ($dom->getElementsByTagName('title')->length < 1) {
			return $crawlResult;
		}

		$title    = $dom->getElementsByTagName('title')->item(0)->nodeValue;
		$subTitle = $title;
		if ($dom->getElementsByTagName('title')->length > 1) {
			$subTitle = $dom->getElementsByTagName('title')->item(1)->nodeValue;
		}

		$crawlResult['title']     = $title;
		$crawlResult['sub_title'] = $subTitle;
		$crawlResult['status']    = 202;
		  
		$imgSource = $dom->getElementsByTagName("*");
		$k         = 0;
		$j         = 0;
		$baseUrl   = "";
		$url       = explode("/", $urlToCrawl);
		$baseUrl   = "http://" . $url[2];
		foreach ($imgSource as $item) {
			if ($item->getAttribute('data-src')) {
				$img_array['data-src'][$j++]['img_url'] = $item->getAttribute('data-src');
			} elseif ($item->getAttribute('src')) {
				$img_array['src'][$k]['img_url']  = $item->getAttribute('src');
				$img_array['src'][$k]['width']    = ($item->getAttribute('width')) ? $item->getAttribute('width') : 0;
				$img_array['src'][$k]['height']   = ($item->getAttribute('height')) ? $item->getAttribute('height') : 0;
				$img_array['src'][$k]['img_name'] = ($item->getAttribute('alt')) ? $item->getAttribute('src') : 'sample';
				
				$k++;
			}
		}

		if (sizeof($img_array) > 0) {
			$all_img_array =  [];
			$k             = 0;
			$url_check     = [];
			arsort($img_array['src']);

			if (array_key_exists('data-src', $img_array)  && sizeof($img_array['data-src'])) {
				foreach ($img_array['data-src'] as $value) {
					$split_image = pathinfo($value['img_url']);
					if (!in_array($split_image['dirname'], $url_check)) {
						if (array_key_exists('extension', $split_image) && (strlen($split_image['extension']) >= 2) && (strlen($split_image['extension']) <= 4)) {
							if (!in_array($split_image['extension'], $imgExtenstion) ) {
								if (substr($value['img_url'], 0,1) == "/") {
									$all_img_array[$k]['img_url']  = $baseUrl . $value['img_url'];
									$all_img_array[$k]['img_name'] = 'sample';

									$url_check[$k] = $split_image['dirname'];
								} else {
									$all_img_array[$k]['img_url']  = $value['img_url'];
									$all_img_array[$k]['img_name'] = 'sample';
									$url_check[$k]                 = $value['img_url']; 
								}

								$k++;
							}
						}
					}
				}
			}

			foreach ($img_array['src'] as $key => $value) {
				if(!in_array($value['img_url'], $url_check)) {
					$split_image = pathinfo($value['img_url']);
					if(array_key_exists('extension',$split_image) && strlen($split_image['extension'])>=2 && strlen($split_image['extension']) <= 4) {
						if (!in_array($split_image['extension'], $imgExtenstion)) {
							$all_img_array[$k]['width']    = $value['width'];
							$all_img_array[$k]['height']   = $value['height'];
							$all_img_array[$k]['img_url']  = (substr($value['img_url'], 0,1 ) == "/") ? $baseUrl . $value['img_url'] : $value['img_url'];
							$all_img_array[$k]['img_name'] = $value['img_name'];
							$url_check[$k++]               = $value['img_url'];
						}
					}
				}
			}

			$paragraph = $dom->getElementsByTagName('p');
			$ul_list   = $dom->getElementsByTagName('li');  
			$paraarray = [];

			foreach ($paragraph  as $pdata) {
				if (isset($pdata->childNodes[0]->tagName) && $pdata->childNodes[0]->tagName!='style')
					$paraarray[] = $pdata->nodeValue;
			}

			$paraSize  = sizeof($paraarray);
			$paraCount = 0;
			$extra     = ' ';
			for ($i = 0; $i < $paraSize ; $i++) {
				if (sizeof($all_img_array) > $i and $i < 4) {
					$crawlResult[$i]['img_name'] = $all_img_array[$i]['img_name'];
					$crawlResult[$i]['img_url']  = $all_img_array[$i]['img_url'];
				}

				if ($paraCount < $paraSize) {
					if (strlen($paraarray[$i]) > 80)
						$crawlResult[$paraCount++]['desc'] = $paraarray[$i];
					elseif ($k++ > 10)
						$extra = $extra . $paraarray[$i];
				}
			}

			if ($paraCount > 4)
				$crawlResult[$paraCount++]['desc'] = $extra;

			$count                    = sizeof($crawlResult);
			$crawlResult['count']     = $count;
			$crawlResult['img_count'] = sizeof($all_img_array);
			$crawlResult['status']    = 200;
		}//endif

		return $crawlResult;
	}

	/**
	* Store a newly created resource in storage.
	*
	* @param Request $request
	* @return Response
	*/
	public function store(Request $request)
	{
		$Alldata = $request->all();
		$tags    = "";
		$image   = "";

		if (empty($Alldata['tags'])) {
			$categoryName    = $this->category->find($Alldata['category_id'])->pluck('name')->first();
			$Alldata['tags'] = !empty($categoryName) ? $tags . "#" . $categoryName : $tags;
		}

		if ($request->hasFile('img')) {
			$image_name = $_FILES['img']['name'];
			$request->file('img')->move(env('IMG_URL') . '/crawle_image', $image_name);

			$image            = env('IMG_URL1') . '/crawle_image/' . $image_name;
			$Alldata['image'] = $image;
		} elseif (!array_key_exists('image', $Alldata)) {
			$Alldata['image'] = (array_key_exists('img1', $Alldata)) ? $Alldata['img1'] : $image;
		}

		$storyCategoryData       = $Alldata['category_id'];
		$Alldata['all_category'] = json_encode($Alldata['category_id']);
		$Alldata['category_id']  = sizeof($Alldata['category_id']);
		$Alldata['content']      = trim($Alldata['content']);

		$id = $this->content->create($Alldata)->id;

		if (sizeof($storyCategoryData)) {
			foreach ($storyCategoryData as $value) {
				$this->storyCategory->create(['category_id' => $value, 'story_id' => $id]);
			}
		}

		$message = [
			'title'     => $Alldata['title'],
			'message'   => $Alldata['content'],
			'imageUrl'  => (array_key_exists('image', $Alldata)) ? $Alldata['image'] : '',
			'crawl_url' => $Alldata['crawl_url'],
		];

		$this->generateStoryNotifications($message, $storyCategoryData);

		if (env('STORY_PUSH_ENABLE') && $request->pushToProd) {
			try {
				$this->_pushToProductionInstance($request->all());
			} catch (Exception $e) {
				echo 'Failed to push to Production Instance';
			}
		}

		return redirect()->route('admin.content.content.index')->withSuccess(
			trans('core::core.messages.resource created', ['name' => trans('content::contents.title.contents')])
		);
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
	* Show the form for editing the specified resource.
	*
	* @param  Content $content
	* @return Response
	*/
	public function edit(Content $content)
	{
		$categories = $this->category->getByAttributes(['status' => 1], 'priority', 'desc')->pluck('name', 'id');

		return view('content::admin.contents.edit', compact('content', 'categories'));
	}

	/**
	* Update the specified resource in storage.
	*
	* @param  Content $content
	* @param  Request $request
	* @return Response
	*/
	public function update(Content $content, Request $request)
	{
		$data        = $request->all();
		$contentId   = $content->id;
		$Allcategory = $data['category_id'];

		$data['all_category'] = json_encode($data['category_id']);
		$data['category_id']  = count($data['category_id']);

		// To Manage Category
		$categoryID = DB::table('story_categories')->where('story_id', '=', $contentId)->delete();
		foreach ($Allcategory as $value) {
			$this->storyCategory->create(['category_id' => $value, 'story_id' => $contentId]);
		}

		// To manage Images
		if ($request->hasFile('img')) {
			$image_name = $contentId . $_FILES['img']['name'];
			$request->file('img')->move(env('IMG_URL') . '/crawle_image', $image_name);
			$image = env('IMG_URL1') . '/crawle_image/' . $image_name;
		} else {
			$image = $content->image;
		}

		$request->merge(['image' => $image]);
		$data['image'] = $image;

		$this->content->update($content, $data);

		return redirect()->route('admin.content.content.index')
			->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('content::contents.title.contents')]));
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  Content $content
	* @return Response
	*/
	public function destroy(Content $content)
	{
		$this->content->destroy($content);

		return redirect()->route('admin.content.content.index')
			->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('content::contents.title.contents')]));
	}

	public function getAllUsers(Request $request)
	{
		$users =json_decode(User::all(),true);
		if (isset($_GET['id'])) {
			$content_id = $_GET['id']; 
			$userData   = DB::table('content__contentusers as cu')->select(\DB::raw('u.*'))
				->join('users as u','u.id', '=', 'cu.user_id')
				->where('cu.content_id', '=', $content_id)->get();
			$check_aray    = array();
			$uncheck_array = array();
			$userData      = json_decode($userData,true);
			$j             = 0;
			$k             = 0;
			$userId        = array();

			foreach ($userData as $key => $value) {
				$userId[$key] = $value['id'];
			}

			$ch = 0;
			foreach ($users as $value) {
				if ($ch<sizeof($userData) && in_array($value['id'], $userId)) {
					if ($value['role']) {
						$check_array[$value['role']][$k]['id']      = $value['id'];
						$check_array[$value['role']][$k]['name']    = $value['first_name'];
						$check_array[$value['role']][$k]['role']    = $value['designation'];
						$check_array[$value['role']][$k]['company'] = $value['company'];
					} else {
						$check_array['default'][$k]['id']      = $value['id'];
						$check_array['default'][$k]['name']    = $value['first_name'];
						$check_array['default'][$k]['role']    = $value['designation'];
						$check_array['default'][$k]['company'] = $value['company'];
					}

					$k++;
					$ch++;
				} else {
					if ($value['role']) {
						$uncheck_array[$value['role']][$j]['id']      = $value['id'];
						$uncheck_array[$value['role']][$j]['name']    = $value['first_name'];
						$uncheck_array[$value['role']][$j]['role']    = $value['designation'];
						$uncheck_array[$value['role']][$j]['company'] = $value['company'];
					} else {
						$uncheck_array['default'][$j]['id']      = $value['id'];
						$uncheck_array['default'][$j]['name']    = $value['first_name'];
						$uncheck_array['default'][$j]['role']    = $value['designation'];
						$uncheck_array['default'][$j]['company'] = $value['company'];
					}

					$j++;
				}
			}

			$FinalArray['check']   = $check_array;
			$FinalArray['uncheck'] = $uncheck_array;
			} else {
				$company_name = array();
				$k            = 0;
				$FinalArray   = array();
				foreach ($users as $value) {
					if ($value['role']) {
						$FinalArray[$value['role']][$k]['id']      = $value['id'];
						$FinalArray[$value['role']][$k]['name']    = $value['first_name'];
						$FinalArray[$value['role']][$k]['role']    = $value['designation'];
						$FinalArray[$value['role']][$k]['company'] = $value['company'];
					} else {
						$FinalArray['default'][$k]['id']      = $value['id'];
						$FinalArray['default'][$k]['name']    = $value['first_name'];
						$FinalArray['default'][$k]['role']    = $value['designation'];
						$FinalArray['default'][$k]['company'] = $value['company'];
					}

					$k++;
				}
			}

			return $FinalArray;
		}

	public function getAllUsersInfo(Request $request)
	{
		$users           = json_decode(User::all(), true);
		$FinalArray_name = array();
		$FinalArray_ids  = array();
		foreach ($users as $key => $value) {
			$name                  = $value['first_name'] . " " . $value['last_name'];
			$FinalArray_name[]     = $name;
			$FinalArray_ids[$name] = $value['id'];
		}

		$FinalArray['name'] = $FinalArray_name;
		$FinalArray['ids']  = $FinalArray_ids;

		return response()->json($FinalArray);
	}

	public function store_user_info(Request $request)
	{
		$content_id = $_GET['content_id'];
		$user_id    = $_GET['user_id'];
		// echo $content_id."   ".$user_id; exit;

		$userData = DB::table('content__contentusers')->select(\DB::raw('*'))->where('content_id', '=', $content_id)->get();
		$userData = json_decode($userData,true);
		$check    = 0;
		foreach ($userData as $userInfo) {
			if (in_array($user_id, $userInfo))
				$check = 1;
		}

		if ($check ==0) {
			$ContentUser             = new ContentUser;
			$ContentUser->user_id    = $user_id;
			$ContentUser->content_id = $content_id;
			$ContentUser->save();

			return 200;
		} else
			return 202;
	}

	public function push_notifications($msg = array(),$registrationIds)
	{
		$API_ACCESS_KEY = env("API_ACCESS_KEY");
		$fields         = [
			'registration_ids' => array($registrationIds),
			'data'             => $msg,
		];

		$headers = [
			'Authorization: key=' . $API_ACCESS_KEY,
			'Content-Type : application/json'
		];

		$url ='https://fcm.googleapis.com/fcm/send';

		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'http://android.googleapis.com/gcm/send');
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch );
		curl_close( $ch );

		return response($result);
	}

	public function push_notificationsIOS($msg = array(),$registrationIds)
	{
		$API_ACCESS_KEY = env("API_ACCESS_KEY");

		// $fields = array
		// (
		//   'registration_ids'  =>array($registrationIds),
		//   'data'      => $msg
		// );
		// $fields = array(
		//     'registration_ids'  => array($registrationIds),
		//     'notification'      => $msg
		// );
		$notification['title'] ='ION NEWS';
		$fields = array(
			'to'           => $registrationIds,
			'data'         => $msg,
			'notification' => $notification
		);

		$headers = array
		(
		'Authorization:key=' . $API_ACCESS_KEY,
		'Content-Type: application/json'
		);
		$url ='https://fcm.googleapis.com/fcm/send';

		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'http://android.googleapis.com/gcm/send');
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($fields) );
		$result = curl_exec($ch );       
		curl_close( $ch );
		// Log::info($result);

		return response($result);
	}

	public function push_notificationsIOS1($smg =array(),$registrationIds)
	{
		$apnsHost = env('apnsHost');
		$apnsCert = env('apnsCert');
		$apnsPort = env('apnsPort');
		$apnsPass = env('apnsPass');
		// $token =$registrationIds;
		$token ='56ed3ac2a250158cc76c33af099a0629fb41a4565923154cb0675baa468b9915';

		// Log::info(json_encode($smg));
		// $message          =json_encode($smg);
		// $payload['aps']   = array('alert' => 'Oh hai!','badge' => 1, 'sound' => 'default');
		// $payload['acme2'] ='ION NEWS';
		// $output           = json_encode($payload);

		$title     = substr( $smg['title'],0,45);
		$message   = substr($smg['message'],0,10);
		$img_url   = $smg['imageUrl'];
		$crawl_url = $smg['crawl_url'];
		// $story  ='IBM NEWS';
		// $title  ='ION NEWS';
		// $url    ="http://assets.jpg";

		$output    ='{
			"aps"      : {
				"alert"    : {
					"title"    :"' . $title . '",
					"body"     : "' . $message . '"
				}
			},
			"mediaUrl" : "' . $img_url . '",
			"mediaType": "image"}';

		// $output='{
		// "aps": {
		// "alert": {
		// "title": "123456789012345678901\n23456789012345766737373\n12345678901234",
		// "body": "titi"
		// }
		// },
		// "mediaUrl": "https://www.w3schools.com/html/pic_mountain.jpg",
		// "mediaType": "image"}';
		// Log::info($output);
		// Log::info($payload['acme2']=['abab','bababa']);
		// Log::info($output);
		// $token = pack('H*', str_replace(' ', '', $token));

		$apnsMessage = chr(0).chr(0).chr(32).$token.chr(0).chr(strlen($output)).$output;

		$streamContext = stream_context_create();
		stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
		stream_context_set_option($streamContext, 'ssl', 'passphrase', $apnsPass);

		$apns = stream_socket_client('ssl://'.$apnsHost.':'.$apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
		// print_r($apns);
		Log::info($apns);

		if (!$apns)
			exit("Failed to connect: $err $errstr" . PHP_EOL);
			//echo 'Connected to APNS' . PHP_EOL;

		fwrite($apns, $apnsMessage);
		fclose($apns);
		sleep(60);
		// echo "hahhaa";
	}

	public function deleteStory(Request $request)
	{
		try {
			$data = DB::table('stories')->whereIn('id', $request->data)->delete();
		} catch(Exception $e) {
			echo 'Message: ' . $e->getMessage();
		}

		return response($data);
	}

	/**
	 * call content create API to create story on production instance
	 *
	 * @param mixed $data [description]
	 *
	 */
	private function _pushToProductionInstance($data)
	{
		$ch = curl_init(env('PROD_SERVER_URL') . '/api/content/createStory');

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Authorization: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vNTAuMTEyLjU3LjE0Ni9hcGkvYXV0aGVudGljYXRpb24vbG9naW4iLCJpYXQiOjE1MzgwNDM4NzcsImV4cCI6MTU1MTM3OTQ3NywibmJmIjoxNTM4MDQzODc3LCJqdGkiOiJZNU14MktHbzRZWVhzSEtUIiwic3ViIjo4NX0.2YqoK4rVT1jEbpkUx0DopH5ZIhFCk-UXl_asT7V4xsY',
			'Content-Type: application/json',
		));

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		$result = curl_exec($ch);

		return curl_close($ch);
	}

	/**
	 * Push multiple story to production instance by Ajax Call
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function pushStoryToProd(Request $request)
	{
		$stories = DB::table('stories as cc')
			->join('content__usergroups as cug', 'cug.content_id', '=', 'cc.id')
			->select('cc.*', 'cug.role_id')
			->whereIn('cc.id', $request->data)
			->get();

		$roleIds          = [];
		$storiesToProcess = $stories->mapWithKeys(function ($content) use (&$roleIds) {
			$roleIds[$content->id][] = $content->role_id;
			$content->role_id        = $roleIds[$content->id];

			return [$content->id => $content];
		})->reverse()->unique('id');

		$storiesToProcess = $storiesToProcess->map(function ($content) {
			$this->_pushToProductionInstance([
				'_token'      => 'NNWS3STN00nXOLV2O0GIa3wVP0eqR8ceS' . rand(1111, 9999),
				'crawl_url'   => $content->crawl_url,
				'title'       => $content->title,
				'sub_title'   => $content->sub_title,
				'tags'        => $content->tags,
				'category_id' => json_decode($content->all_category),
				'expiry_date' => $content->expiry_date,
				'content'     => $content->content,
				'image'       => $content->image,
				'img1'        => $content->image,
				'img2'        => '',
				'img3'        => '',
				'img4'        => '',
				'user_roles'  => $content->role_id,
			]);
		});

		return response('Selected contents moved to Production Instance [' . json_encode($request->data) . ']');
	}

	/**
	 * Push multiple story to production instance by Ajax Call
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function ChangeExpiryDate(Request $request)
	{
		$response = Content::whereIn('id', $request->id)->update(['expiry_date' => $request->date]);

		return response(($response) ? 'Expiry date updated to the Selected Stories' : 'Expiry date could not be updated, Try Again');
	}

}
