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
    return 'Welcome to our API';
});

Route::post('webhook', 'RequestsController@store');

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});

/**
 * @var $api \Dingo\Api\Routing\Router
 */
$api = app('Dingo\Api\Routing\Router');
$api->version('v1', ['middleware' => ['api']], function (Dingo\Api\Routing\Router $api) {

    /*
    * Trainers
    */
    $api->group(['prefix' => 'trainers'], function (Dingo\Api\Routing\Router $api) {
        $api->get('/', 'App\Http\Controllers\TrainerController@getAll');
        $api->get('/{uuid}', 'App\Http\Controllers\TrainerController@get');
        $api->post('/', 'App\Http\Controllers\TrainerController@post');
        $api->patch('/{uuid}', 'App\Http\Controllers\TrainerController@patch');
        $api->delete('/{uuid}', 'App\Http\Controllers\TrainerController@delete');

        $api->get('/{uuid}/events', 'App\Http\Controllers\TrainerController@events');
    });

    /*
     * Authentication
     */
    $api->group(['prefix' => 'auth'], function (Dingo\Api\Routing\Router $api) {
        $api->group(['prefix' => 'jwt'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/token', 'App\Http\Controllers\Auth\AuthController@token');
            $api->post('/login', 'App\Http\Controllers\Auth\AuthController@login');
        });
    });
    $api->get('clients/{uuid}/print', 'App\Http\Controllers\ClientController@printCard');

    /*
     * Authenticated routes
     */
    $api->group(['middleware' => ['api.auth']], function (Dingo\Api\Routing\Router $api) {
        /*
         * Authentication
         */
        $api->group(['prefix' => 'auth'], function (Dingo\Api\Routing\Router $api) {
            $api->group(['prefix' => 'jwt'], function (Dingo\Api\Routing\Router $api) {
                $api->get('/refresh', 'App\Http\Controllers\Auth\AuthController@refresh');
                $api->delete('/token', 'App\Http\Controllers\Auth\AuthController@logout');
            });

            $api->get('/me', 'App\Http\Controllers\Auth\AuthController@getUser');
        });

        /*
         * Users
         */
        $api->group(['prefix' => 'users', 'middleware' => 'check_role:' . Role::ROLE_OWNER . ',' . Role::ROLE_HALL_ADMIN], function (Dingo\Api\Routing\Router $api) {
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

        /*
         * Roles
         */
        $api->group(['prefix' => 'activities'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\ActivityController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\ActivityController@get');
        });

        /**
         * Clients
         */
        $api->group(['prefix' => 'clients'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\ClientController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\ClientController@get');
            $api->get('/{uuid}/qrcode', 'App\Http\Controllers\ClientController@qrcode');
            $api->post('/', 'App\Http\Controllers\ClientController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\ClientController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\ClientController@delete');
            $api->post('/{uuid}/avatar', 'App\Http\Controllers\ClientController@avatar');
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

            $api->get('/{uuid}/stats', 'App\Http\Controllers\HallStatisticsController@get');
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

        /*
         * Lockers
         */
        $api->group(['prefix' => 'lockers'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\LockerController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\LockerController@get');
            $api->post('/', 'App\Http\Controllers\LockerController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\LockerController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\LockerController@delete');
        });

        # /clients/{uuid}/claims and /lockers/{uuid}/claims
        # VS
        # /locker-claims?filter[locker_id]={uuid} and /locker-claims?filter[client_id]={uuid}

        /*
         * Locker Bookings
         */
        $api->group(['prefix' => 'locker-claims'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\LockerClaimController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\LockerClaimController@get');
            $api->post('/', 'App\Http\Controllers\LockerClaimController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\LockerClaimController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\LockerClaimController@delete');
        });

        /**
         * Issues
         */
        $api->group(['prefix' => 'issues'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\IssueController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\IssueController@get');
            $api->post('/', 'App\Http\Controllers\IssueController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\IssueController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\IssueController@delete');
        });

        /*
         * Trainers
         */
        $api->group(['prefix' => 'trainers'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\TrainerController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\TrainerController@get');
            $api->post('/', 'App\Http\Controllers\TrainerController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\TrainerController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\TrainerController@delete');
        });

        /*
         * Groups
         */
        $api->group(['prefix' => 'groups'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\GroupController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\GroupController@get');
            $api->post('/', 'App\Http\Controllers\GroupController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\GroupController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\GroupController@delete');

            $api->get('/{uuid}/events', 'App\Http\Controllers\GroupController@events');

            $api->get('/{parentUuid}/clients', 'App\Http\Controllers\GroupClientController@getAll');
            $api->put('/{parentUuid}/clients/{uuid}', 'App\Http\Controllers\GroupClientController@put');
            $api->delete('/{parentUuid}/clients/{uuid}', 'App\Http\Controllers\GroupClientController@delete');
        });

        /**
         * Schedules
         */
        $api->group(['prefix' => 'schedules'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\ScheduleController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\ScheduleController@get');
            $api->post('/', 'App\Http\Controllers\ScheduleController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\ScheduleController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\ScheduleController@delete');
        });

        /*
         * Subscriptions
        */
        $api->group(['prefix' => 'subscriptions'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\SubscriptionController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\SubscriptionController@get');
            $api->post('/', 'App\Http\Controllers\SubscriptionController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\SubscriptionController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\SubscriptionController@delete');
            $api->post('/{uuid}/sell', 'App\Http\Controllers\SubscriptionController@sell');

        });

        /*
         * BarItems
         */
        $api->group(['prefix' => 'bar-items'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\BarItemController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\BarItemController@get');
            $api->post('/', 'App\Http\Controllers\BarItemController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\BarItemController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\BarItemController@delete');

            $api->post('/{uuid}/sell', 'App\Http\Controllers\BarItemController@sell');
        });

        /*
         * Payments
         */
        $api->group(['prefix' => 'payments'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\PaymentController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\PaymentController@get');
        });

        /*
        * IssueDiscussions
        */
        $api->group(['prefix' => 'issue-discussions'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\IssueDiscussionController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\IssueDiscussionController@get');
            $api->post('/', 'App\Http\Controllers\IssueDiscussionController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\IssueDiscussionController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\IssueDiscussionController@delete');
        });


        /*
        * VisitHistoryRecords
        */
        $api->group(['prefix' => 'visit-history-records'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\VisitHistoryRecordController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\VisitHistoryRecordController@get');
            $api->post('/', 'App\Http\Controllers\VisitHistoryRecordController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\VisitHistoryRecordController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\VisitHistoryRecordController@delete');
        });

        /*
        * Requests
        */
        $api->group(['prefix' => 'requests'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\RequestsController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\RequestsController@get');
            $api->post('/', 'App\Http\Controllers\RequestsController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\RequestsController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\RequestsController@delete');
        });

        /*
        * Identifiers
        */
        $api->group(['prefix' => 'identifiers'], function ($api) {
            $api->get('/', 'App\Http\Controllers\IdentifierController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\IdentifierController@get');
            $api->post('/', 'App\Http\Controllers\IdentifierController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\IdentifierController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\IdentifierController@delete');
        });

        /*
         * TrainingSessions
         */
        $api->group(['prefix' => 'training-sessions'], function (Dingo\Api\Routing\Router $api) {
            $api->get('/', 'App\Http\Controllers\TrainingSessionController@getAll');
            $api->get('/{uuid}', 'App\Http\Controllers\TrainingSessionController@get');
            $api->post('/', 'App\Http\Controllers\TrainingSessionController@post');
            $api->patch('/{uuid}', 'App\Http\Controllers\TrainingSessionController@patch');
            $api->delete('/{uuid}', 'App\Http\Controllers\TrainingSessionController@delete');
            $api->post('/{uuid}/sell', 'App\Http\Controllers\TrainingSessionController@sell');
        });
    });
});
