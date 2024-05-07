<?php

use App\Http\Controllers\BackendController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ProfileController;
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
    return view('welcome');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [BackendController::class, 'index'])->name('dashboard');

//google login route
Route::get('login/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);

//facebook login route
Route::get('login/facebook', [App\Http\Controllers\Auth\LoginController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('login/facebook/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleFacebookCallback']);



//Normal Users Routes List
Route::middleware(['auth', 'user-access:user'])->group(function () {
    Route::get('/leave/index', [LeaveController::class, 'index'])->name('leave.index');
    Route::get('/leave/request', [LeaveController::class, 'request'])->name('leave.request');
    Route::post('/leave/adddata', [LeaveController::class, 'adddata'])->name('leaveAddData');
    Route::post('/leave/editdata', [LeaveController::class, 'editdata'])->name('leaveEditData');
    Route::post('/user/leave/user-delete-leaves', [LeaveController::class, 'user_delete_leaves'])->name('userDeleteLeaves');
    Route::post('/seen-user/{id}', [LeaveController::class, 'seen_user'])->name('seenUser');

  
});
Route::get('/home', [HomeController::class, 'index'])->name('home');
//Admin Routes List
Route::middleware(['auth', 'user-access:admin'])->group(function () {

    Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');
    Route::get('/user-management', [BackendController::class, 'user_management'])->name('user-management');
    Route::post('/adduser', [BackendController::class, 'addUser'])->name('addUser');
    Route::post('/edituser', [BackendController::class, 'editUser'])->name('editUser');
    Route::delete('/deleteuser/{id}', [BackendController::class, 'deleteUser'])->name('deleteUser');
    Route::post('/statususer/{id}', [BackendController::class, 'statusUser'])->name('statusUser');
    // อนุมัติการลา
    Route::get('/admin/leave/listdata', [LeaveController::class, 'listdata'])->name('admin.leaves.listdata');
    Route::post('/admin/leave/approved', [LeaveController::class, 'approved'])->name('adminLeavesApproved');
    Route::post('/admin/leave/admin-comment', [LeaveController::class, 'admin_comment'])->name('adminComment');
    Route::post('/admin/leave/admin-delete-leaves', [LeaveController::class, 'admin_delete_leaves'])->name('adminDeleteLeaves');
   
    Route::get('/leave-type', [LeaveController::class, 'leave_type'])->name('leaveType');
    Route::get('/log-login', [LeaveController::class, 'log_login'])->name('logLogin');


    //แจ้งเตือน
    Route::post('/seen-admin/{id}', [LeaveController::class, 'seen_admin'])->name('seenAdmin');
});

Route::get('/noti', [LeaveController::class, 'noti'])->name('noti');
Route::get('/seen-noti', [LeaveController::class, 'seen_noti'])->name('seenNoti');
Route::get('/seen-noti-user', [LeaveController::class, 'seen_noti_user'])->name('seenNotiUser');

// profile
Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
Route::post('/edit-name-profile', [ProfileController::class, 'edit_name_profile'])->name('editNameProfile');
Route::post('/edit-pass-profile', [ProfileController::class, 'edit_pass_profile'])->name('editPassProfile');
Route::post('/edit-detail-profile', [ProfileController::class, 'edit_detail_profile'])->name('editDetailProfile');
Route::post('/edit-img-profile', [ProfileController::class, 'edit_img_profile'])->name('editImgProfile');

Route::delete('/delete-img-profile', [ProfileController::class, 'delete_img_profile'])->name('deleteImgProfile');
Route::delete('/delete-user-profile', [ProfileController::class, 'delete_user_profile'])->name('deleteUserProfile');

//Admin Routes List

Route::middleware(['auth', 'user-access:manager'])->group(function () {

    Route::get('/manager/home', [HomeController::class, 'managerHome'])->name('manager.home');
});
