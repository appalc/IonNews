<?php

namespace Modules\Content\Repositories\Cache;

use Modules\Content\Repositories\ContentCompanyRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheContentCompanyDecorator extends BaseCacheDecorator implements ContentCompanyRepository
{
    public function __construct(ContentCompanyRepository $contentcompany)
    {
        parent::__construct();
        $this->entityName = 'content.contentcompanies';
        $this->repository = $contentcompany;
    }
}
