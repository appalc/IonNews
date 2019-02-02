<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' => '/story'], function (Router $router) {
	Route::group(['middleware' => 'cors'], function (Router $router) {
		$router->get('/list', [
			'as'         => 'StoryController.api.story',
			'uses'       => 'StoryController@story',
			'middleware' => 'auth:api',
		]);

		$router->get('/getAllLikeStory', [
			'as'         => 'StoryController.api.getAllLikeStory',
			'uses'       => 'StoryController@getAllLikeStory',
			'middleware' => 'auth:api',
		]);

		$router->get('/homepage', [
			'as'         => 'StoryController.api.homepage',
			'uses'       => 'StoryController@homepage',
			'middleware' => 'auth:api',
		]);

		$router->get('/updateDatabase', [
			'as'            => 'StoryController.api.updateDatabase',
			'uses'          => 'StoryController@updateDatabase',
			// 'middleware' => 'auth:api',
		]);

		$router->get('/move_to_archive', [
			'as'            => 'StoryController.api.move_to_archive',
			'uses'          => 'StoryController@move_to_archive',
			// 'middleware' => 'auth:api',
		]);

		$router->POST('/story_like', [
			'as'         => 'StoryController.api.story_like',
			'uses'       => 'StoryController@story_like',
			'middleware' => 'auth:api',
		]);
	});
});

$router->group(['prefix' => '/category'], function (Router $router) {
	Route::group(['middleware' => 'cors'], function(Router $router){
		$router->get('/list', [
			'as'   => 'CategoryController.api.categorylist',
			'uses' => 'CategoryController@categorylist',
		]);

		$router->get('/user_group', [
			'as'   => 'CategoryController.api.user_group',
			'uses' => 'CategoryController@getUserGroup',
		]);
	});
});

$router->group(['prefix' => '/search'], function (Router $router) {
	Route::group(['middleware' => 'cors'], function(Router $router){
		$router->get('/categoryAndTaglist', [
			'as'         => 'SearchController.api.categoryAndTaglist',
			'uses'       => 'SearchController@categoryAndTaglist',
			'middleware' => 'auth:api',
		]);

		$router->POST('/storyByTag', [
			'as'         => 'SearchController.api.storyByTag',
			'uses'       => 'SearchController@storyByTag',
			'middleware' => 'auth:api',
		]);
	});
});

$router->group(['prefix' => '/content'], function (Router $router) {
	Route::group(['middleware' => 'cors'], function(Router $router){
		$router->POST('/createStory', [
			'as'   => 'ContentController.api.createStory',
			'uses' => 'ContentController@createStory',
		]);
	});
});

$router->group(['prefix' => '/feedback'], function (Router $router) {
	Route::group(['middleware' => 'cors'], function(Router $router){
		$router->POST('/add', [
			'as'         => 'FeedbackController.api.store',
			'uses'       => 'FeedbackController@store',
			'middleware' => 'auth:api',
		]);
	});
});
