<?php

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

Route::apiResource('users', 'UserController');
Route::apiResource('accounts', 'AccountController');
Route::apiResource('transactions', 'TransactionController');

Route::get('/users/search/{api_token}', 'UserController@show');
Route::get('/users/accounts_user/{api_token}', 'UserController@accounts_user');

Route::post('/accounts/amount_account', 'AccountController@amount_account');
Route::post('/accounts/withdraw_credit_account', 'AccountController@withdraw_credit_account');
Route::post('/accounts/withdraw_account', 'AccountController@withdraw_account');
Route::post('/accounts/pay_account_credit', 'AccountController@pay_account_credit');
Route::post('/accounts/deposit_account', 'AccountController@deposit_account');

Route::post('/transactions/by_user', 'TransactionController@by_user');
Route::post('/transactions/by_account', 'TransactionController@by_account');


