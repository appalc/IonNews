<?php

use Illuminate\Routing\Router;

Route::group(['middleware' => 'cors'], function(Router $router){

    $router->get('questions', [
        'as' => 'api.get.questions',
        'uses' => 'QuestionsApiController@index',
        'middleware' => 'auth'
    ]);

    $router->post('vote', [
        'as' => 'api.post.vote',
        'uses' => 'QuestionsApiController@vote',
        'middleware' => 'auth'
    ]);

    $router->post('comment', [
        'as' => 'api.post.comment',
        'uses' => 'CommentsApiController@comment',
        'middleware' => 'auth'
    ]);

    $router->get('comment/{question_id}', [
        'as' => 'api.get.comment',
        'uses' => 'CommentsApiController@getcomment'
    ]);

    $router->get('questions/category/{category}', [
        'as' => 'api.get.questions.category',
        'uses' => 'QuestionsApiController@categoryQuestions'
    ]);

    $router->get('category/list', [
        'as' => 'api.get.category.list',
        'uses' => 'QuestionsApiController@listOfCategories'
    ]);

    $router->post('questions/create', [
        'as' => 'api.post.user.question',
        'uses' => 'QuestionsApiController@create',
        'middleware' => 'auth'
    ]);

    $router->get('questions/myquestions', [
        'as' => 'api.get.questions.myquestions',
        'uses' => 'QuestionsApiController@myQuestions',
        'middleware' => 'auth'
    ]);

});