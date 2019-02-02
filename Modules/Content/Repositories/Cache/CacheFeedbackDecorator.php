<?php

namespace Modules\Content\Repositories\Cache;

use Modules\Content\Repositories\FeedbackRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheFeedbackDecorator extends BaseCacheDecorator implements FeedbackRepository
{
    public function __construct(FeedbackRepository $feedback)
    {
        parent::__construct();
        $this->entityName = 'content.feedback';
        $this->repository = $feedback;
    }
}
