<?php

use App\Http\Controllers\HelloWorldController;
use App\Http\Controllers\Mail\MailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

// require __DIR__.'/auth.php';
Route::get('/hello', [HelloWorldController::class, 'index']);

//Mail
Route::get('/test-email', [MailController::class, 'sendTestEmail']);
