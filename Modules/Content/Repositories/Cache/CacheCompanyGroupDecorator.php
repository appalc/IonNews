<?php

namespace Modules\Content\Repositories\Cache;

use Modules\Content\Repositories\CompanyGroupRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheCompanyGroupDecorator extends BaseCacheDecorator implements CompanyGroupRepository
{
    public function __construct(CompanyGroupRepository $companygroup)
    {
        parent::__construct();
        $this->entityName = 'company_groups';
        $this->repository = $companygroup;
    }
}
