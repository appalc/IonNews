<?php

namespace Modules\Content\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\Feedback;
use Modules\Content\Http\Requests\CreateFeedbackRequest;
use Modules\Content\Http\Requests\UpdateFeedbackRequest;
use Modules\Content\Repositories\FeedbackRepository;
use Modules\Core\Http\Controllers\BasePublicController;;
use Validator;

class FeedbackController extends BasePublicController
{
	/**
	* @var FeedbackRepository
	*/
	private $feedback;

	public function __construct(FeedbackRepository $feedback, Response $response)
	{
		parent::__construct();

		$this->feedback = $feedback;
		$this->response = $response;
	}

	/**
	* Store a newly created resource in storage.
	*
	* @param  Request $request
	* @return Response
	*/
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), ['user_id' => 'required', 'feedback' => 'required']);

		if ($validator->fails()) {
			$errors = $validator->errors();
			foreach ($errors->all() as $message) {
				$meserror = $message;
			}

			$this->response->setContent(['message' => $message]);

			return $this->response->setStatusCode(400, $meserror);
		}

		return response($this->feedback->create($request->all()) ? 'Feedback saved successfully': 'Feedback Not Saved');
	}

	public function validateApiRequest()
	{
	}

}
