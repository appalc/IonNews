<?php

namespace Modules\Content\Repositories\Cache;

use Modules\Content\Repositories\ContentLikeStoryRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheContentLikeStoryDecorator extends BaseCacheDecorator implements ContentLikeStoryRepository
{
    public function __construct(ContentLikeStoryRepository $contentlikestory)
    {
        parent::__construct();
        $this->entityName = 'content.contentlikestories';
        $this->repository = $contentlikestory;
    }
}
