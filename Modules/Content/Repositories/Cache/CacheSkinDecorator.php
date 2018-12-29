<?php

namespace Modules\Content\Repositories\Cache;

use Modules\Content\Repositories\SkinRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheSkinDecorator extends BaseCacheDecorator implements SkinRepository
{
    public function __construct(SkinRepository $skin)
    {
        parent::__construct();
        $this->entityName = 'content.skins';
        $this->repository = $skin;
    }
}
