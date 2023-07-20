<?php

namespace App\Http\Controllers;

use PDF;


use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Models\Bank;
use App\Models\Page;
use App\Models\Role;
use App\Models\Employee;
use App\Models\ResetPassword;
use App\Models\User;
use App\Models\UserLocationTrack;
use Razorpay\Api\Api;
use App\Models\Package;
use App\Models\Setting;
use App\Models\State;
use App\Models\Contact;
use App\Models\About;
use App\Models\Notice;
use App\Models\Attendance;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RechargeHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\UserNotification;
use App\Console\Commands\MyCommand;
use App\Models\UserOtp;




class HomeController extends Controller
{
    public function index()
    {
        // dd(Auth::guard('front-admin')->user());
        $packages = Package::where('status', 1)->get();
        return view('home.index', compact('packages'));
    }

    public function loginStore(Request $request)
    {
        $attributes = $request->all();
        if (filter_var($attributes['username'], FILTER_VALIDATE_EMAIL)) {
            $attributes['email'] = $attributes['username'];
            unset($attributes['username']);
        }
        unset($attributes['_token']);
        if (Auth::guard('front-admin')->attempt($attributes)) {
            session()->regenerate();

            // dd(Auth::guard('front-admin')->user());

            // dd('sdfd');
            $roles = Role::where('id', '=', Auth::guard('front-admin')->user()->role_id)->first();
            if (!$roles->status) {
                Auth::guard('front-admin')->logout();
                $result = array(
                    'status' => 0,
                    'msg' => 'Your role is inactive. Contact with Authorises Person',
                );
                return response()->json($result);
            }
            if (Auth::guard('front-admin')->user()->role_id != config('constants.admin_role_id')) {
                Auth::guard('front-admin')->logout();
                $result = array(
                    'status' => 0,
                    'msg' => 'Unauthorises User',
                );
                return response()->json($result);
            }

            if (!Auth::guard('front-admin')->user()->status) {
                Auth::guard('front-admin')->logout();
                $result = array(
                    'status' => 0,
                    'msg' => 'Your account is inactive. Contact with Authorises Person',
                );
                return response()->json($result);
            }
            $result = array(
                'status' => 1,
                'process_status' => Auth::guard('front-admin')->user()->process_status,
                'msg' => 'You are logged in.',
            );

            return response()->json($result);
        } else {
            $result = array(
                'status' => 0,
                'msg' => 'Username or password invalid.',
            );
            return response()->json($result);
        }
    }


    public function gotoAdminPanel(Request $request)
    {
        $user = User::where('id', Config::get('auth_detail')['id'])->first();
        if ($user->package_valid_date >= Config::get('current_date')) {
            Auth::login($user, $remember = true);
            return redirect()->route('admin.dashboard')
                ->with('success', __('User Login successfully.'));
        } else {
            return redirect()->route('admindashboard')
                ->with('error', __('Your Package Expiry.'));
        }
    }

