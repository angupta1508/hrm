<?php


use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExceptionController;
use App\Http\Controllers\Backend\BanksController;
use App\Http\Controllers\Backend\MoodsController;
use App\Http\Controllers\Backend\PagesController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\CitiesController;
use App\Http\Controllers\Backend\PolicyController;
use App\Http\Controllers\Backend\ShiftsController;
use App\Http\Controllers\Backend\StatesController;
use App\Http\Controllers\Backend\WishesController;
use App\Http\Controllers\Backend\NoticesController;
use App\Http\Controllers\Backend\PackageController;
use App\Http\Controllers\Backend\HolidaysController;
use App\Http\Controllers\Backend\SalariesController;
use App\Http\Controllers\Backend\SettingsController;
use App\Http\Controllers\Backend\CompaniesController;
use App\Http\Controllers\Backend\CountriesController;
use App\Http\Controllers\Backend\DutyTypesController;
use App\Http\Controllers\Backend\EnquiriesController;
use App\Http\Controllers\Backend\LanguagesController;
use App\Http\Controllers\Backend\LocationsController;
use App\Http\Controllers\Backend\RechargerController;
use App\Http\Controllers\Backend\AttendanceController;
use App\Http\Controllers\Backend\LeaveTypesController;
use App\Http\Controllers\Backend\DepartmentsController;
use App\Http\Controllers\Backend\SalaryTypesController;
use App\Http\Controllers\Backend\SalaySetupsController;
use App\Http\Controllers\Backend\ManualAttendanceController;


// use App\Http\Controllers\Backend\HolidaysController;
// use App\Http\Controllers\Backend\CompaniesController;
// use App\Http\Controllers\Backend\DutyTypeController;
// use App\Http\Controllers\Backend\LeavetypeController;
// use App\Http\Controllers\Backend\LeavetypeinoutController;
// use App\Http\Controllers\Backend\PositionsController;
// use App\Http\Controllers\Backend\ShiftsController;
use App\Http\Controllers\Backend\SmsTemplateController;
use App\Http\Controllers\Backend\UserBankersController;
use App\Http\Controllers\Backend\DesignationsController;
use App\Http\Controllers\Backend\EmailTemplateController;
use App\Http\Controllers\Backend\NotificationsController;
use App\Http\Controllers\Backend\LeaveTypeInOutsController;
use App\Http\Controllers\Backend\PaymentGatewaysController;
use App\Http\Controllers\Backend\LeaveApplicationController;
use App\Http\Controllers\Backend\PerformanceTypesController;
use App\Http\Controllers\Backend\AttendanceReasonsController;
use App\Http\Controllers\Backend\SalarySettlementController;

