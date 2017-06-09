<?php

namespace Modules\Content\Repositories\Cache;

use Modules\Content\Repositories\CustomMultiCategoryRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheCustomMultiCategoryDecorator extends BaseCacheDecorator implements CustomMultiCategoryRepository
{
    public function __construct(CustomMultiCategoryRepository $custommulticategory)
    {
        parent::__construct();
        $this->entityName = 'content.custommulticategories';
        $this->repository = $custommulticategory;
    }
}
