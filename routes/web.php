<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use Illuminate\Support\Facades\Artisan;
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



Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'show')->name('login');
    Route::post('/login', 'login')->name('login.submit');
});

Route::group(['middleware' => ['auth']], function () {
    Route::controller(EmployeeController::class)->group(function () {
        Route::prefix('employees')->group(function () {
            Route::get('/', 'index')->name('employee.index');
            Route::post('/upload-excel','uploadExcel')->name('employee.upload');
            Route::get('/get-employees','ajax_get_employees')->name('employee.get');
        });
    });

    Route::controller(BusinessController::class)->group(function () {
        Route::prefix('business')->group(function () {
            Route::get('/', 'index')->name('business.index');
            Route::post('/save-business','ajax_save_business');
            Route::get('/get-businesses','ajax_get_businesses');
            Route::get('/get-business','ajax_get_business');
            Route::post('/edit-business','ajax_edit_business');
            Route::post('/delete-business','ajax_delete_business');
        });
    });

    Route::controller(LocationController::class)->group(function () {
        Route::prefix('locations')->group(function () {
            Route::get('/', 'index')->name('locations.index');
            Route::get('/get-locations','ajax_get_locations');
            Route::post('/save-location','ajax_save_location');
            Route::get('/get-location','ajax_get_location');
            Route::post('/edit-location','ajax_edit_location');
            Route::post('/delete-location','ajax_delete_location');
        });
    });

    Route::controller(LogoutController::class)->group(function () {
        Route::get('/logout', 'perform')->name('logout');
    });
    Route::get('/', function () {
        return view('pages.home');
    });
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    echo "Cleared";
});
