<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\Content;
use Modules\Content\Repositories\ContentRepository;
use Modules\Content\Repositories\ContentUserRepository;
use Modules\Content\Repositories\UserGroupRepository;
use Modules\Content\Repositories\MultipleCategoryContentRepository;
use Modules\Content\Repositories\CategoryRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\User\Repositories\UserRepository;
use Modules\User\Contracts\Authentication;

use Modules\User\Entities\Sentinel\User;
use Modules\Content\Entities\ContentImages;
use Modules\Content\Entities\ContentUser;
use Modules\Content\Entities\ContentCompany;
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

    public function __construct(ContentRepository $content,CategoryRepository $category,ContentUserRepository $contentUser , MultipleCategoryContentRepository $multiContCategory,RoleRepository $role , UserGroupRepository $userGroup)
    {
        parent::__construct();
        $this->category = $category;
        $this->content = $content;  
        $this->contentUser=$contentUser;
        $this->multiContCategory=$multiContCategory;
        $this->role=$role;
        $this->userGroup=$userGroup;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {   
        $categories = $this->category->getByAttributes(['status' => 1]);
        // Log::info(json_decode($categories,true)); 
        $contents = $this->content->all(); 
        // Log::info(json_decode($contents,true)); die;
        

        return view('content::admin.contents.index', compact('contents','categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $categories = $this->category->getByAttributes(['status' => 1],'priority','desc');
        foreach ($categories as $key => $value) {
          if($value->slug_name=='archive')
            unset($categories[$key]);         
        }

        $roles=json_decode($this->role->all());          
          $user_roles[-1]['id']=-1;       
          $user_roles[-1]['type']='All';
                       
          foreach ($roles as $value) { 
          if($value->name!='Admin')
          {                
          $user_roles[$value->id]['id']=$value->id;        
          $user_roles[$value->id]['type']=$value->name;
        
          }
         }  
             

        return view('content::admin.contents.create',compact('categories'),compact('user_roles'));
    }

    public function ajaxcall(Request $request)
    {  
        $url=$_GET['url']; 
        $img_extenstion=['gif','cms','js','html'] ;            
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $result = curl_exec($ch);
        curl_close($ch);
            $dom = new \DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($result);
            if($dom->getElementsByTagName('title')->length>1){
            $title = $dom->getElementsByTagName('title')->item(0)->nodeValue;
            $sub_title = $dom->getElementsByTagName('title')->item(1)->nodeValue;
            }else {
             $title = $dom->getElementsByTagName('title')->item(0)->nodeValue;
             $sub_title = $title;
            }
            // echo "<pre>";
            // echo $title; echo "<br>";
            // echo $sub_title; echo "<br>";
              
               $img1=$dom->getElementsByTagName("*");
               $k=0; $j=0;
               $baseUrl="";   
               $url=explode("/",$url); 
               $baseUrl="http://".$url[2];                

                foreach ($img1 as $item) {
                  if($item->getAttribute('data-src'))
                  {
                    $img_array['data-src'][$j++]['img_url']= $item->getAttribute('data-src');
                    
                 
                  }
                  else if($item->getAttribute('src'))
                  {                    
                    if($item->getAttribute('width'))
                    $img_array['src'][$k]['width']= $item->getAttribute('width');
                    else $img_array['src'][$k]['width']=0;
                    if($item->getAttribute('height'))
                    $img_array['src'][$k]['height']= $item->getAttribute('height');
                    else $img_array['src'][$k]['height']=0;
                    $img_array['src'][$k]['img_url']= $item->getAttribute('src');
                    if($item->getAttribute('alt'))
                       $img_array['src'][$k]['img_name']= $item->getAttribute('src');
                     else $img_array['src'][$k]['img_name']="sample";
                    $k++;                    
                  }
                }

       

           if(sizeof($img_array)>0)
           {        
            $all_img_array=array();
            $k=0;
            $url_check=array();
            arsort($img_array['src']);
               if(array_key_exists('data-src', $img_array)  && sizeof($img_array['data-src']))
               {
               foreach ($img_array['data-src'] as $value) {
                $split_image = pathinfo($value['img_url']);
                if(!in_array($split_image['dirname'], $url_check)){             

                    if(array_key_exists('extension',$split_image) && strlen($split_image['extension'])>=2 && strlen($split_image['extension'])<=4)
                   {
                    if(!in_array($split_image['extension'], $img_extenstion) ){
                    if(substr($value['img_url'], 0,1)=="/")
                    {
                    $all_img_array[$k]['img_url']=$baseUrl.$value['img_url'];
                    $all_img_array[$k]['img_name']="sample";

                    $url_check[$k]=$split_image['dirname']; 
                    }
                    else{
                      $all_img_array[$k]['img_url']=$value['img_url'];
                      $all_img_array[$k]['img_name']="sample";
                      $url_check[$k]=$value['img_url']; 
                    }  
                    $k++;              
                   }
                 }
                }
              }
            }
             
             foreach ($img_array['src'] as $key => $value) {
                if(!in_array($value['img_url'], $url_check))
                {
                  $split_image = pathinfo($value['img_url']);


                  if(array_key_exists('extension',$split_image) && strlen($split_image['extension'])>=2 && strlen($split_image['extension'])<=4)
                  {
                  if(!in_array($split_image['extension'], $img_extenstion)){
                  $all_img_array[$k]['width']=$value['width'];
                  $all_img_array[$k]['height']=$value['height'];  
                  if(substr($value['img_url'], 0,1)=="/")                  
                    $all_img_array[$k]['img_url']=$baseUrl.$value['img_url'];                  
                  else 
                    $all_img_array[$k]['img_url']=$value['img_url'];
                  $all_img_array[$k]['img_name']=$value['img_name'];
                  $url_check[$k]=$value['img_url'];
                  $k++;
                } 
              } 
              }          
             } 
               
            $paragraph = $dom->getElementsByTagName('p');
            $ul_list = $dom->getElementsByTagName('li');  

            $paraarray = array();
            
            foreach ($paragraph  as $pdata){
                if( isset($pdata->childNodes[0]->tagName) && $pdata->childNodes[0]->tagName!='style')
                $paraarray[] = $pdata->nodeValue;
            }
            // echo "<pre>";
            // print_r($paraarray); die;

             $parasize=sizeof($paraarray);
             $paracount=0;
              // if($paraarray>20)
              // {
              //   $paracount=15;
              // }
              //  else {
              //   $paracount=0;
              //  }
             $extra=' ';
            for ($i = 0; $i <$parasize ; $i++){
                if(sizeof($all_img_array)>$i and $i<4){
                    $FinalArray[$i]['img_name'] = $all_img_array[$i]['img_name'];
                    $FinalArray[$i]['img_url'] = $all_img_array[$i]['img_url'];
                }
                if ($paracount<$parasize)
                {   if(strlen($paraarray[$i])>80)
                    $FinalArray[$paracount++]['desc'] = $paraarray[$i];

                    else if($k++>10)$extra=$extra.$paraarray[$i];
                }
             }
             if($paracount>4)
              $FinalArray[$paracount++]['desc']=$extra;

             // print_r($FinalArray); die;
            $count = sizeof($FinalArray);
            $FinalArray['title'] = $title;
            $FinalArray['sub_title'] = $title;
            $FinalArray['count'] = $count;
            $FinalArray['img_count'] = sizeof($all_img_array);
            $FinalArray['status']=200;
            }else{  $FinalArray['title'] = $title;
                    $FinalArray['status']=202;
             }

            // echo "<pre>"; echo "<br>"; print_r($FinalArray); exit;
        return $FinalArray;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request  $request)
    {
      $Alldata=$request->all(); 
      $tags="";
      $user_roles=$Alldata['user_roles'];     
      $Alldata['all_users']=json_encode($user_roles);
      if(!$Alldata['tags']){
          $category_name=$this->category->find($Alldata['category_id']);    
          $i=0; 
          $category_name=json_decode($category_name,true);
          if(sizeof($category_name)){
              
              foreach ( $category_name as $value) {
                  $tags=$tags."#".$value['name'];                 
                  break;
                        
              }
           
          }
          $Alldata['tags']=$tags;
      }


      $Alldata['content']=trim( $Alldata['content']); 
      $image="";

      if ($request->hasFile('img')){  
          $image_name=$_FILES['img']['name'];
          $request->file('img')->move(env('IMG_URL').'/crawle_image',$image_name);
          $image=env('IMG_URL1').'/crawle_image/'.$image_name;  
          $Alldata['image']=$image;
      }
      else {
          if(!array_key_exists('image', $Alldata)){
              if(array_key_exists('img1', $Alldata))
              $Alldata['image']=$Alldata['img1'];        
              else
              $Alldata['image']=$image;
          }
      }

      $sizeofCategories=sizeof($Alldata['category_id']);
      $multiContCategoryData=$Alldata['category_id'];

      $Alldata['all_category']=json_encode($Alldata['category_id']);
      $Alldata['category_id']=$sizeofCategories;      
      $ids=$this->content->create($Alldata); 

      $id=json_decode($ids,true);        
      $id=$ids['id'];
   
      
      if(!in_array(-1,$user_roles) ){           
          foreach ($user_roles as $key => $value){
              $abc['role_id']=$value;
              $abc['content_id']=$id;       
              $this->userGroup->create($abc); 
          }
      }
      else{
          $all_roles=json_decode($this->role->all(),true);
              foreach ($all_roles as $key => $value){
                  if($value['id']!=1)
                  {
                      $abc['role_id']=$value['id'];
                      $abc['content_id']=$id;       
                      $this->userGroup->create($abc); 
                  }
                }
          }

    

      if(sizeof($multiContCategoryData)){
          foreach ($multiContCategoryData as $value) {
              $abc['category_id']=$value;
              $abc['content_id']=$id;       
              $this->multiContCategory->create($abc); 
          }
      }        

      $company_name=array();
      $i=0;  
      $device_code=array(); 
      $users =json_decode(User::all(),true); 

      $role_ids=$Alldata['user_roles'];
      $final_users=array();
      
      if(!in_array(-1,$role_ids) ){  
          $user_roll=$this->role->find($role_ids);
          $all_roles=json_decode($user_roll,true);

          foreach ($all_roles as $key => $value) {
              $find[]=$value['slug'];             
          }    
          foreach ($users as $key => $value) { 
              if(in_array($value['role'], $find)){
                  $final_users[]=$value;
              }
          }
      }
      else {
          $final_users =$users; 
      }
     

      foreach ($users as $key => $value) {
          if($value['id']==$final_users[$i]['id']){
              $company_name[]=$value['company'];
              $i++;
              if($value['device_type'])
              $device_code[$value['device_type']][$value['id']]=$value['device_code'];
          }
          if($i>=sizeof($final_users))
              break;
      }

      // for ($i=0;$i<sizeof($company_name);$i++) {               
      //     $ContentCompany= new ContentCompany;
      //     $ContentCompany->content_id=$id;
      //     $ContentCompany->company_name=$company_name[$i];
      //     $ContentCompany->save();
      // }
      $message=array();


      $message['title']=$Alldata['title'];
      $message['message']=$Alldata['content'];
      if(array_key_exists('image', $Alldata))
          $message['imageUrl']=$Alldata['image'];
      else $message['imageUrl']="";
          $message['crawl_url']=$Alldata['crawl_url'];


      // Log::info($device_code);


      foreach ($device_code as $device_type=>$value) {
          if($device_type=="iphone"){
              foreach ($value as $device_iphone) {
                if($value)
                  $this->push_notificationsIOS($message,$device_iphone);
                  // Log::info("IOS");
                  // Log::info($device_iphone);

              }
          }
          else if($device_type=="android"){
              foreach ($value as  $device_andriod) {
                if($value)
                  $this->push_notifications($message,$device_andriod);              
              }
          }
      }
  

           

      return redirect()->route('admin.content.content.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('content::contents.title.contents')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Content $content
     * @return Response
     */
    public function edit(Content $content)
    {    
         $find_group_type=$this->content->find($content->id);
         $find_group_type=$find_group_type->all_users;
         $user_type=json_decode($find_group_type,true);

        $categories = $this->category->getByAttributes(['status' => 1],'priority','desc');
        $roles=json_decode($this->role->all());
          
          $user_roles[-1]['id']=-1;       
          $user_roles[-1]['type']='All';
             
         foreach ($roles as $value) { 
          if($value->name!='Admin')
          {                
          $user_roles[$value->id]['id']=$value->id;        
          $user_roles[$value->id]['type']=$value->name;
        
          }
         } 

         foreach ($user_roles as $key => $value) {
           if( sizeof($user_type) and in_array($value['id'], $user_type))
            $user_roles[$value['id']]['checked']=1;
           else $user_roles[$value['id']]['checked']=0;

         }
          // Log::info($user_roles); die;



            
        return view('content::admin.contents.edit', compact('content','categories','user_roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Content $content
     * @param  Request $request
     * @param  ContentUser $contentUser
     * @return Response
     */
    public function update(Content $content, Request $request,ContentUser $contentUser )
    { 
         $content_data=json_decode($content,true);
         $data=$request->all();   

         $data['all_users']=json_encode($data['user_roles']);
         $content_id=$content_data['id'];

         $sizeofCategories=sizeof($data['category_id']);
         $Allcategory=$data['category_id'];

         $data['all_category']=json_encode($data['category_id']);
         $data['category_id']=$sizeofCategories;      
        
         $categoryID=DB::table('content__multiplecategorycontents')                        
                          ->where('content_id','=',$content_id)->delete();

          foreach ($Allcategory as $value) {           
              $abc['category_id']=$value;
              $abc['content_id']=$content_id;
              $this->multiContCategory->create($abc);        
            }


          if ($request->hasFile('img')){  
          $image_name=$content_id.$_FILES['img']['name'];
          $request->file('img')->move(env('IMG_URL').'/crawle_image',$image_name);
          $image=env('IMG_URL1').'/crawle_image/'.$image_name;   
           }
          else {
            $image = $content->image;
           } 
         
           $request->merge(['image' => $image]);
           $data['image']=$image;

           DB::table('content__usergroups')
                ->where('content_id','=',$content_id)->delete();
            $role_ids=$data['user_roles'];
            $final_users=array();
            if(!in_array(-1,$role_ids) ){  
             $user_roll=$this->role->find($role_ids);

                $all_roles=json_decode($user_roll,true);
                // Log::info($all_roles);
                foreach ($all_roles as $key => $value) {
                   $abc['role_id']=$value['id'];
                   $abc['content_id']=$content_id;
                   $this->userGroup->create($abc);
                }    
            
            }
            else {
                 $user_roll=$this->role->all();
                 // Log::info(json_decode($user_roll,true));
                 foreach ($user_roll as $key => $value) {
                 if($value->id!=1)
                 {
                   $abc['role_id']=$value->id;
                   $abc['content_id']=$content_id;
                   $this->userGroup->create($abc);
                }
                
              }
              }
          

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
            $content_id=$_GET['id']; 
            $userData = DB::table('content__contentusers as cu')->select(\DB::raw('u.*'))
            ->join('users as u','u.id','=','cu.user_id')
            ->where('cu.content_id','=',$content_id)->get();
          $check_aray=array();
          $uncheck_array=array();
          $userData=json_decode($userData,true);
          $j=0;$k=0;
          $userId=array();
          foreach ($userData as $key => $value) {
               $userId[$key]=$value['id'];
          }
          $ch=0;
          foreach ($users as $value) {           
            if($ch<sizeof($userData) && in_array($value['id'], $userId))
            {    if($value['role'])
              {
                $check_array[$value['role']][$k]['id']=$value['id'];
                $check_array[$value['role']][$k]['name']=$value['first_name'];
                $check_array[$value['role']][$k]['role']=$value['designation'];
                $check_array[$value['role']][$k]['company']=$value['company'];
              }
               else {
                 $check_array['default'][$k]['id']=$value['id'];
                 $check_array['default'][$k]['name']=$value['first_name'];
                 $check_array['default'][$k]['role']=$value['designation'];
                 $check_array['default'][$k]['company']=$value['company'];

               }

                $k++;
                $ch++;
            }
            else {
                 if($value['role'])
                 {
                 $uncheck_array[$value['role']][$j]['id']=$value['id'];
                 $uncheck_array[$value['role']][$j]['name']=$value['first_name'];
                 $uncheck_array[$value['role']][$j]['role']=$value['designation'];
                 $uncheck_array[$value['role']][$j]['company']=$value['company'];
                 }
                 else {
                 $uncheck_array['default'][$j]['id']=$value['id'];
                 $uncheck_array['default'][$j]['name']=$value['first_name'];
                 $uncheck_array['default'][$j]['role']=$value['designation'];
                 $uncheck_array['default'][$j]['company']=$value['company'];

                 }



                  $j++;

                 } 
                    
          }
         
          $FinalArray['check']=$check_array;
          $FinalArray['uncheck']=$uncheck_array;         
           }    
        else {              
              $company_name=array();
              $k=0;
              $FinalArray=array();
              foreach ($users as $value) {
              if($value['role'])
              {
              $FinalArray[$value['role']][$k]['id']=$value['id'];
              $FinalArray[$value['role']][$k]['name']=$value['first_name'];
              $FinalArray[$value['role']][$k]['role']=$value['designation'];
              $FinalArray[$value['role']][$k]['company']=$value['company'];
              }
              else {
              $FinalArray['default'][$k]['id']=$value['id'];
              $FinalArray['default'][$k]['name']=$value['first_name'];
              $FinalArray['default'][$k]['role']=$value['designation'];
              $FinalArray['default'][$k]['company']=$value['company'];

              }
              $k++;
              }             

     }

     return $FinalArray;
     }
     public function getAllUsersInfo(Request $request)
     {
          $users =json_decode(User::all(),true);
           $FinalArray_name=array();
           $FinalArray_ids=array();
           foreach ($users as $key => $value) {
                $name=$value['first_name']." ".$value['last_name'];
                $FinalArray_name[]=$name;
                $FinalArray_ids[$name]=$value['id'];
           }
             $FinalArray['name']=$FinalArray_name;
             $FinalArray['ids']=$FinalArray_ids;
           return response()->json($FinalArray);
     }

     public function store_user_info(Request $request)
     {      
          
           $content_id=$_GET['content_id'];
           $user_id=$_GET['user_id'];   
            // echo $content_id."   ".$user_id; exit;      

           $userData = DB::table('content__contentusers')->select(\DB::raw('*'))
            ->where('content_id','=',$content_id)->get();
            $userData=json_decode($userData,true);
            $check=0;
            foreach ($userData as $userInfo) {
                 if(in_array($user_id, $userInfo))
                      $check=1;             
                     
                } 
                    if($check==0)
                    {                     
                     $ContentUser= new ContentUser;
                     $ContentUser->user_id=$user_id;
                     $ContentUser->content_id=$content_id;
                     $ContentUser->save();
                     return 200;
                   } 
                    else return 202;           
      }

      public function push_notifications($msg = array(),$registrationIds)
      {
          $API_ACCESS_KEY = env("API_ACCESS_KEY");      
       
      
        $fields = array
        (
          'registration_ids'  =>array($registrationIds),
          'data'      => $msg
        );
         
        $headers = array
        (
          'Authorization: key=' . $API_ACCESS_KEY,
          'Content-Type: application/json'
        );
        $url='https://fcm.googleapis.com/fcm/send';
         
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
          $notification['title']='ION NEWS';
          $fields = array(
            'to' => $registrationIds,
            'data' => $msg,
            'notification' => $notification
        );
         
        $headers = array
        (
          'Authorization: key=' . $API_ACCESS_KEY,
          'Content-Type: application/json'
        );
        $url='https://fcm.googleapis.com/fcm/send';
         
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
      public function push_notificationsIOS1($smg=array(),$registrationIds)
      {

        $apnsHost = env('apnsHost');
        $apnsCert = env('apnsCert');
        $apnsPort = env('apnsPort');
        $apnsPass = env('apnsPass');
        // $token =$registrationIds;
        $token ='56ed3ac2a250158cc76c33af099a0629fb41a4565923154cb0675baa468b9915';

        
        // Log::info(json_encode($smg));
        // $message=json_encode($smg);
        // $payload['aps'] = array('alert' => 'Oh hai!','badge' => 1, 'sound' => 'default');
        // $payload['acme2']='ION NEWS';
        // $output = json_encode($payload);

        $title=substr( $smg['title'],0,45);
        $message=substr($smg['message'],0,10);
        $img_url=$smg['imageUrl'];
        $crawl_url=$smg['crawl_url'];
        // $story='IBM NEWS';
        // $title='ION NEWS';
        // $url="http://assets.jpg";

$output='{
"aps": {
"alert": {
"title":"'.$title.'",
"body": "'.$message.'"
}
},
"mediaUrl": "'.$img_url.'",
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
            $all_content=$request->data;
                  
            try{
            $data=DB::table('content__contents')
            ->whereIn('id',$all_content)->delete();
                } 
            catch(Exception $e) {
                  echo 'Message: ' .$e->getMessage();
                  }
            return response($data);        
        }       


}
