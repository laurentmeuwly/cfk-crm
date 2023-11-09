<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\Http\Controllers\JsonApiController;
use App\Http\Controllers\Api\V2\ContactController;

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

Route::middleware('auth:sanctum')->group(function() {
    JsonApiRoute::server('v1')
        ->prefix('v1')
        ->resources(function ($server) {
            $server->resource('contacts', JsonApiController::class)
            ->relationships(function ($relationships) {
			    $relationships->hasOne('title');
                $relationships->hasOne('source');
			});

            $server->resource('titles', JsonApiController::class)->readOnly();;
            $server->resource('sources', JsonApiController::class)->readOnly();;
    });

    JsonApiRoute::server('v2')
        ->prefix('v2')
        ->resources(function ($server) {
            $server->resource('contacts', JsonApiController::class)
            ->relationships(function ($relationships) {
                $relationships->hasOne('title');
                $relationships->hasOne('source');
			});

            $server->resource('webforms', JsonApiController::class)
            ->relationships(function ($relationships) {
                $relationships->hasOne('title');
                $relationships->hasOne('source');
			});

            $server->resource('titles', JsonApiController::class)->readOnly();;
            $server->resource('sources', JsonApiController::class)->readOnly();;
    });

});

