<?php

namespace Modules\Content\Repositories\Cache;

use Modules\Content\Repositories\MultipleCategoryContentRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheMultipleCategoryContentDecorator extends BaseCacheDecorator implements MultipleCategoryContentRepository
{
    public function __construct(MultipleCategoryContentRepository $multiplecategorycontent)
    {
        parent::__construct();
        $this->entityName = 'content.multiplecategorycontents';
        $this->repository = $multiplecategorycontent;
    }
}
