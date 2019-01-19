<?php

namespace Modules\Content\Repositories\Cache;

use Modules\Content\Repositories\StoryCategoryRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheStoryCategoryDecorator extends BaseCacheDecorator implements StoryCategoryRepository
{
	public function __construct(StoryCategoryRepository $storycategory)
	{
		parent::__construct();
		$this->entityName = 'content.storycategories';
		$this->repository = $storycategory;
	}
}
