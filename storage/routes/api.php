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

// Route::get('/test',[ApiController::class,'test']);
Route::post('/welcome',[ApiController::class,'welcome']);
Route::post('/customer-register',[ApiController::class,'CustomerRegister']);

Route::post('/otpSend',[ApiController::class,'otpSend']);
 Route::Post('/customerLogin',[ApiController::class,'customerLogin']);
 Route::Post('/astrologerLogin',[ApiController::class,'astrologerLogin']);
 Route::Post('/getAllAstrologer',[ApiController::class,'getAllAstrologer']);
 Route::Post('/getChatAstrologer',[ApiController::class,'getChatAstrologer']);
 Route::Post('/getLiveAstrologer',[ApiController::class,'getLiveAstrologer']);
 Route::Post('/getCallAstrologer',[ApiController::class,'getCallAstrologer']);
 Route::Post('/astrologerProfileDetail',[ApiController::class,'astrologerProfileDetail']);
 Route::Post('/saveAstrologerOtherData',[ApiController::class,'saveAstrologerOtherData']);
 Route::Post('/customerEdit',[ApiController::class,'customerEdit']);
 Route::Post('/astrologerEditProfile',[ApiController::class,'astrologerEditProfile']);
 Route::Post('/cityList',[ApiController::class,'cityList']);
 Route::Post('/stateList',[ApiController::class,'stateList']);
 Route::Post('/countryList',[ApiController::class,'countryList']);
 Route::Post('/languageList',[ApiController::class,'languageList']);
 Route::Post('/giftItem',[ApiController::class,'giftItem']);
 Route::Post('/banner',[ApiController::class,'banner']);
 Route::Post('/addBlog',[ApiController::class,'addBlog']);
 Route::Post('/getBlog',[ApiController::class,'getBlog']);
 Route::Post('/blogLike',[ApiController::class,'blogLike']);
 Route::Post('/getMyBlog',[ApiController::class,'getMyBlog']);
 Route::Post('/deleteBlog',[ApiController::class,'deleteBlog']);
 Route::Post('/notificationList',[ApiController::class,'notificationList']);
 Route::Post('/notificationAstrologerList',[ApiController::class,'notificationAstrologerList']);
 Route::Post('/astrologerFollow',[ApiController::class,'astrologerFollow']);
 Route::Post('/astrologerUnfollow',[ApiController::class,'astrologerUnfollow']);
 Route::Post('/rechargeVoucher',[ApiController::class,'rechargeVoucher']);
 Route::Post('/addReviews',[ApiController::class,'addReviews']);
 Route::Post('/saveReport',[ApiController::class,'saveReport']);
 Route::Post('/addCustomerWalletRecharge',[ApiController::class,'addCustomerWalletRecharge']);
 Route::Post('/page',[ApiController::class,'page']);
 Route::Post('/startChat',[ApiController::class,'startChat']);
 Route::Post('/reciveChat',[ApiController::class,'reciveChat']);
 Route::Post('/endChat',[ApiController::class,'endChat']);
 Route::Post('/getTotalBalance',[ApiController::class,'getTotalBalance']);
 Route::Post('/astrologerOnlineStatus',[ApiController::class,'astrologerOnlineStatus']);
 Route::Post('/updateNextOnlineTime',[ApiController::class,'updateNextOnlineTime']);
 Route::Post('/updateOnlineStatus',[ApiController::class,'updateOnlineStatus']);
 Route::Post('/updateVideoCallStatus',[ApiController::class,'updateVideoCallStatus']);
 Route::Post('/updateCallStatus',[ApiController::class,'updateCallStatus']);
 Route::Post('/updateChatStatus',[ApiController::class,'updateChatStatus']);






Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


