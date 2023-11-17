<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/add-person', function () {
    return view('person-details', ['add_new' => true]);
});

Route::get('/edit-person/{id}', function (int $id) {
    return view('person-details', ['add_new' => false, 'person_id' => $id]);
});
