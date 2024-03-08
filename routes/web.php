<?php

use App\Http\Controllers\ShortLinkController;
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

Route::get('/', [ShortLinkController::class, 'index']);

Route::post('/', [ShortLinkController::class, 'store']);

Route::get('/redirect/{slug}', [ShortLinkController::class, 'redirect', ['slug' => 'slug']]);
