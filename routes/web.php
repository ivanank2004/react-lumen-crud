<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () {
    return response()->json(['message' => 'Hello from Lumen!']);
});


// register
$router->post("api/register","LoginController@register");

//login
$router->post("api/login","LoginController@login");
$router->post("api/logout","LoginController@logout");

$router->group( ['prefix' => 'api', 'middleware' => 'auth'], function() use ($router){
    $router->get('notes','NoteController@index');
    $router->get('notes/{id}','NoteController@show');
    $router->post('notes','NoteController@create');
    $router->delete('notes/{id}', 'NoteController@destroy');
});
