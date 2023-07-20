<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Backend\UsersController;

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
Route::group(['middleware' => 'app_maintenance'], function () {
    
    // Route::get('/test',[ApiController::class,'test']);
    Route::post('/welcome', [ApiController::class, 'welcome']);
    Route::post('/checkAdminID', [ApiController::class, 'checkAdminID']);
    Route::post('/login', [ApiController::class, 'login']);
    Route::post('/forgotPassword', [ApiController::class, 'forgotPassword']);
    Route::Post('/resetPassword', [ApiController::class, 'resetPassword']);
    Route::Post('/otpSend', [ApiController::class, 'otpSend']);
    Route::Post('/otpVerify', [ApiController::class, 'otpVerify']);
    Route::Post('/razorpayXPayout', [ApiController::class, 'razorpayXPayout']);
    Route::group(['middleware' => 'api_key'], function () {
        Route::Post('/changePassword', [ApiController::class, 'changePassword']);
        Route::Post('/attendancePunch', [ApiController::class, 'attendancePunch']);
        Route::Post('/wishesList', [ApiController::class, 'wishesList']);   
        Route::Post('/addWishes', [ApiController::class, 'addWishes']);
        Route::Post('/attendanceList', [ApiController::class, 'attendanceList']);
        Route::Post('/attendanceApproval', [ApiController::class, 'attendanceApproval']);
        Route::Post('/deleteManualAttendance', [ApiController::class, 'deleteManualAttendance']);
        Route::Post('/leaveTypeLists', [ApiController::class, 'leaveTypeLists']);
        Route::Post('/leaveLists', [ApiController::class, 'leaveLists']);
        Route::Post('/addLeave', [ApiController::class, 'addLeave']);
        Route::Post('/deleteLeave', [ApiController::class, 'deleteLeave']);
        Route::Post('/leaveApproval', [ApiController::class, 'leaveApproval']);
        Route::Post('/attendanceReasons', [ApiController::class, 'attendanceReasons']);
        Route::Post('/mannualAttendanceList', [ApiController::class, 'mannualAttendanceList']);
        Route::Post('/moodTypes', [ApiController::class, 'moodTypes']);
        Route::Post('/addTodayMood', [ApiController::class, 'addTodayMood']);
        Route::Post('/celebrations', [ApiController::class, 'celebrations']);
        Route::Post('/getTodayAttendance', [ApiController::class, 'getTodayAttendance']);
        Route::Post('/manualAttendence', [ApiController::class, 'manualAttendence']);
        Route::Post('/mannualAttendanceList', [ApiController::class, 'mannualAttendanceList']);
        Route::Post('/calender', [ApiController::class, 'calender']); 
        Route::Post('/mannualAttendanceRequestList', [ApiController::class, 'mannualAttendanceRequestList']);
        // Route::Post('/announcements', [ApiController::class, 'announcements']);
        Route::Post('/addLocationTrack', [ApiController::class, 'addLocationTrack']);   
        Route::Post('/notice', [ApiController::class, 'notice']);   
        Route::Post('/addLeaveList', [ApiController::class, 'addLeaveList']);   
        Route::Post('/leaveRequestList', [ApiController::class, 'leaveRequestList']);   
        Route::Post('/salarySlipGenrate', [ApiController::class, 'salarySlipGenrate']);   
        Route::Post('/editLeave', [ApiController::class, 'editLeave']);   
        Route::Post('/editManualAttendence', [ApiController::class, 'editManualAttendence']);   
        Route::Post('/leaveBlance', [ApiController::class, 'leaveBlance']);   
        Route::Post('/attendanceLog', [ApiController::class, 'attendanceLog']);
        Route::Post('/getTeam', [ApiController::class, 'getTeam']);     
        Route::Post('/userNotificationList', [ApiController::class, 'userNotificationList']);     
        Route::Post('/viewNotification', [ApiController::class, 'viewNotification']);    
        Route::Post('/teamUserProfile', [ApiController::class, 'teamUserProfile']);  
        Route::Post('/teamUserLocation', [ApiController::class, 'teamUserLocation']);  
        Route::Post('/allUserLocation', [ApiController::class, 'allUserLocation']);  
        Route::Post('/userAttendanceListPdf', [ApiController::class, 'userAttendanceListPdf']);  
        Route::Post('/updateProfileImage', [ApiController::class, 'updateProfileImage']); 

    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
