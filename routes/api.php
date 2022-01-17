<?php

use App\Http\Controllers\Api\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use CloudCreativity\LaravelJsonApi\Facades\JsonApi;
use CloudCreativity\LaravelJsonApi\Routing\RouteRegistrar as Api;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


JsonApi::register('v1')
    //->withNamespace('Api')
    //->withNamespace('App\Http\Controllers\Api')
    //->defaultController('ContactController')
    ->singularControllers()->routes(function (Api $api) {
        
        $api->resource('contacts')->relationShips(function ($relations) {
            //$relations->hasOne('title')->readOnly();
        });    
});

/*
Route::group(['prefix' => 'v1', 'middleware' => 'api'], function () {
    Route::get('contacts', [ContactController::class, 'index']);
    //Route::get('contacts/{contact}', 'ContactController@show');
    Route::delete('contacts/{contact}', [ContactController::class, 'delete']);
    Route::post('contacts', [ContactController::class, 'store']);
});
*/