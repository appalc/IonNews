<?php

namespace Modules\Content\Repositories\Cache;

use Modules\Content\Repositories\LayoutRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheLayoutDecorator extends BaseCacheDecorator implements LayoutRepository
{
    public function __construct(LayoutRepository $layout)
    {
        parent::__construct();
        $this->entityName = 'content.layouts';
        $this->repository = $layout;
    }
}
