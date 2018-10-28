<?php

namespace Modules\User\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Modules\User\Contracts\Authentication;
use Modules\User\Events\UserHasBegunResetProcess;
use Modules\User\Http\Requests\CreateUserRequest;
use Modules\User\Http\Requests\UpdateUserRequest;
use Modules\User\Permissions\PermissionManager;
use Modules\User\Repositories\RoleRepository;
use Modules\User\Repositories\UserRepository;
use Log;
use Mail;

class UserController extends BaseUserModuleController
{
    /**
     * @var UserRepository
     */
    private $user;
    /**
     * @var RoleRepository
     */
    private $role;
    /**
     * @var Authentication
     */
    private $auth;

    /**
     * @param PermissionManager $permissions
     * @param UserRepository    $user
     * @param RoleRepository    $role
     * @param Authentication    $auth
     */
    public function __construct(
        PermissionManager $permissions,
        UserRepository $user,
        RoleRepository $role,
        Authentication $auth
    ) {
        parent::__construct();

        $this->permissions = $permissions;
        $this->user = $user;
        $this->role = $role;
        $this->auth = $auth;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $users = $this->user->all();

        $currentUser = $this->auth->user();

        return view('user::admin.users.index', compact('users', 'currentUser'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $roles = $this->role->all();
                 // echo "<pre>";print_r(json_decode($roles)); exit;


        return view('user::admin.users.create', compact('roles'));
    }

	/**
	* Store a newly created resource in storage.
	*
	* @param  CreateUserRequest $request
	* @return Response
	*/
	public function store(CreateUserRequest $request)
	{
		$data = $this->mergeRequestWithPermissions($request);
		if($request->has('roles'))
			$data['role_id'] = $data['roles'][0];

		$this->user->createWithRoles($data, $request->roles, true);

		//register Alert Email
		$this->sendAlertEmail($data['first_name'], $data['email']);

		return redirect()->route('admin.user.user.index')->withSuccess(trans('user::messages.user created'));
	}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int      $id
     * @return Response
     */
    public function edit($id)
    {    
        if (!$user = $this->user->find($id)) {
            return redirect()->route('admin.user.user.index')
                ->withError(trans('user::messages.user not found'));
        }
        $roles = $this->role->all();

        $currentUser = $this->auth->user();

        return view('user::admin.users.edit', compact('user', 'roles', 'currentUser'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int               $id
     * @param  UpdateUserRequest $request
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $data = $this->mergeRequestWithPermissions($request);
        if($request->has('roles'))
        $data['role_id']=$data['roles'][0];
        // Log::info($data);

        $this->user->updateAndSyncRoles($id, $data, $request->roles);

        if ($request->get('button') === 'index') {
            return redirect()->route('admin.user.user.index')
                ->withSuccess(trans('user::messages.user updated'));
        }

        return redirect()->back()
            ->withSuccess(trans('user::messages.user updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int      $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->user->delete($id);

        return redirect()->route('admin.user.user.index')
            ->withSuccess(trans('user::messages.user deleted'));
    }

    public function sendResetPassword($user, Authentication $auth)
    {
        $user = $this->user->find($user);
        $code = $auth->createReminderCode($user);

        event(new UserHasBegunResetProcess($user, $code));

        return redirect()->route('admin.user.user.edit', $user->id)
            ->withSuccess(trans('user::auth.reset password email was sent'));
    }

	/**
	 * Send Alert email for new user registration
	 * @param  string $name  Name of the new user
	 * @param  string $email Email of the new user
	 * @return boolean success/failure
	 */
	public function sendAlertEmail($name, $email)
	{
		Mail::send('user::emails.registeralert', ['name' => $name, 'email' => $email], function ($message) {
			// Set the sender
			$message->from('ionnews@anionmarketing.com', 'Ion News');

			// Set the receiver and subject of the mail.
			$message->to('appal@anionmarketing.com', 'Appal')->cc('sarvesh.farshore@gmail.com', 'Sarvesh')->subject('User Register Alert');
		});

		return true;
	}

}
