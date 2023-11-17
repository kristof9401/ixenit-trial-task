<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/add-new-person', 'App\Http\Controllers\PersonController@savePersonData');

Route::post('/edit-person', 'App\Http\Controllers\PersonController@savePersonData');

Route::get('/get-persons', 'App\Http\Controllers\PersonController@getPersons');

Route::delete('/delete-person/{id}', 'App\Http\Controllers\PersonController@deletePerson');

Route::get('/person-details/{id}', 'App\Http\Controllers\PersonController@getPersonDetails');
