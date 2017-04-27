<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/story'], function (Router $router) {
Route::group(['middleware' => 'cors'], function(Router $router){
	 $router->get('/list', [
        'as' => 'StoryController.api.story',
        'uses' => 'StoryController@story',
        'middleware' => 'auth:api'
    ]);

      $router->get('/getAllLikeStory', [
        'as' => 'StoryController.api.getAllLikeStory',
        'uses' => 'StoryController@getAllLikeStory',
        'middleware' => 'auth:api'
    ]);

     $router->get('/homepage', [
        'as' => 'StoryController.api.homepage',
        'uses' => 'StoryController@homepage',
        'middleware' => 'auth:api'
    ]);

    $router->POST('/story_like', [
        'as' => 'StoryController.api.story_like',
        'uses' => 'StoryController@story_like',
        'middleware' => 'auth:api'
    ]);
     
});
});

$router->group(['prefix' =>'/category'], function (Router $router) {
Route::group(['middleware' => 'cors'], function(Router $router){
     $router->get('/list', [
        'as' => 'CategoryController.api.categorylist',
        'uses' => 'CategoryController@categorylist'
    ]);

});
});

		