Route::group(['prefix' => '/', 'as' => 'admin.'], function () {

    // Route::get('exception/index', ExceptionController::class);
    // Route::get('exception/index', 'ExceptionController@index');

    Route::group(['middleware' => 'admin.auth'], function () {

        Route::get('/', [UsersController::class, 'dashboard'])->name('dashboard');
        Route::resource('users', UsersController::class);
        Route::post('/users/change-status', [UsersController::class, 'changeStatus'])->name('users.changeStatus');
        Route::post('/users/get-user-detail', [UsersController::class, 'getUserDetail'])->name('users.getUserDetail');
        Route::post('/roles/get-role-detail', [RolesController::class, 'getRoleDetail'])->name('roles.getRoleDetail');
        Route::get('/users-import', [UsersController::class, 'importView'])->name('users.importView');
        Route::post('/users-import', [UsersController::class, 'import'])->name('users.import');

        
        Route::delete('/users/{id}/trash', [UsersController::class, 'trash'])->name('users.trash');

        Route::get('/logout', [UsersController::class, 'logout'])->name('logout');
        Route::get('/user-profile', [UsersController::class, 'userProfile'])->name('userProfile');
        Route::get('/edit-profile', [UsersController::class, 'editProfile'])->name('editProfile');
        Route::post('/update-profile', [UsersController::class, 'updateProfile'])->name('updateProfile');
        Route::get('change-password', [UsersController::class, 'change_password'])->name('change_password');
        Route::post('/update-password', [UsersController::class, 'updatePassword'])->name('updatePassword');
        Route::get('/user-location/{id}', [UsersController::class, 'userLocation'])->name('userLocation');
        Route::get('/reset-device-id/{id}', [UsersController::class, 'resetDeviceId'])->name('resetdeviceid');
        Route::get('/all-user-location', [UsersController::class, 'allUserLocation'])->name('allUserLocation');

        Route::post('/users/usergraph-status', [UsersController::class, 'userRegistorGraph'])->name('users.userGraphStatus');
        Route::post('/users/admingraph-status', [UsersController::class, 'adminRegistorGraph'])->name('users.adminGraphStatus');
        

        Route::group(['middleware' => 'check'], function () {
            Route::resource('superadmin-roles', RolesController::class);
            Route::resource('superadmin-staff-roles', RolesController::class);
            Route::resource('admin-roles', RolesController::class);
            Route::resource('user-roles', RolesController::class);

            Route::group(['prefix' => 'moods', 'as' => 'moods.',], function () {
                Route::resource('moods', MoodsController::class);
                Route::post('/moods/change-status', [MoodsController::class, 'changeStatus'])->name('moods.changeStatus');

                Route::resource('Wishes', WishesController::class);
                Route::post('/Wishes/change-status', [WishesController::class, 'changeStatus'])->name('Wishes.changeStatus');
            });

            Route::group(['prefix' => 'administration', 'as' => 'administration.',], function () {
                Route::resource('languages', LanguagesController::class);
                Route::post('/languages/change-status', [LanguagesController::class, 'changeStatus'])->name('languages.changeStatus');

                Route::resource('shifts', ShiftsController::class);
                Route::post('/shifts/change-status', [ShiftsController::class, 'changeStatus'])->name('shifts.changeStatus');

                Route::resource('companies', CompaniesController::class);
                Route::post('/companies/change-status', [CompaniesController::class, 'changeStatus'])->name('companies.changeStatus');

                Route::resource('departments', DepartmentsController::class);
                Route::post('/departments/change-status', [DepartmentsController::class, 'changeStatus'])->name('departments.changeStatus');

                Route::resource('banks', BanksController::class);
                Route::post('/banks/change-status', [BanksController::class, 'changeStatus'])->name('banks.changeStatus');

                Route::resource('locations', LocationsController::class);
                Route::post('/locations/change-status', [LocationsController::class, 'changeStatus'])->name('locations.changeStatus');

                Route::resource('payment-gateways', PaymentGatewaysController::class);
                Route::post('/payment-gateways/change-status', [PaymentGatewaysController::class, 'changeStatus'])->name('payment-gateways.changeStatus');

                Route::resource('designations', DesignationsController::class);
                Route::post('/designations/change-status', [DesignationsController::class, 'changeStatus'])->name('designations.changeStatus');

                Route::resource('user-policy', PolicyController::class);
                Route::post('/user-policy/change-status', [PolicyController::class, 'changeStatus'])->name('userpolicy.changeStatus');
            });

            Route::resource('email-template', EmailTemplateController::class);
            Route::post('/email-template/change-status', [EmailTemplateController::class, 'changeStatus'])->name('email-template.changeStatus');

            Route::resource('sms-template', SmsTemplateController::class);
            Route::post('/sms-template/change-status', [SmsTemplateController::class, 'changeStatus'])->name('sms-template.changeStatus');

            Route::group(['prefix' => 'cms', 'as' => 'cms.',], function () {
                Route::resource('pages', PagesController::class);
                Route::post('/pages/change-status', [PagesController::class, 'changeStatus'])->name('pages.changeStatus');

                Route::resource('enquires', EnquiriesController::class);
                Route::post('/enquires/change-status', [EnquiriesController::class, 'changeStatus'])->name('enquires.changeStatus');

                Route::resource('notices', NoticesController::class);
                Route::post('/notices/change-status', [NoticesController::class, 'changeStatus'])->name('notices.changeStatus');
                Route::get('/notices/downloadimage/{id}', [NoticesController::class, 'downloadAttachment'])->name('notices.downloadimage');

                //NotificationsController
                Route::resource('notifications', NotificationsController::class);
                Route::post('/notifications/change-status', [NotificationsController::class, 'changeStatus'])->name('notifications.changeStatus');
                Route::post('/notifications/resend-notifications', [NotificationsController::class, 'resendNotifiction'])->name('notifications.resendNotifiction');
                Route::post('/notifications/token', [NotificationsController::class, 'tokenUpdate'])->name('notifications.tokenUpdate');
            });

            Route::group(['prefix' => 'attendence', 'as' => 'attendence.',], function () {

                Route::resource('attendance', AttendanceController::class);
                Route::post('/attendance/change-status', [AttendanceController::class, 'changeStatus'])->name('attendance.changeStatus');
                Route::get('attendance-report', [AttendanceController::class, 'attendanceReport'])->name('attendance.attendanceReport');
                Route::get('miss-punch-report', [AttendanceController::class, 'missPunchReport'])->name('attendance.missPunchReport');
                Route::get('attendance-log', [AttendanceController::class, 'attendanceLog'])->name('attendance.attendanceLog');
                Route::get('present-register', [AttendanceController::class, 'presentRegister'])->name('attendance.presentregister');
                Route::get('month-wise-report', [AttendanceController::class, 'monthWiseReport'])->name('attendance.monthWiseReport');
                Route::resource('attendance-reasons', AttendanceReasonsController::class);
                Route::post('/attendance-reasons/change-status', [AttendanceReasonsController::class, 'changeStatus'])->name('attendance-reasons.changeStatus');
                Route::resource('manualAttendance', ManualAttendanceController::class);
                Route::post('/manualAttendance/change-status', [ManualAttendanceController::class, 'changeStatus'])->name('manualAttendance.changeStatus');
                Route::post('/attendance_approve/approveStatus', [ManualAttendanceController::class, 'approveStatus'])->name('attendance_approve.approveStatus');
            });

            Route::group(['prefix' => 'leave', 'as' => 'leave.',], function () {
                Route::resource('leaves', LeaveApplicationController::class);
                Route::post('/leaves/change-status', [LeaveApplicationController::class, 'changeStatus'])->name('leaves.changeStatus');
                Route::post('/approve_leave_application/approveStatus', [LeaveApplicationController::class, 'approveStatus'])->name('approve_leave_application.approveStatus');
                Route::get('/leaves/getLeave', [LeaveApplicationController::class, 'getLeave'])->name('leaves.getLeave');

                Route::resource('leave-types', LeaveTypesController::class);
                Route::post('/leave-type/change-status', [LeaveTypesController::class, 'changeStatus'])->name('leave-type.changeStatus');

                Route::resource('holidays', HolidaysController::class);
                Route::post('/holidays/change-status', [HolidaysController::class, 'changeStatus'])->name('holidays.changeStatus');
            });

            Route::group(['prefix' => 'payroll', 'as' => 'payroll.',], function () {
                Route::resource('salary-types', SalaryTypesController::class);
                Route::post('/salary-types/change-status', [SalaryTypesController::class, 'changeStatus'])->name('salary-types.changeStatus');
                
                Route::resource('salary-settlement', SalarySettlementController::class);

                Route::resource('salary-setup', SalaySetupsController::class);
                Route::post('/salary-setup/change-status', [SalaySetupsController::class, 'changeStatus'])->name('salary-setup.changeStatus');
                Route::resource('salary', SalariesController::class);
                Route::post('/salary/salary-slip', [SalariesController::class, 'slipGenrate'])->name('salaryslip');

                Route::get('salary-pay', [SalariesController::class, 'SalaryPay'])->name('salary.salarypay');
                Route::post('salary-payout', [SalariesController::class, 'SalaryPayout'])->name('salary.salarypayout');
            });

            Route::group(['prefix' => 'performance', 'as' => 'performance.',], function () {
                // Route::resource('performances', PerformancesController::class);
                // Route::post('/performances', [PerformancesController::class, 'changeStatus'])->name('performances.changeStatus');
                Route::resource('performance-types', PerformanceTypesController::class);
                Route::post('/performance/change-status', [PerformanceTypesController::class, 'changeStatus'])->name('performance.changeStatus');
            });
        });
        Route::resource('roles', RolesController::class);
        Route::post('/roles/change-status', [RolesController::class, 'changeStatus'])->name('roles.changeStatus');
        Route::post('/roles/permission-store', [RolesController::class, 'permissionStore'])->name('roles.permissionStore');
        Route::get('/permissions/{permission}', [RolesController::class, 'permissions'])->name('roles.permissions');

        $routeRoles = Role::all();
        foreach ($routeRoles as $routeRole) {
            $slug = Str::slug($routeRole->name);
            Route::resource($slug, UsersController::class);
            Route::get($slug . '-import', [UsersController::class, 'importView'])->name($slug . '.importView');
            Route::post($slug . '-import', [UsersController::class, 'import'])->name($slug . '.import');
            Route::delete('/'.$slug.'/{id}/trash', [UsersController::class, 'trash'])->name($slug.'.trash');
        }

        Route::resource('user-bankers', UserBankersController::class);
        Route::post('/user-bankers/change-status', [UserBankersController::class, 'changeStatus'])->name('user-bankers.changeStatus');

        Route::resource('countries', CountriesController::class);
        Route::post('/countries/change-status', [CountriesController::class, 'changeStatus'])->name('countries.changeStatus');

        Route::resource('states', StatesController::class);
        Route::post('/states/change-status', [StatesController::class, 'changeStatus'])->name('states.changeStatus');

        Route::resource('cities', CitiesController::class);
        Route::post('/cities/change-status', [CitiesController::class, 'changeStatus'])->name('cities.changeStatus');

        // Settings Controller
        Route::resource('settings', SettingsController::class);
        Route::post('setting/update-setting', [SettingsController::class, 'updateSetting'])->name('setting.updateSetting');

        Route::get('setting/company', [SettingsController::class, 'company'])->name('setting.company');
        Route::get('setting/email', [SettingsController::class, 'email'])->name('setting.email');
        Route::get('setting/sms', [SettingsController::class, 'sms'])->name('setting.sms');
        Route::get('setting/payment', [SettingsController::class, 'payment'])->name('setting.payment');
        Route::get('setting/withdrawal', [SettingsController::class, 'withdrawal'])->name('setting.withdrawal');
        Route::get('setting/social', [SettingsController::class, 'social'])->name('setting.social');


        Route::resource('package', PackageController::class);
        Route::get('/package/trash/{id}', [PackageController::class, 'trash'])->name('package.trash');
        Route::post('/package/change-status', [PackageController::class, 'changeStatus'])->name('package.changeStatus');
        Route::post('/package/moduleUpdate', [PackageController::class, 'moduleUpdate'])->name('package.moduleUpdate');
        Route::get('/package/module-permission/{id}', [PackageController::class, 'modulePermission'])->name('package.modulepermission');
        Route::post('/package/change-status', [PackageController::class, 'changeStatus'])->name('package.changeStatus');
    });





    Route::resource('recharge', RechargerController::class);
    Route::post('/payment', [RechargerController::class, 'payment'])->name('recharge.payment');
    Route::get('/register', [UsersController::class, 'register'])->name('register');
    Route::post('/register', [UsersController::class, 'registerStore'])->name('registerStore');
    Route::get('/login', [UsersController::class, 'login'])->name('login');
    Route::post('/session', [UsersController::class, 'loginStore'])->name('loginStore');
    Route::get('/forgot-password', [UsersController::class, 'forgotPassword'])->name('forgotPassword');
    Route::post('/forgot-password', [UsersController::class, 'sendEmail'])->name('forgotPasswordSendEmail');
    Route::get('/reset-password/{token}', [UsersController::class, 'resetPass'])->name('resetPass');
    Route::post('/reset-password', [UsersController::class, 'changePassword'])->name('changePassword');
    Route::get('user-change-password/{id}', [UsersController::class, 'userChangePassword'])->name('user-change-password');
    Route::post('user-change-password/{user}', [UsersController::class, 'userUpdatePassword'])->name('user-update-password');
    Route::post('/users-date', [UsersController::class, 'date'])->name('users.date');
    Route::post('/users/change-status', [UsersController::class, 'changeStatus'])->name('users.changeStatus');

    // Route::post('getState', [UsersController::class, 'getState'])->name('getState');
    // Route::post('getCity', [UsersController::class, 'getCity'])->name('getCity');




    Route::resource('duty-types', DutyTypesController::class);
    Route::post('/duty-type/change-status', [DutyTypesController::class, 'changeStatus'])->name('duty-type.changeStatus');

    Route::resource('leave-type-in-outs', LeaveTypeInOutsController::class);
    Route::post('/leave-type-in-outs/change-status', [LeaveTypeInOutsController::class, 'changeStatus'])->name('leave-type-in-outs.changeStatus');

    Route::resource('holidays', HolidaysController::class);
    Route::post('/holidays/change-status', [HolidaysController::class, 'changeStatus'])->name('holidays.changeStatus');

    Route::resource('wishes', WishesController::class);
    Route::post('/wishes/change-status', [WishesController::class, 'changeStatus'])->name('wishes.changeStatus');




    Route::group(['prefix' => 'master', 'as' => 'master.',], function () {
        Route::resource('duty-types', DutyTypesController::class);
        Route::post('/duty-type/change-status', [DutyTypesController::class, 'changeStatus'])->name('duty-type.changeStatus');

        Route::resource('leave-type-in-outs', LeaveTypeInOutsController::class);
        Route::post('/leave-type-in-outs/change-status', [LeaveTypeInOutsController::class, 'changeStatus'])->name('leave-type-in-outs.changeStatus');
    });
});
