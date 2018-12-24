<?php

namespace Modules\Content\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;

class ContentServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
    }

    public function boot()
    {
        $this->publishConfig('content', 'permissions');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(
            'Modules\Content\Repositories\ContentRepository',
            function () {
                $repository = new \Modules\Content\Repositories\Eloquent\EloquentContentRepository(new \Modules\Content\Entities\Content());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Content\Repositories\Cache\CacheContentDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Content\Repositories\CategoryRepository',
            function () {
                $repository = new \Modules\Content\Repositories\Eloquent\EloquentCategoryRepository(new \Modules\Content\Entities\Category());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Content\Repositories\Cache\CacheCategoryDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Content\Repositories\ContentImagesRepository',
            function () {
                $repository = new \Modules\Content\Repositories\Eloquent\EloquentContentImagesRepository(new \Modules\Content\Entities\ContentImages());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Content\Repositories\Cache\CacheContentImagesDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Content\Repositories\ContentUserRepository',
            function () {
                $repository = new \Modules\Content\Repositories\Eloquent\EloquentContentUserRepository(new \Modules\Content\Entities\ContentUser());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Content\Repositories\Cache\CacheContentUserDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Content\Repositories\ContentCompanyRepository',
            function () {
                $repository = new \Modules\Content\Repositories\Eloquent\EloquentContentCompanyRepository(new \Modules\Content\Entities\ContentCompany());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Content\Repositories\Cache\CacheContentCompanyDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Content\Repositories\ContentLikeStoryRepository',
            function () {
                $repository = new \Modules\Content\Repositories\Eloquent\EloquentContentLikeStoryRepository(new \Modules\Content\Entities\ContentLikeStory());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Content\Repositories\Cache\CacheContentLikeStoryDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Content\Repositories\MultipleCategoryContentRepository',
            function () {
                $repository = new \Modules\Content\Repositories\Eloquent\EloquentMultipleCategoryContentRepository(new \Modules\Content\Entities\MultipleCategoryContent());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Content\Repositories\Cache\CacheMultipleCategoryContentDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Content\Repositories\Custom_ContentStoryRepository',
            function () {
                $repository = new \Modules\Content\Repositories\Eloquent\EloquentCustom_ContentStoryRepository(new \Modules\Content\Entities\Custom_ContentStory());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Content\Repositories\Cache\CacheCustom_ContentStoryDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Content\Repositories\CustomMultiCategoryRepository',
            function () {
                $repository = new \Modules\Content\Repositories\Eloquent\EloquentCustomMultiCategoryRepository(new \Modules\Content\Entities\CustomMultiCategory());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Content\Repositories\Cache\CacheCustomMultiCategoryDecorator($repository);
            }
        );

		$this->app->bind(
			'Modules\Content\Repositories\CompanyRepository',
			function () {
				$repository = new \Modules\Content\Repositories\Eloquent\EloquentCompanyRepository(new \Modules\Content\Entities\Company());

				if (! config('app.cache')) {
					return $repository;
				}

				return new \Modules\Content\Repositories\Cache\CacheCompanyDecorator($repository);
			}
		);

		$this->app->bind(
			'Modules\Content\Repositories\CompanyGroupRepository',
			function () {
				$repository = new \Modules\Content\Repositories\Eloquent\EloquentCompanyGroupRepository(new \Modules\Content\Entities\CompanyGroup());

				if (! config('app.cache')) {
					return $repository;
				}

				return new \Modules\Content\Repositories\Cache\CacheCompanyGroupDecorator($repository);
			}
		);

		$this->app->bind(
			'Modules\Content\Repositories\UserGroupRepository',
			function () {
				$repository = new \Modules\Content\Repositories\Eloquent\EloquentUserGroupRepository(new \Modules\Content\Entities\UserGroup());

				if (! config('app.cache')) {
					return $repository;
				}

				return new \Modules\Content\Repositories\Cache\CacheUserGroupDecorator($repository);
			}
		);

// add bindings










    }
}
