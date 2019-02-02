<?php

namespace Modules\Content\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Content\Entities\Feedback;
use Modules\Content\Http\Requests\CreateFeedbackRequest;
use Modules\Content\Http\Requests\UpdateFeedbackRequest;
use Modules\Content\Repositories\FeedbackRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\User\Repositories\UserRepository;

class FeedbackController extends AdminBaseController
{
    /**
     * @var FeedbackRepository
     */
    private $feedback;

    public function __construct(FeedbackRepository $feedback, UserRepository $user)
    {
        parent::__construct();

		$this->feedback = $feedback;
		$this->user     = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
		$feedback  = $this->feedback->all();
		$userNames = $this->user->all()->pluck('first_name', 'id')->toArray();

        return view('content::admin.feedback.index', compact('feedback', 'userNames'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('content::admin.feedback.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateFeedbackRequest $request
     * @return Response
     */
    public function store(CreateFeedbackRequest $request)
    {
        $this->feedback->create($request->all());

        return redirect()->route('admin.content.feedback.index')
            ->withSuccess(trans('core::core.messages.resource created', ['name' => trans('content::feedback.title.feedback')]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Feedback $feedback
     * @return Response
     */
    public function edit(Feedback $feedback)
    {
        return view('content::admin.feedback.edit', compact('feedback'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Feedback $feedback
     * @param  UpdateFeedbackRequest $request
     * @return Response
     */
    public function update(Feedback $feedback, UpdateFeedbackRequest $request)
    {
        $this->feedback->update($feedback, $request->all());

        return redirect()->route('admin.content.feedback.index')
            ->withSuccess(trans('core::core.messages.resource updated', ['name' => trans('content::feedback.title.feedback')]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Feedback $feedback
     * @return Response
     */
    public function destroy(Feedback $feedback)
    {
        $this->feedback->destroy($feedback);

        return redirect()->route('admin.content.feedback.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => trans('content::feedback.title.feedback')]));
    }
}
