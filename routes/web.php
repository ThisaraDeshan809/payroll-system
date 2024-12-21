<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FactoryWorkerController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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
            Route::post('/save-employee','ajax_employee_save');
            Route::get('/get-employee','ajax_get_employee');
            Route::post('/edit-employee','ajax_edit_employee');
            Route::post('/delete-employee','ajax_delete_employee');
            Route::get('/view-employee','ajax_view_employee');
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

    Route::controller(AttendanceController::class)->group(function () {
        Route::prefix('employees/attendance')->group(function () {
            Route::get('/', 'attendance_page')->name('attendance.index');
            Route::post('/upload-attendance-sheet','uploadAttendance')->name('attendance.upload');
            Route::get('/get-incomplete-attendances','ajax_get_incomplete_attendance');
        });
    });

    Route::controller(SettingsController::class)->group(function () {
        Route::prefix('settings')->group(function () {
            Route::get('/', 'settings_page')->name('settings.index');
        });
    });

    Route::controller(FactoryWorkerController::class)->group(function () {
        Route::prefix('factory-workers')->group(function () {
            Route::get('/', 'index')->name('factoryWorker.index');
            Route::get('/calculate-factory-worker-salary','calculate_factory_worker_salary');
        });
    });

    Route::controller(LoanController::class)->group(function () {
        Route::prefix('employees/loans')->group(function () {
            Route::get('/', 'index')->name('loans.index');
            Route::post('save-loan','ajax_save_loan')->name('loan.save');
            Route::get('/get-loans-table','ajax_get_loans_table')->name('loan.getLoans');
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
