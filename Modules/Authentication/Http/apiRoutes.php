<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->group(['prefix' =>'/authentication'], function (Router $router) {
Route::group(['middleware' => 'cors'], function(Router $router){
	 $router->post('/login', [
        'as' => 'authentication.api.login',
        'uses' => 'FrontController@login'
    ]);
	 $router->get('/user', [
        'as' => 'authentication.api.userDetails',
        'uses' => 'FrontController@userDetails',
        'middleware' => 'auth:api'
    ]);
     $router->post('/register', [
        'as' => 'authentication.api.register',
        'uses' => 'FrontController@register'
    ]);
     $router->post('/forgot', [
        'as' => 'authentication.api.forgotpassword',
        'uses' => 'FrontController@forgotpassword'
    ]);
     $router->post('/updateuserinfo', [
        'as' => 'authentication.api.updateuserinfo',
        'uses' => 'FrontController@updateuserinfo',
        'middleware' => 'auth:api'
    ]);
     $router->post('/push_notifications',[
        'as'=>'authentication.api.push_notifications',
        'uses'=>'FrontController@push_notifications',
        // 'middleware'=>'auth.api'

        ]);
    $router->post('/getactive', [
        'as' => 'authentication.api.getactive',
        'uses' => 'FrontController@getactive'
    ]);
     $router->post('/registerfamily', [
        'as' => 'contact.api.registerfamily',
        'uses' => 'FrontController@registerfamily',
        'middleware' => 'auth:api'
    ]);
    $router->post('/removemember', [
        'as' => 'contact.api.removemember',
        'uses' => 'FrontController@removemember',
        'middleware' => 'auth:api'
    ]);
    $router->post('/update', [
        'as' => 'contact.api.update',
        'uses' => 'FrontController@update',
        'middleware' => 'auth:api'
    ]);
    $router->post('/updateProfileImg',[
         'as'=>'authentication.api.updateprofileImg',
         'uses'=>'FrontController@updateprofileImg',
         'middleware'=>'auth:api'

         ]);

    $router->post('/reset', [
        'as' => 'contact.api.resetpassword',
        'uses' => 'FrontController@resetpassword',
        'middleware' => 'auth:api'
    ]);
});
});
		
