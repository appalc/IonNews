<?php

namespace Modules\Content\Repositories\Cache;

use Modules\Content\Repositories\UserLikedStoryRepository;
use Modules\Core\Repositories\Cache\BaseCacheDecorator;

class CacheUserLikedStoryDecorator extends BaseCacheDecorator implements UserLikedStoryRepository
{

	public function __construct(UserLikedStoryRepository $userLikedStory)
	{
		parent::__construct();

		$this->entityName = 'content.userlikedstories';
		$this->repository = $contentlikestory;
	}

}
