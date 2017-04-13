<?php namespace Modules\Authentication\Http\Controllers\Api;


use Modules\Core\Http\Controllers\BasePublicController;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\User\Http\Requests\LoginRequest;
use Illuminate\Http\Response;
use Validator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use Modules\User\Repositories\UserRepository;
use Modules\User\Events\UserHasBegunResetProcess;
use Modules\User\Repositories\RoleRepository;
use Modules\User\Services\UserResetter;
use Modules\Services\Repositories\UsertypeRepository;
use Modules\Authentication\Events\Confirmnotify;
use Input;
use Log;
class FrontController extends BasePublicController
{
    protected $guard;
    public function __construct(Response $response,Guard $guard,UserRepository $user)
    {
       parent::__construct();
       $this->response = $response;
       $this->guard = $guard;
       $this->user = $user;
       //$this->middleware('auth:api');
      // $this->middleware('oauth');
    }
    public function login(Request $request,Client $http){
      $validator = Validator::make($request->all(), [
          'email' => 'required',
          'password' => 'required'
      ]);
      if ($validator->fails()) {
          $errors = $validator->errors();
          foreach ($errors->all() as $message) {
              $meserror =$message;
          }
          $this->response->setContent(array('message'=> $message));
        return $this->response->setStatusCode(400,$meserror);
      }else{

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];
      
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {    
         $authicated_user = Auth::user();    
           if($this->user->find($authicated_user->id)->isActivated()){
               $last_login =  $authicated_user->last_login;
               Auth::user()->last_login = new \DateTime();
               Auth::user()->save();

               $token = Auth::generateTokenById($authicated_user->id);
               $authicated_user->token=$token;



                $authicated_user=json_decode($authicated_user,true);
               $response=array();
               $response['id']=$authicated_user['id'];
               $response['email']=$authicated_user['email'];
               $response['first_name']=$authicated_user['first_name'];
               $response['last_name']=$authicated_user['last_name'];
               $response['created_at']=$authicated_user['created_at'];
               $response['updated_at']=$authicated_user['updated_at'];
               $response['phone']=$authicated_user['phone'];
               $response['address']=$authicated_user['address'];
               $response['company']=$authicated_user['company'];
               $response['designation']=$authicated_user['designation'];
               $response['role']=$authicated_user['role'];
               $response['profileImg']=$authicated_user['profileImg'];
               $response['token']=$authicated_user['token'];


               return response(json_encode($response))->header('Content-Type', 'application/json');

               // return response($authicated_user);
               // return response(['id'=>$authicated_user->id,'token' => $token,'email'=>$authicated_user->email,'last_login' =>$last_login,'first_name'=> $authicated_user->first_name,'last_name'=>$authicated_user->last_name,'create_at'=>$authicated_user->created_at,'updated_at'=>$authicated_user->updated_at,
                // 'company_name'=>$authicated_user->company,'designation'=>$authicated_user->designation,'role'=>$authicated_user->role])->header('Content-Type', 'application/json');
           }else{
               $this->response->setContent(array('message'=>'Please Activate your account'));
               return $this->response->setStatusCode(401,'Please Activate your account');
           }
         }
       }
        $this->response->setContent(array('message'=>'Email or Password is invalid'));
        return $this->response->setStatusCode(401,'Email or Password is invalid');
    }

    public function forgotpassword(Request $request){
        $validator = Validator::make($request->all(), [
          'email' => 'required|unique:users'
          ]);
        if ($validator->fails()) {
          if(isset($request->email) && $request->email){
            $user = $this->user->findByCredentials(['email' => $request->email ]);
            app(UserResetter::class)->startReset($request->all());
            return  array('message' => "successfully sent" );
          }else{
               $this->response->setContent(array('message'=>'Email id required'));
              return $this->response->setStatusCode(400,'Email id required');
          }     
        }else{
              $this->response->setContent(array('message'=>'Email id not Exists'));
            return $this->response->setStatusCode(400,'Email id not Exists');
        }
      }

    public function userDetails(Request $request){
      $authicated_user = Auth::user(); 
 
      if($authicated_user){
            return response($authicated_user)->header('Content-Type', 'application/json'); 
      }else{
           return $this->response->setStatusCode(401,'Invaid token');
      }
    }

    public function register(Request $request,RoleRepository $roles,Confirmnotify $confirm){
      $validator = Validator::make($request->all(), [
          'email' => 'required|unique:users',
          'password' => 'required',
          'phone' => 'required|unique:users|max:10|min:9',
          'first_name' => 'required|max:25',
          'role' => 'required',
          'last_name' => 'required|max:25'
      ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $meserror =$message;
            }
           $this->response->setContent(array('message'=> $message));
          return $this->response->setStatusCode(400,$meserror);
        }else{
  
          
            $role_id = '';
            $roledetails = $roles->all();
            foreach ($roledetails as $roledetail) {
                if(ucfirst($request->role) != 'Admin'){
                  if(ucfirst($request->role) == ucfirst($roledetail->name)){
                      $role_id = $roledetail->id;
                  }
                }else{
                  return $this->response->setStatusCode(400,'Not allowed as admin'); 
                }
            }
            if(!$role_id){
               return $this->response->setStatusCode(400,'Not allowed as Usertype');
            }

            $user = $this->user->createWithRoles($request->all(), $role_id,true);
            $user->role = $request->role;

        $confirm->broadcastOn($user);
        
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {    
         $authicated_user = Auth::user();    
           if($this->user->find($authicated_user->id)->isActivated()){
               $last_login =  $authicated_user->last_login;
               Auth::user()->last_login = new \DateTime();
               Auth::user()->save();               
               $user->token = Auth::generateTokenById($authicated_user->id);
          return response($user)->header('Content-Type', 'application/json');
          }
        }
      }
    }

   public function update(Request $request){
        
          $validator = Validator::make($request->all(), [        
          'first_name' => 'required|max:25',
          'last_name' => 'required|max:25',
          'company' => 'required',
          'designation' => 'required',
      ]);  
           
          if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {             

                $meserror =$message;
            }              
           $this->response->setContent(array('message'=> $message));
          return $this->response->setStatusCode(400,$meserror);
        }else { 
         

            $find_user = $this->user->find($request->user_id);

            if(isset($request->first_name) && $request->first_name){
                $first_name = $request->first_name;
            }else{
                $first_name = $find_user->first_name;
            }

            if(isset($request->last_name) && $request->last_name){
                $last_name = $request->last_name;
            }else{
                $last_name = $find_user->last_name;
            }

             if(isset($request->company) && $request->company){
                $company = $request->company;
            }else{
                $company = $find_user->company;
            }
           if(isset($request->designation) && $request->designation){
                $designation = $request->designation;
            }else{
                $designation = $find_user->designation;
            }





           
            $user_Detail = array(
                            'last_name' => $last_name,
                            'first_name' => $first_name,
                            'company'=>$company,
                            'designation'=>$designation
                          );
            $details = $this->user->update($find_user,$user_Detail);

            $response['id']=$details->id;
            $response['email']=$details->email;
            $response['first_name']=$details->first_name;
            $response['last_name']=$details->last_name;
            $response['created_at']=$details->created_at;
            $response['updated_at']=$details->updated_at;
            $response['phone']=$details->phone;
            $response['address']=$details->address;
            $response['status']=$details->status;
            $response['company']=$details->company;
            $response['designation']=$details->designation;
          
          return response($response)->header('Content-Type', 'application/json');
    } 
  }


    public function getactive(Request $request){
      $validator = Validator::make($request->all(), [
          'userId' => 'required|exists:users,id',
          'code' => 'required'
      ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $meserror =$message;
            }
            $this->response->setContent(array('message'=> $meserror));
          return $this->response->setStatusCode(400,$meserror);
        }else{
          if ($this->auth->activate($request->userId, $request->code)) {
               $this->response->setContent(array('message'=> 'verified successfully'));
             return $this->response->setStatusCode(200,'verified successfully');
          }else{
              if($this->user->find($request->userId)->isActivated()){
                   $this->response->setContent(array('message'=>'Already Activated please Login'));
                return $this->response->setStatusCode(400,'Already Activated please Login');
              }else{
                  $this->response->setContent(array('message'=> 'User id or activation code not correct'));
                return $this->response->setStatusCode(400,'there was an error with the activation');
              }
          }
        }
     }
      public function resetpassword(Request $request){
      $validator = Validator::make($request->all(), [
          'userId' => 'required|exists:users,id',
          'code' => 'required',
          'password' => 'required|min:3|confirmed',
          'password_confirmation' => 'required',
      ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            foreach ($errors->all() as $message) {
                $meserror =$message;
            }
            $this->response->setContent(array('message'=> $meserror));
          return $this->response->setStatusCode(400,$meserror);
        }else{
          try {
            app(UserResetter::class)->finishReset(
                array_merge($request->all(), ['userId' => $request->userId, 'code' => $request->code])
            );
            } catch (UserNotFoundException $e) {
                return array('message' => 'invalid code');
            } catch (InvalidOrExpiredResetCode $e) {
                return array('message' => 'invalide user or code');
            }
          return array('message' => 'successfully updated');
        }
     }
     
     public function usertypes(Request $request,UsertypeRepository $role){
        $userroles =  $role->getByAttributes(['status' => 1]);
         foreach ($userroles as $userrole) {
           $userrole->role;
         }
         $userTypes = array();
         foreach ($userroles as $usertype) {
           array_push($userTypes, ['role_id' => $usertype->role_id,'name' => $usertype->role['name']]);
         }
        return $userTypes;
     }
     public function updateuserinfo(Request $request){
       print_r($request->all());
       $data=$request->profileImg;
    $validator = Validator::make($request->all(), [
          'profileImg'=> 'required'
      ]);

               return response("hello");
     }
     public function updateProfileImg(Request $request)
     {    
     
       if(empty($_FILES))
        return response("profileImg Image required");
      else {
       $info = pathinfo($_FILES['profileImg']['name']);
       $ext = $info['extension']; 
       $filename= $info['filename']; 
// 
       $newname = $filename.".".$ext;      

         $target = env('IMG_URL')."/".$newname;
        move_uploaded_file( $_FILES['profileImg']['tmp_name'], $target);
        $target1 = env('IMG_URL1')."/".$newname;
        return response($target1);
      }


     }
}