    public function rechargeHistory(Request $request)
    {
        $limit = config('constants.default_page_limit');
        $filter = $request->query();

        $thismodel = RechargeHistory::where('admin_id', Config::get('auth_detail')['admin_id']);
        $status = $request->status;
        if (isset($status) && $status != "") {
            $thismodel->where('status', $filter['status']);
        }

        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->Where('recharge_uni_id', 'LIKE', '%' . $keyword . '%')->orWhere('package_uni_id', 'LIKE', '%' . $keyword . '%')->orWhere('admin_id', 'LIKE', '%' . $keyword . '%')->orWhere('order_id', 'LIKE', '%' . $keyword . '%')->orWhere('razorpay_id', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (!empty($filter['start_date'])) {
            $thismodel->whereDate('recharge_histories.created_at', '>=', $filter['start_date']);
        }

        if (!empty($filter['end_date'])) {
            $thismodel->whereDate('recharge_histories.created_at', '<=', $filter['end_date']);
        }

        $rechargeHistory = $thismodel->paginate($limit);
        return view('home.dashboard.rechargehistory', compact('rechargeHistory', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    public function userLogout()
    {
        Auth::guard('front-admin')->logout();
        Session::flush();
        return redirect()->route('home')
            ->with('success', __('User Logout successfully.'));
    }

    public function employeeLogout()
    {
        Auth::guard('front-user')->logout();
        Session::flush();
        return redirect()->route('employeeLogin')
            ->with('success', __('User Logout successfully.'));
    }


    //start- forget password

    public function showForgetPasswordForm()
    {
        return view('home.employee.forget_password');
    }


    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()

        ]);

        $action_link = route('showResetPasswordForm', ['token' => $token, 'email' => $request->email]);
        $body = "we are received the request of your password for<b>your app name</b> account associated with" . $request->email . "
        .you can reset your password  by clicking the link below";

        Mail::send('home.employee.forget_email', ['action_link' => $action_link, 'token' => $token, 'body' => $body], function ($message) use ($request) {
            $message->from('noreply@astrocarejyotish.com', 'SynilogicTech');
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return back()->with('message', 'We have e-mailed your password reset link!');
    }


    public function showResetPasswordForm($token)
    {
        return view('home.employee.forget_password_link', ['token' => $token]);
    }


    public function submitResetPasswordForm(Request $request)
    {

        //    dd( $request);
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        //  dd( $updatePassword);

        if (empty($updatePassword)) {
            return redirect()->back()->with('message', 'Invalid token!..Please regenrate new token to reset password.');
        }

        if (!($updatePassword)) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect()->route('employeeLogin')->with('message', 'Your password has been changed!');
    }


    //end- forget password



    public function adminDashboard()
    {
        $authdetail = Config::get('auth_detail');
        // dd($authdetail);
        return view('home.dashboard.admindashboard', compact('authdetail'));
    }

    public function getUserDetail(Request $request)
    {
        $user = User::where('user_uni_id', $request->user)->first();
        if (!empty($user)) {
            return response()->json([
                'status' => '1',
                'data' => $user,
                'msg' => __('Status changed successfully.')
            ]);
        } else {
            return response()->json([
                'status' => '0',
                'msg' => 'No record Found.'
            ]);
        }
    }

    public function package()
    {
        $packages = Package::where('status', 1)->get();
        return view('home.package.package', compact('packages'));
    }

    public function packageDetail($id)
    {
        $id = Crypt::decrypt($id);
        $packages = multiPackageTimePeriod($id);
        return view('home.package.packagedetail', compact('packages'));
    }

    public function packageBuy(Request $request)
    {
        $userData = Config::get('auth_detail');
        if (Auth::guard('front-admin')->check()) {
            $pakages = multiPackageTimePeriod($request->package_uni_id);
            $packageData = $pakages[$request->type];
            // dd($packageData);
            $result = '';
            if ($userData['istrial'] == 1) {
                $razorpayId = Config::get('razorpay_id');
                $razorpayKey = Config::get('razorpay_key');
                $api = new Api($razorpayId, $razorpayKey);
                $receiptId = Str::random(20);
                $order = $api->order->create(
                    array(
                        'receipt' => $receiptId,
                        'amount' => $packageData['price'] * 100,
                        'currency' => 'INR',
                    )
                );
                $company_logo = getSettingData('logo', config('constants.superadmin_role_id'), 'val');
                $imgPath = config('constants.setting_image_path');
                $imgDefaultPath = config('constants.default_image_path');
                $logo = ImageShow($imgPath, $company_logo, 'icon', $imgDefaultPath);
                $array = (object) array(
                    'package_uni_id' => $request->package_uni_id,
                    'amount' => $order->amount,
                    'order_id' => $order->id,
                    'logo' => $logo,
                    'razorpayId' => $razorpayId,
                );
                $array->duration = $packageData['duration'];
                $data = array(
                    'package_uni_id' => $request->package_uni_id,
                    'order_id' => $order->id,
                    'admin_id' => $userData['admin_id'],
                    'amount' => $packageData['price'],
                    'recharge_uni_id' => new_sequence_code('REC'),
                );
                RechargeHistory::create($data);
                $result = array(
                    'status' => 1,
                    'type' => 1,
                    'data' => $array,
                );
            } else {
                //  $array->duration = $packageData->trial_duration;
                $data = array(
                    'package_uni_id' => $request->package_uni_id,
                    'order_id' => 'trial',
                    'admin_id' => $userData['admin_id'],
                    'amount' => '0',
                    'recharge_uni_id' => new_sequence_code('REC'),
                    'razorpay_id' => 'trial',
                    'pay_method' => 'Razorpay',
                    'status' => 1,
                );
                RechargeHistory::create($data);
                if ($userData['package_valid_date'] > Config::get('current_date')) {
                    $exp = strtotime(' +' . $packageData['trial_duration'] . 'day', strtotime($userData['package_valid_date']));
                    $date = date('Y-m-d', $exp);
                } else {
                    $exp = strtotime(' +' . $packageData['trial_duration'] . 'day', strtotime(Config::get('current_date')));
                    $date = date('Y-m-d', $exp);
                }
                $result = User::where('id', $userData['admin_id'])->update(['package_uni_id' => $request->package_uni_id, 'package_valid_date' => $date, 'istrial' => 1]);
                $result = array(
                    'status' => 1,
                    'type' => 0,
                    'msg' => 'Your trial Package Recharged',
                );
            }
            if (empty($result)) {
                $result = array(
                    'status' => 0,
                    'msg' => 'Something went Wrong',
                );
            }
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'Login in Panel',
            );
        }
        return response()->json($result);
    }


    public function payment(Request $request)
    {
        $userData = User::where('mobile', $request->number)->first();
        $data = array();
        $data = array(
            'razorpay_id' => $request->razorpay_id,
            'status' => 1,
            'pay_method' => 'Razorpay',
        );
        if ($userData->role_id == config('constants.admin_role_id')) {
            $result = RechargeHistory::where('order_id', $request->order_id)->update($data);
            if ($userData->package_valid_date > Config::get('current_date')) {
                $exp = strtotime(' +' . $request->duration . 'day', strtotime($userData->package_valid_date));
                $date = date('Y-m-d', $exp);
            } else {
                $exp = strtotime(' +' . $request->duration . 'day', strtotime(Config::get('current_date')));
                $date = date('Y-m-d', $exp);
            }
            $result = $userData->update(['package_uni_id' => $request->package_uni_id, 'package_valid_date' => $date]);
            if ($result) {
                $response = array(
                    'status' => 1,
                    'msg' => "Payment Successfully",
                );
            } else {
                $response = array(
                    'status' => 0,
                    'msg' => "Something went wrong1",
                );
            }
        } else {
            $response = array(
                'status' => 0,
                'msg' => "Something went wrong2",
            );
        }
        return response()->json($response);
    }


    public function setting()
    {
        $userData = Auth::guard('front-admin')->user();
        if ($userData->process_status == 0) {
            $settingType = 'company';
        } elseif ($userData->process_status == 1) {
            $settingType = 'email';
        } elseif ($userData->process_status == 2) {
            $settingType = 'sms';
        } elseif ($userData->process_status == 3) {
            $settingType = 'social';
        } elseif ($userData->process_status == 4) {
            $settingType = 'payment';
        }
        if ($userData->process_status == 5) {
            return redirect()->route('admindashboard')->with(['success' => __('Setting updated successfully.')]);
        }
        $settings = getSettings($settingType);
        return view('home.dashboard.setting', ['settings' => $settings, 'setting_type' => $settingType]);
    }

    public function settingSaved(Request $request)
    {
        $attributes = request()->validate([
            'setting' => ['required'],
            'setting_type' => ['nullable'],
        ]);
        $user =  Auth::guard('front-admin')->user();
        $keys = array_keys($attributes['setting']);
        if (!empty($keys)) {
            // dd($keys);
            $setting_check = Setting::where('setting_name', $keys[0])->first();
            $update = [];
            $update['setting_value'] = 0;
            Setting::where('setting_type', $setting_check->setting_type)->where('input_type', 'slider')->update($update);
        }

        foreach ($attributes['setting'] as $key => $value) {
            $setting = Setting::where('setting_name', $key)->where('admin_id', $user->id)->first();
            if ($setting->input_type == "file") {
                $img = $value;
                $imgPath = public_path(config('constants.setting_image_path'));
                $filename = UploadImage($img, $imgPath, $key, $setting->setting_value, 1);
                // $filename = rand(11111, 99999) . '.' . $value->getClientOriginalExtension();
                // $value->move($imgPath, $filename);
                $saveDate = [];
                $saveDate['setting_value'] = $filename;
                $saveDate['updated_at'] = date('Y-m-d H:i:s');
            } else {
                $saveDate = [];
                $saveDate['setting_value'] = $value;
                $saveDate['updated_at'] = date('Y-m-d H:i:s');
            }
            if ($user->role_id > config('constants.superadmin_role_id')) {
                $setting->where([['setting_type', $attributes['setting_type']], ['setting_name', $key], ['admin_id', $user->id]])->update($saveDate);
            } else {
                $setting->where([['setting_name', $key], ['admin_id', $user->id]])->update($saveDate);
            }
        }

        if ($user->process_status == 0 && $attributes['setting_type'] == 'company') {
            User::where('admin_id', $user->admin_id)->update(['process_status' => 1]);
        } elseif ($user->process_status == 1 && $attributes['setting_type'] == 'email') {
            User::where('admin_id', $user->admin_id)->update(['process_status' => 2]);
        } elseif ($user->process_status == 2 && $attributes['setting_type'] == 'sms') {
            User::where('admin_id', $user->admin_id)->update(['process_status' => 3]);
        } elseif ($user->process_status == 3 && $attributes['setting_type'] == 'social') {
            User::where('admin_id', $user->admin_id)->update(['process_status' => 4]);
        } elseif ($user->process_status == 4 && $attributes['setting_type'] == 'payment') {
            User::where('admin_id', $user->admin_id)->update(['process_status' => 5]);
        }
        return redirect()->route('setting')->with(['success' => __('Setting updated successfully.')]);
    }



    ///employees functions
    public function employeeLogin()
    {
        return view('home.employee.login');
    }

    public function employeeLoginStore(Request $request)
    {
        $attributes = $request->all();
        if (filter_var($attributes['username'], FILTER_VALIDATE_EMAIL)) {
            $attributes['email'] = $attributes['username'];
            unset($attributes['username']);
        }
        unset($attributes['_token']);
        if (Auth::guard('front-user')->attempt($attributes)) {
            session()->regenerate();

            // dd(Auth::guard('front-admin')->user());

            // dd('sdfd');
            $roles = Role::where('id', '=', Auth::guard('front-user')->user()->role_id)->first();
            if (!$roles->status) {
                Auth::guard('front-user')->logout();
                return back()->with('error', 'Your role is inactive. Contact with Authorises Person');
            }
            if (Auth::guard('front-user')->user()->role_id != config('constants.employee_role_id')) {
                Auth::guard('front-user')->logout();
                return back()->with('error', 'Unauthorises User');
            }

            $notice = Notice::where('status', '1')->where('type', 'welcome_message')->orderBy('id', 'desc')->first();
            // dd($notice);
            if (!Auth::guard('front-user')->user()->status) {

                Auth::guard('front-user')->logout();
                return back()->with('error', 'Your account is inactive. Contact with Authorises Person');
            }
            return redirect()->route('employeedashboard')->with('notice', $notice)->with('success', 'You are log in.');;
        } else {
            return back()->withErrors(['email' => 'Username or password invalid.']);
        }
    }

    public function employeeDashboard(Request $request)
    {
        $notice = $request->session()->get('notice');
        // dd($notice);
        $authdetail = User::getUserDetails(Config::get('auth_detail')['id'], 'emp');
        $celebrationList = upcomingCelebrations($authdetail);
        $todayCelebrationList = todayCelebrations($authdetail);
        // $todayCelebrationList = todayWebCelebrations($authdetail);


        return view('home.employee.dashboard.employee_dashboard', compact('authdetail', 'notice', 'celebrationList', 'todayCelebrationList'));
    }


    public function updateImg(Request $request, $id)
    {
        // dd($request);
        $attributes = request()->validate([
            'profile_image' => ['nullable'],
        ]);

        $leaveUpdate = User::find($id);
        if (!empty($attributes['profile_image'])) {
            $img        =   'profile_image';
            $imgPath    =   public_path(config('constants.user_image_path'));
            $img_path   =   $imgPath . $leaveUpdate->profile_image;
            if (!empty($leaveUpdate->profile_image) && file_exists($img_path)) {
                @unlink($img_path);
            };
            $filename   =   UploadImage($request, $imgPath, $img);
            $attributes['profile_image'] = $filename;
        }

        $leaveUpdate->update($attributes);
        return redirect()->route('profile')
            ->with('success', 'Profile image upload successfully.');
    }


    public function profile(Request $request)
    {
        $authdetail = User::getUserDetails(Config::get('auth_detail')['id'], 'emp');
        return view('home.employee.dashboard.profile', compact('authdetail'));
    }

    public function calender(Request $request)
    {

        $authdetail = User::getUserDetails(Config::get('auth_detail')['id'], 'emp');
        $attendence = Attendance::getTodayAttendanceData($authdetail, 'web');
        // dd($attendence );
        $attend = Attendance::where('user_id', $authdetail->user_id)->where('admin_id', $authdetail->admin_id)->get();
        return view('home.employee.dashboard.calender', compact('authdetail', 'attendence', 'attend'));
    }

    public function getCalenderData(Request $request)
    {
        $attributes = $request->all();
        Attendance::fillMissingAttendance($attributes);
        $attendance = Attendance::orderBy('attendance_date', 'ASC');
        $attendance = $attendance->where('admin_id', $attributes['admin_id']);
        $attendance = $attendance->where('user_id', $attributes['user_id']);
        if (!empty($attributes['month'])) {
            $year  = date('Y', strtotime($attributes['month']));
            $month  = date('m', strtotime($attributes['month']));
            $attendance->whereYear('attendance_date', $year);
            $attendance->whereMonth('attendance_date', $month);
        }
        $attendances = $attendance->get();

        if (!empty($attendances)) {
            $result = array(
                "status" => 1,
                "data" => $attendances,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }

        return response()->json($result);
    }

    public function checkInOut(Request $request)
    {

        $date = date('Y-m-d');
        $authdetail = User::getUserDetails(Config::get('auth_detail')['id'], 'emp');
        $attendence = Attendance::where('admin_id', $authdetail->admin_id)
            ->where('user_id', $authdetail->user_id)
            ->where('shift_id', $authdetail->shift_id)
            ->where('attendance_date', $date)
            ->first();
        return view('home.employee.dashboard.checkinout', compact('authdetail', 'attendence'));
    }


    public function attendancePunch(Request $request)
    {
        $attributes = $request->all();
        if ($attributes['punchInOut'] == 'in') {
            $result = Attendance::punchIn($request);
        } elseif ($attributes['punchInOut'] == 'out') {
            $result = Attendance::punchOut($request);
        }
        return response()->json($result);
    }

    public function attendanceTime(Request $request)
    {
        // dd($request);
        $attributes = $request->all();
        if (!empty($attributes['punchInOut'] == 'in')) {
            $fromAttendance = Attendance::where('admin_id', $request->admin_id)
                ->where('user_id', $request->user_id)
                ->where('shift_id', $request->shift_id)
                ->where('attendance_date', $request->attendance_date)
                ->first();
            $result = [];
            if ($fromAttendance) {
                $result['type'] = "from";
                $result['from_time'] = $fromAttendance->from_time;
            }

            return response()->json($result);
        }
        if (!empty($attributes['punchInOut'] == 'out')) {
            $toAttendance = Attendance::where('admin_id', $request->admin_id)
                ->where('user_id', $request->user_id)
                ->where('shift_id', $request->shift_id)
                ->where('attendance_date', $request->attendance_date)
                ->first();

            $result = [];
            if ($toAttendance) {
                $result['type'] = "to";
                $result['to_time'] = $toAttendance->to_time;
            }

            return response()->json($result);
        }
    }


    public function userList(Request $request)
    {
        $authdetail = Config::get('auth_detail');

        $thismodel = Employee::leftJoin('designations', function ($join) {
            $join->on('employees.designation_id', '=', 'designations.id');
        })->select('employees.*', 'designations.name')
            ->where('employees.admin_id', $authdetail['admin_id'])->groupBy('designations.id')->get();

        $shiftType = Employee::leftJoin('shifts', function ($join) {
            $join->on('employees.shift_id', '=', 'shifts.id');
        })->select('employees.*', 'shifts.shift_name')
            ->where('employees.admin_id', $authdetail['admin_id'])->groupBy('shifts.id')->get();

        return view('home.employee.dashboard.user_list', compact('thismodel', 'shiftType'));
    }

    public function getUserData(Request $request)
    {
        $attributes = $request->all();
        $limit = config('constants.api_page_limit');
        $attributes['limit'] = $limit;
        $attributes['authorised_person_id'] = Config::get('auth_detail')['id'];
        $attributes['admin_id'] = Config::get('auth_detail')['admin_id'];

        $users = User::getUserData($attributes);

        $html = view('home.employee.dashboard.user_data', compact('users'))->render();
        $result = array(
            'status' => 1,
            'response' => $html,
            'msg'     => 'Success',
            'offest' => $request['offset'] + $limit,
        );


        return response()->json($result);
    }




    public function page($page_slug)
    {
        $language_code = config('constants.default_company_lang');
        $locale = session()->put('locale');
        $thismodel = Page::leftJoin('language_pages', function ($join) {
            $join->on('language_pages.page_id', '=', 'pages.id');
        });
        $thismodel->leftJoin('languages', function ($join) {
            $join->on('language_pages.language_id', '=', 'languages.id');
        });
        $thismodel->where('pages.page_slug', $page_slug);
        if (!empty($locale)) {
            $thismodel->where('languages.language_code', $locale);
        } else {
            $thismodel->where('languages.language_code', $language_code);
        }

        $thismodel->select([
            'pages.*', 'language_pages.page_name', 'language_pages.page_content', 'language_pages.page_meta_title'
        ]);

        $page_data = $thismodel->first();
        return view('home.page', compact('page_data'));
    }


    public function switchLang(Request $request)
    {
        App::setLocale($request->lang);
        session()->put('locale', $request->lang);

        return redirect()->back();
    }



    public function userLocation(Request $request, $id)
    {

        $filter = $request->query();
        $thismodel = UserLocationTrack::where('user_location_tracks.admin_id', Config::get('auth_detail')['admin_id']);
        $thismodel->where('user_location_tracks.user_id', $id);

        if (!empty($filter['date'])) {
            $start_date_format = mysqlDateFormat($filter['date']);
            $thismodel->whereDate('user_location_tracks.datetime', '=', $start_date_format);
        } else {
            // $date = Config::get('current_date');
            // $start_date_format = mysqlDateFormat($date);
            // $thismodel->whereDate('user_location_tracks.datetime', '=', $start_date_format);

            $date = Config::get('current_date');
            $filter['date'] = $date;
        }
        $thismodel->where('status', 1);
        // pr(getQuery($thismodel));die;
        $userlocations = $thismodel->get();

        $userLocationArray = [];
        foreach ($userlocations as $key => $value) {
            $locationtime = 'Time: ';
            $address = 'Address: ';
            $html = 'LatLong: ';
            $html .= $value->latitude . ',' . $value->longitude . '<br>' . $address . $value->location . '<br>' . $locationtime . prettyDateFormet($value->datetime, 'time');
            $userLocationArray[$key]['from_lat']  =    $value->latitude;
            $userLocationArray[$key]['from_long'] =    $value->longitude;
            $userLocationArray[$key]['colour']    =    "blue";
            $userLocationArray[$key]['time']      =     $html;
            $userLocationArray[$key]['location']  =    $value->location;
            $userLocationArray[$key]['latitude']  =    $value->latitude;
            $userLocationArray[$key]['longitude']  =   $value->longitude;
        }

        return view('home.employee.dashboard.location.index', compact('userLocationArray', 'filter', 'id'));
    }


    public function userDetail($id)
    {;
        $attributes['authorised_person_id'] = Config::get('auth_detail')['id'];
        $attributes['admin_id'] = Config::get('auth_detail')['admin_id'];
        $users = User::getUserData($attributes)->where('id', $id)->first();
        return view('home.employee.dashboard.user_detail', compact('users'));
    }

    public function notice(Request $request)
    {

        $thismodel = Notice::where('status', '1')
            ->where('notices.admin_id', Config::get('auth_detail')['admin_id']);
        $note = $thismodel->orderby('id', 'desc')->get();

        foreach ($note as $key => $value) {
            $imgPath = config('constants.notice_image_path');
            $note[$key]['attachment'] = ImageShow($imgPath, $value['attachment'], 'icon');
        }

        return view('home.employee.dashboard.notice', compact('note'));
    }


    public function userFaq(Request $request)
    {

        return view('home.employee.dashboard.faq');
    }


    public function userWish(Request $request)
    {

        $authdetail = User::getUserDetails(Config::get('auth_detail')['id'], 'emp');
        $celebrationList = upcomingCelebrations($authdetail);
        $todayCelebrationList = todayCelebrations($authdetail);
        // dd($celebrationList);
        return view('home.employee.dashboard.wish', compact('authdetail', 'celebrationList', 'todayCelebrationList'));
    }


    public function getState(Request $request)
    {

        $data['states'] = getStatelist($request->country_id);
        return response()->json($data);
    }

    public function getCity(Request $request)
    {
        $data['cities'] = getCitylist($request->state_id);
        return response()->json($data);
    }

    public function contact(Request $request)
    {
        $att = [];
        $att['language_code'] = session()->get('locale');
        $att['page_slug'] = 'contact-us';
        $page_data = getPage($att);
        return view('home.contact', compact('page_data'));
    }

    public function about(Request $request)
    {
        $att = [];
        $att['language_code'] = session()->get('locale');
        $att['page_slug'] = 'about-us';
        $page_data = getPage($att);
        return view('home.about', compact('page_data'));
    }

    public function getUserPresentDetail(Request $request)
    {
        $request->date = date('Y-m-d', strtotime($request->date));
        $attributes = $request->all();
        $attendances = Attendance::getTodayAttendanceData($request);

        if (!empty($attendances)) {
            $result = array(
                "status" => 1,
                "data" => $attendances,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        return response()->json($result);
    }

    public function userNotification(Request $request)
    {

        $authdetail = User::getUserDetails(Config::get('auth_detail')['id'], 'emp');
        $celebrationList = upcomingCelebrations($authdetail);
        $imgPath = public_path(config('constants.notification_image_path'));
        // $todayCelebrationList = todayCelebrations($authdetail);
        $notification = UserNotification::where('admin_id', $authdetail['admin_id'])->where('user_id', $authdetail['id'])->get();
        foreach ($notification as  $key => $notice) {
            if (!empty($notice['image']) && file_exists($imgPath . $notice['image'])) {
                $notification[$key]['image'] = url(config('constants.notification_image_path') . $notice['image']);
            }
        }
        return view('home.employee.dashboard.notification', compact('notification'));
    }





    public function getOtp(Request $request)
    {
        // dd($request);
        $user_phone = $request->phone;
        $user = User::where('mobile', $user_phone)->first();
        // dd($user);
        if (!empty($user)) {
            // if (!empty(Config::get('sms_live_mode'))) {
            //     $rand_number = rand(100000, 999999);
            //     $msg = $rand_number . ' is OTP for ' . Config::get('company_name') . ' login and valid for the next 30 minutes';
            //     MyCommand::send_sms($user_phone, $msg);
            // } else {
            //     $rand_number = config('constants.default_otp_code');
            // }
            $rand_number = 230623;
            $user_otp_array = array('mobile_otp' => $rand_number);
            if (!empty($user_otp_array)) {
                $res = User::where('mobile', $user_phone)->update($user_otp_array);
            }
            if (
                !empty($res)
            ) {
                $result = array(
                    'status' => 1,
                    'phone' => $user_phone,
                    'msg' => 'Your OTP is Send Successfully',
                );
            } else {
                $result = array(
                    'status' => 0,
                    'msg' => 'Something went wrong',
                );
            }
        } else {
            $result = array(
                'status' => 0,
                'msg' => 'Do not exit',
            );
        }

        return response()->json($result);
    }


    public function verifyOtp(Request $request)
    {
        // dd($request);
        $phone = $request->phone;
        $otp = $request->otp;
        $status = 1;
        $user = User::where('mobile', "=", $phone)->first();
        if (($user->otp == $otp)) {

            $result = array(
                'status' => 1,
                'msg' => 'OTP is verified',
            );
            $userData = User::where('id', $user->id)->first();
            // DD($userData);
            $attributes['username'] = $userData->username;
            $attributes['password'] = $userData->password;

            if (filter_var($attributes['username'], FILTER_VALIDATE_EMAIL)) {
                $attributes['email'] = $attributes['username'];
                unset($attributes['username']);
            }
            unset($attributes['_token']);
            if (Auth::guard('front-user')->attempt($attributes)) {
                session()->regenerate();

                // dd(Auth::guard('front-admin')->user());

                // dd('sdfd');
                // $roles = Role::where('id', '=', Auth::guard('front-user')->user()->role_id)->first();
                // if (!$roles->status) {
                //     Auth::guard('front-user')->logout();
                //     return back()->with('error', 'Your role is inactive. Contact with Authorises Person');
                // }
                // if (Auth::guard('front-user')->user()->role_id != config('constants.employee_role_id')) {
                //     Auth::guard('front-user')->logout();
                //     return back()->with('error', 'Unauthorises User');
                // }

                // $notice = Notice::where('status', '1')->where('type', 'welcome_message')->orderBy('id', 'desc')->first();
                // // dd($notice);
                // if (!Auth::guard('front-user')->user()->status) {

                //     Auth::guard('front-user')->logout();
                //     return back()->with('error', 'Your account is inactive. Contact with Authorises Person');
            }
            return redirect()->route('employeedashboard')->with('success', 'You are log in.');;
            // } else {
            //     return back()->withErrors(['email' => 'Username or password invalid.']);
            // }
        } else {
            $result = array(
                'status' => 0,
                'msg' => 'Invalid Otp or Expiry',
            );
        }
        return response()->json($result);
    }
}



//
