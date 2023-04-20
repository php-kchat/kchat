<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\KchatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DashboardController;

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

Route::group(['middleware' => ['GoIn']],function(){
	
	Route::get('/login', function () { return view('login'); });

	Route::get('/sign-on', function () { return view('signon'); });

	Route::post('/login', [AuthController::class, 'login'])->name('Login');
	
	Route::post('/sign-on', [AuthController::class, 'signon'])->name('Sign-On');

});

Route::group(['middleware' => ['CheckLogin']],function(){
    
    //Route::group(['middleware' => ['CheckRole']],function(){
    //Route::prefix('admin')->name('admin.')->middleware(['CheckRole'])->group(function () {
        
        Route::group(['middleware' => ['GetCounts']],function(){
        
            Route::get('/members', [UserController::class, 'members'])->name('Members List');

            Route::get('/activity', [ActivityController::class, 'activity'])->name('Activities');
            
            Route::get('/notification', [NotificationController::class, 'notification'])->name('Notification\'s');

            Route::get('/settings', [SettingController::class, 'Setting'])->name('Setting\'s');
            
            Route::get('/profile', [UserController::class, 'profile'])->name('Profile');
            
            Route::get('/', [DashboardController::class, 'index'])->name('Dashboard');
            
            Route::get('/messages', [MessageController::class, 'messages'])->name('Messages Controller');
            
        });
        
        Route::post('/logout', [AuthController::class, 'logout'])->name('Logout');
        
        Route::post('/members/delete_users', [UserController::class, 'delete_users'])->name('Delete Members');
        
        Route::post('/members/set_inactive_users', [UserController::class, 'set_inactive_users'])->name('Set Inactive Members');
        
        Route::post('/members/set_active_users', [UserController::class, 'set_active_users'])->name('Set Active Members');
        
        Route::post('/members/block_users', [UserController::class, 'block_users'])->name('Block Members');
        
        Route::post('/members/unblock_users', [UserController::class, 'unblock_users'])->name('Unblock Members');
        
        Route::post('/members/newconversation', [UserController::class, 'NewConversation'])->name('New Conversation');
        
        Route::post('/profile', [UserController::class, 'SaveProfile'])->name('Save Profile');
        
        Route::post('/setting/savedpt', [SettingController::class, 'AddDepartment'])->name('Add Department');
        
        Route::post('/setting/timezone', [SettingController::class, 'TimeZone'])->name('TimeZone');
        
        Route::post('/setting/deletedpt', [SettingController::class, 'DeleteDepartment'])->name('Delete Department');

        Route::post('/activity/delete', [ActivityController::class, 'delete'])->name('Delete Activities');
        
        Route::post('/notification/delete', [NotificationController::class, 'delete'])->name('Delete Notification\'s');
        
        Route::post('/messages', [KchatController::class, 'kchat'])->name('All Json Responses');
        
        Route::post('/messages/update', [MessageController::class, 'UpdateConversation'])->name('Update Conversation');
        
        Route::post('/getConvo', [KchatController::class, 'getConvo'])->name('get Conversations list via search');
            
        Route::post('/ajax_members', [UserController::class, 'members_ajax'])->name('Members List on Ajax call');

    //});
    
});