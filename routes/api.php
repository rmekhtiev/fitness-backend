<?php

use App\Models\Role;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
 * Welcome route - link to any public API documentation here
 */
Route::get('/', function () {
    echo 'Welcome to our API';
});

/**
 * @var $api \Dingo\Api\Routing\Router
 */
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', ['middleware' => ['api']], function (Dingo\Api\Routing\Router $api) {
    /*
     * Authentication
     */
    $api->group(['prefix' => 'auth'], function (Dingo\Api\Routing\Router $api) {
        $api->group(['prefix' => 'jwt'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/token', 'App\Http\Controllers\Auth\AuthController@token');
            $api->post('/login', 'App\Http\Controllers\Auth\AuthController@login');
        });
    });

    /*
     * Authenticated routes
     */
    $api->group(['middleware' => ['api.auth']], function (Dingo\Api\Routing\Router $api) {
        /*
         * Authentication
         */
        $api->group(['prefix' => 'auth'], function ($api) {
            $api->group(['prefix' => 'jwt'], function ($api) {
                $api->get('/refresh', 'App\Http\Controllers\Auth\AuthController@refresh');
                $api->delete('/token', 'App\Http\Controllers\Auth\AuthController@logout');
            });

            $api->get('/me', 'App\Http\Controllers\Auth\AuthController@getUser');
        });

        /*
         * Users
         */
        $api->group(['prefix' => 'users', 'middleware' => 'check_role:'.Role::ROLE_OWNER.','.Role::ROLE_HALL_ADMIN], function ($api) {
            $api->get('/', 'App\Http\Controllers\UserController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\UserController@get');
            $api->post('/', 'App\Http\Controllers\UserController@post');
            $api->put('/{uuid}', 'App\Http\Controllers\UserController@put');
            $api->patch('/{uuid}', 'App\Http\Controllers\UserController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\UserController@delete');
        });

        /*
         * Roles
         */
        $api->group(['prefix' => 'roles'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\RoleController@getAll');
        });

        /**
         * Clients
         */
        $api->group(['prefix' => 'clients'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\ClientController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\ClientController@get');
            $api->post('/', 'App\Http\Controllers\ClientController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\ClientController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\ClientController@delete');
        });

        /**
         * Halls
         */
        $api->group(['prefix' => 'halls'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\HallController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\HallController@get');
            $api->post('/', 'App\Http\Controllers\HallController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\HallController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\HallController@delete');
        });

        /*
         * Employees
         */
        $api->group(['prefix' => 'employees'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\EmployeeController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\EmployeeController@get');
            $api->post('/', 'App\Http\Controllers\EmployeeController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\EmployeeController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\EmployeeController@delete');
        });
    });
});
