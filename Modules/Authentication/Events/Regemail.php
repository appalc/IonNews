<?php
namespace Modules\Authentication\Events;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\User\Entities\UserInterface;

class Regemail extends Mailable implements ShouldQueue
{
	use Queueable, SerializesModels;

	/**
	* @var UserInterface
	*/
	public $user;
	public $order;

	/**
	* @var
	*/
	public $msg;

	// public $subject = 'Order placed in doober.';
	public function __construct(UserInterface $user, $msg)
	{
		$this->user    = $user;
		$this->msg     = $msg;
		$this->subject = 'Welcome to ION News ';
	}
	
	/**
	* Build the message.
	*
	* @return $this
	*/
	public function build()
	{
		return $this->view('authentication::email');
	}
}
