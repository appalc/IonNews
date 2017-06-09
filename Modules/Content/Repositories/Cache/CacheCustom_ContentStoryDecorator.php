<?php

namespace Modules\Content\Repositories\Cache;

use Modules\Content\Repositories\Custom_ContentStoryRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheCustom_ContentStoryDecorator extends BaseCacheDecorator implements Custom_ContentStoryRepository
{
    public function __construct(Custom_ContentStoryRepository $custom_contentstory)
    {
        parent::__construct();
        $this->entityName = 'content.custom_contentstories';
        $this->repository = $custom_contentstory;
    }
}
