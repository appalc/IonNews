<?php

namespace Modules\Content\Repositories\Cache;

use Modules\Content\Repositories\PreferenceRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CachePreferenceDecorator extends BaseCacheDecorator implements PreferenceRepository
{
	public function __construct(PreferenceRepository $preference)
    {
		parent            ::__construct();
		$this->entityName = 'content.preferences';
		$this->repository = $preference;
	}
}
