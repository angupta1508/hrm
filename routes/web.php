<?php



use Illuminate\Http\Request;

use App\Http\Controllers\Users;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeavesController;
// use App\Http\Controllers\EmployeAttendanceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ManualAttendanceController;



// if (!function_exists('pr')) {
//   function pr($arr)
//   {

//     echo '<pre>';

//     print_r($arr);

//     echo '</pre>';
//   }
// }



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



//  Route::get('service_category', [ServiceCategoryController::class, 'index'])->name('service_category');







Route::group(['middleware' => 'maintenance'], function () {
  Route::group(['middleware' => 'admin.guest'], function () {
  });


  Route::get('/', [HomeController::class, 'index'])->name('home');
  Route::get('/employeeLogout', [HomeController::class, 'employeeLogout'])->name('employeeLogout');
  Route::get('/userlogout', [HomeController::class, 'userLogout'])->name('userlogout');
  Route::get('page/{page_slug}', [HomeController::class, 'page'])->name('page');
  Route::post('package-buy', [HomeController::class, 'packageBuy'])->name('packagebuy');
  Route::get('packages', [HomeController::class, 'package'])->name('package');
  Route::get('packages', [HomeController::class, 'package'])->name('package');
  Route::get('package-detail/{id}', [HomeController::class, 'packageDetail'])->name('packagedetail');
  Route::post('/get-user-detail', [HomeController::class, 'getUserDetail'])->name('getUserDetail');

  //employee routes
  Route::get('employee-login', [HomeController::class, 'employeeLogin'])->name('employeeLogin');

  Route::post('employee-login-store', [HomeController::class, 'employeeLoginStore'])->name('employeeloginstore');
  Route::post('employee-login-store', [HomeController::class, 'employeeLoginStore'])->name('employeeloginstore');
  // Route::post('otp-login', [HomeController::class, 'otpLogin'])->name('otpLogin');
  Route::post('get-otp', [HomeController::class, 'getOtp'])->name('getOtp');
  Route::post('verify-otp', [HomeController::class, 'verifyOtp'])->name('verifyOtp');


  Route::get('forget-password', [HomeController::class, 'showForgetPasswordForm'])->name('showForgetPasswordForm');
  Route::post('forget-password', [HomeController::class, 'submitForgetPasswordForm'])->name('submitForgetPasswordForm');
  Route::get('reset-password/{token}', [HomeController::class, 'showResetPasswordForm'])->name('showResetPasswordForm');
  Route::post('reset-password', [HomeController::class, 'submitResetPasswordForm'])->name('submitResetPasswordForm');



  Route::group(['middleware' => 'admin.guest:front-user'], function () {

    Route::get('employee-dashboard', [HomeController::class, 'employeeDashboard'])->name('employeedashboard');
    Route::get('profile', [HomeController::class, 'profile'])->name('profile');
    Route::get('calender', [HomeController::class, 'calender'])->name('calender');
    Route::post('attendancePunch', [HomeController::class, 'attendancePunch'])->name('attendancePunch');
    Route::post('attendanceTime', [HomeController::class, 'attendanceTime'])->name('attendanceTime');
    Route::get('user-list', [HomeController::class, 'userList'])->name('userList');
    Route::post('get-user-data', [HomeController::class, 'getUserData'])->name('getUserData');
    Route::resource('employe-leave', LeavesController::class);

    Route::get('attendance-list', [AttendanceController::class, 'attendanceList'])->name('attendanceList');
    Route::get('check-in-out', [HomeController::class, 'checkInOut'])->name('checkInOut');
    Route::get('/user-location/{id}', [HomeController::class, 'userLocation'])->name('getUserLocation');

    Route::resource('employee-regularise', AttendanceController::class);
    Route::post('approve-leave', [LeavesController::class, 'approveLeave'])->name('approveLeave');
    Route::get('approve-leave-list', [LeavesController::class, 'approveLeaveList'])->name('approveLeaveList');
    Route::get('userDetail/{id}', [HomeController::class, 'userDetail'])->name('userDetail');
    Route::get('notice', [HomeController::class, 'notice'])->name('notice');
    Route::get('faq', [HomeController::class, 'userFaq'])->name('faq');
    Route::get('wish', [HomeController::class, 'userWish'])->name('wish');
    Route::get('getMore', [HomeController::class, 'getMore'])->name('getMore');
    Route::post('updateImg/{id}', [HomeController::class, 'updateImg'])->name('updateImg');
    Route::resource('attendance-regularise', ManualAttendanceController::class);
    Route::get('approve-attendance-list', [ManualAttendanceController::class, 'approveAttendanceList'])->name('approveAttendanceList');
    Route::get('approve-leave', [ManualAttendanceController::class, 'approveLeave'])->name('approveLeave');
    Route::get('approvel-attendance', [ManualAttendanceController::class, 'approvelAttendance'])->name('approvelAttendance');
    Route::post('approve-status', [ManualAttendanceController::class, 'approveStatus'])->name('approveStatus');
    Route::post('/get-calender-data', [HomeController::class, 'getCalenderData'])->name('getCalenderData');
    Route::post('/get-user-present-detail', [HomeController::class, 'getUserPresentDetail'])->name('getUserPresentDetail');
    Route::get('user-notification', [HomeController::class, 'userNotification'])->name('userNotification');

    // Route::get('/get-data', 'DataController@getData');
  });
  Route::group(['middleware' => 'admin.guest:front-admin'], function () {

    Route::get('admin-dashboard', [HomeController::class, 'adminDashboard'])->name('admindashboard');
    // Route::group(['middleware' => 'check'], function () {
    Route::post('goto-adminpanel', [HomeController::class, 'gotoAdminPanel'])->name('gotoadminpanel');
    // });

    Route::get('recharge-history', [HomeController::class, 'rechargeHistory'])->name('rechargehistory');


    Route::post('payment', [HomeController::class, 'payment'])->name('payment');
    Route::get('setting', [HomeController::class, 'setting'])->name('setting');
    Route::post('setting-saved', [HomeController::class, 'settingSaved'])->name('settingsaved');

    Route::post('product-purchase', [HomeController::class, 'productPurchase'])->name('productpurchase');

    Route::post('gocheckout', [HomeController::class, 'goCheckout'])->name('gocheckout');

    Route::get('checkout', [HomeController::class, 'Checkout'])->name('checkout');

    Route::get('paid-kundali', [HomeController::class, 'paidKundali'])->name('paidkundali');

    Route::post('kundali-request', [HomeController::class, 'kundaliRequest'])->name('kundalirequest');

    Route::post('kundaliCalulation', [HomeController::class, 'kundaliCalulation'])->name('kundaliCalulation');

    Route::get('generate-kundali', [HomeController::class, 'generateKundali'])->name('generatekundali');

    Route::get('send-mail-kundali/{id}', [HomeController::class, 'sendMailKundali'])->name('sendmailkundali');

    Route::get('/call-history', [HomeController::class, 'callHistory'])->name('callhistory');

    Route::get('/chat-history', [HomeController::class, 'chatHistory'])->name('chathistory');

    Route::post('getAddressbyid', [HomeController::class, 'getAddressByid'])->name('getAddressbyid');

    Route::get('save-address', [HomeController::class, 'saveAddress'])->name('saveaddress');

    Route::get('order', [HomeController::class, 'order'])->name('order');

    Route::get('order-invoice/{id}', [HomeController::class, 'orderInvoice'])->name('orderinvoice');

    Route::post('change-order-status', [HomeController::class, 'changeOrdreStatus'])->name('changeOrdreStatus');

    Route::get('horoscope', [HomeController::class, 'horoscope'])->name('horoscope');

    Route::get('/service-order-list', [HomeController::class, 'serviceOrderList'])->name('serviceorderlist');
  });

  // Route::group(['middleware' => 'guest:front-user'], function () {


  Route::post('loginStore', [HomeController::class, 'loginStore'])->name('loginstore');

  Route::get('lang/{lang}', [HomeController::class, 'switchLang'])->name('switchLang');

  Route::get('contact', [HomeController::class, 'contact'])->name('contact');

  Route::get('about', [HomeController::class, 'about'])->name('about');

  Route::post('enquiry', [HomeController::class, 'enquiry'])->name('enquiry');

  Route::post('register', [HomeController::class, 'register'])->name('register');

  Route::post('get-state', [HomeController::class, 'getState'])->name('getState');

  Route::post('get-city', [HomeController::class, 'getCity'])->name('getCity');

  Route::post('get-otp', [HomeController::class, 'getOtp'])->name('getOtp');

  Route::post('verify-otp', [HomeController::class, 'verifyOtp'])->name('verifyotp');

  Route::post('update-profile', [HomeController::class, 'updateProfile'])->name('updateprofile');


  Route::put('profile-update/{id}', [HomeController::class, 'profileUpdate'])->name('profileupdate');


  // });
});
