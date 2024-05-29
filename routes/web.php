<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardPostController;
use App\Http\Controllers\HistoryAbsenController;
use App\Http\Controllers\HistoryPayrollController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\SendSalaryController;

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
    return redirect('/login');
});





Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware('auth');

Route::get('/employee', function () {
    return view('employee.index');
})->middleware('auth');

Route::get('/dashboard/payroll', [DashboardController::class, 'index'])->middleware('auth');
Route::get('/dashboard/payroll/get-salary/{id}', [DashboardController::class, 'getSalary']);

Route::get('/dashboard/payroll/get-attendance-count/{tag}/{month}', [DashboardController::class, 'getAttendanceCount']);
Route::get('/dashboard/payroll/get-holiday-salary/{postId}', [DashboardController::class, 'getHolidaySalary']);
Route::get('/dashboard/payroll/get-late-count/{tag}/{month}', [DashboardController::class, 'getLateCount']);
Route::get('/dashboard/payroll/get-fine-value', [DashboardController::class, 'getFineValue']);
Route::get('/dashboard/payroll/get-transport', [DashboardController::class, 'getTransport']);
Route::get('/dashboard/payroll/show', [DashboardController::class, 'showPayroll'])->name('dashboard.payroll.show');
Route::post('/dashboard/payroll/show', [DashboardController::class, 'showPayrollForm'])->name('dashboard.payroll.showForm');
Route::get('/payrolls/pdf', [DashboardController::class, 'generatePDF'])->name('payrolls.pdf');

Route::middleware(['auth'])->group(function () {
    Route::resource('/dashboard/salaries', SalaryController::class);
});
Route::middleware(['auth'])->group(function () {
    Route::resource('/dashboard/send-salary', SendSalaryController::class);
    Route::post('/payroll/check-existing', [SendSalaryController::class, 'checkExisting'])->name('payroll.check_existing');
    Route::post('/dashboard/payroll/store_all', [SendSalaryController::class, 'storeAllPayrolls'])->name('payroll.store_all');
});



Route::post('/dashboard/payroll', [DashboardController::class, 'storePayroll'])->name('dashboard.payroll.store');


Route::resource('/dashboard/posts', DashboardPostController::class)->middleware('auth');
Route::resource('/dashboard/setting', SettingController::class)->middleware('auth');

Route::get('/employee/posts', [EmployeeController::class, 'index'])->name('employee.posts.index')->middleware('auth');
Route::get('/dashboard/history-absen', [HistoryAbsenController::class, 'index'])->name('dashboard.posts.index')->middleware('auth');

Route::resource('/employee/profile', ProfileController::class)->middleware('auth');

Route::middleware(['auth'])->group(function () {
    Route::get('/employee/payroll/index', [PayrollController::class, 'showForm'])->name('employee.payroll.showForm');
    Route::post('/employee/payroll/index', [PayrollController::class, 'getPayroll'])->name('employee.payroll.get');
    Route::get('/employee/payroll/data', [PayrollController::class, 'getPayrollData'])->name('employee.payroll.data');
    Route::get('/employee/payroll/generate-pdf/{id}', [PayrollController::class, 'generatePDF'])->name('employee.payroll.generate-pdf');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/history-salary', [HistoryPayrollController::class, 'showForm'])->name('dashboard.payroll.showForm');
    // Route::post('/dashboard/payroll', [HistoryPayrollController::class, 'getPayroll'])->name('dashboard.payroll.get');
    Route::get('/dashboard/payroll/data', [HistoryPayrollController::class, 'getPayrollData'])->name('dashboard.payroll.data');
    Route::get('/dashboard/payroll/generate-pdf/{id}', [HistoryPayrollController::class, 'generatePDF'])->name('dashboard.payroll.generate-pdf');
});
