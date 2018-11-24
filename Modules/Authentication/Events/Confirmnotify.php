<?php

namespace Modules\Authentication\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Mail\Mailer;
use Modules\Authentication\Events\Regemail;

class Confirmnotify
{
	use SerializesModels;

	/**
	* Create a new event instance.
	*
	* @return void
	*/
	public $user;

	public function __construct(Mailer $mailer)
	{
		//$this->user = $user;
		$this->mailer = $mailer;
	}

	/**
	* Get the channels the event should be broadcast on.
	*
	* @return array
	*/
	public function broadcastOn($user)
	{
		return $this->mailer->to($user->email)->send(new Regemail($user, 'Successfully registered with Ion News'));
	}
}
