<?php

namespace Modules\Content\Repositories\Cache;

use Modules\Content\Repositories\ContentUserRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheContentUserDecorator extends BaseCacheDecorator implements ContentUserRepository
{
    public function __construct(ContentUserRepository $contentuser)
    {
        parent::__construct();
        $this->entityName = 'content.contentusers';
        $this->repository = $contentuser;
    }
}
