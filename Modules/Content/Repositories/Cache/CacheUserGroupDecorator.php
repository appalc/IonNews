<?php

namespace Modules\Content\Repositories\Cache;

use Modules\Content\Repositories\UserGroupRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheUserGroupDecorator extends BaseCacheDecorator implements UserGroupRepository
{
    public function __construct(UserGroupRepository $usergroup)
    {
        parent::__construct();
        $this->entityName = 'user_groups';
        $this->repository = $usergroup;
    }
}
