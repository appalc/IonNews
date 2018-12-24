<?php

namespace Modules\Content\Repositories\Cache;

use Modules\Content\Repositories\CompanyRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheCompanyDecorator extends BaseCacheDecorator implements CompanyRepository
{
    public function __construct(CompanyRepository $company)
    {
        parent::__construct();
        $this->entityName = 'companies';
        $this->repository = $company;
    }
}
