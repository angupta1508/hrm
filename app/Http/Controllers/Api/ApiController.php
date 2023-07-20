<?php

namespace App\Http\Controllers\Api;

use PDF;
use App\Models\Api;
use App\Models\Mood;
use App\Models\Role;
use App\Models\User;
use App\Models\Wish;
use App\Models\Salary;
use App\Models\UserOtp;
use App\Models\Employee;
use App\Models\MoodType;
use App\Models\LeaveType;
use App\Models\Attendance;
use App\Models\UserPolicy;
use App\Exports\ExportUser;
use App\Models\SalarySetup;
use Illuminate\Http\Request;
use App\Models\AttendanceLog;
use App\Models\EmailTemplates;
use App\Models\AttendanceReason;
use App\Models\LeaveApplication;
use App\Models\ManualAttendance;
use App\Models\UserNotification;
use App\Models\UserLocationTrack;
use App\Console\Commands\MyCommand;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Excel as ExcelWriter;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{

    private function checkUserStatus($user_data)
    {
        //        print_r($user_data);exit();
        if (empty($user_data)) {
            $data['_error'] = "Mobile Number Not Valid";
            return $data;
        } else {
            $data['_success'] = 'Correct Credentials';
            return $data;
        }
    }

    public function welcome(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_id' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $attributes['type'] = 'welcome_message';
        $notice = getNotices($attributes, 1);


        $admin_id = !empty($attributes['admin_id']) ? $attributes['admin_id'] : '';
        $setting = getAllSettings($admin_id);

        $data = [];
        if ($setting->count() > 0) {
            foreach ($setting as $v) {
                $master_data[$v['setting_name']] = !empty($v['setting_value']) ? $v['setting_value'] : '';
            }

            $data = array(
                'company_name' => !empty($master_data['company_name']) ? $master_data['company_name'] : '',
                'email' => !empty($master_data['email']) ? $master_data['email'] : '',
                'postal_code' => !empty($master_data['postal_code']) ? $master_data['postal_code'] : '',
                'city' => !empty($master_data['city']) ? $master_data['city'] : '',
                'state' => !empty($master_data['state']) ? $master_data['state'] : '',
                'country' => !empty($master_data['country']) ? $master_data['country'] : '',
                'address' => !empty($master_data['address']) ? $master_data['address'] : '',
                'mobile_no' => !empty($master_data['mobile_no']) ? $master_data['mobile_no'] : '',
                'razorpay_id' => !empty($master_data['razorpay_id']) ? $master_data['razorpay_id'] : '',
                'razorpay_key' => !empty($master_data['razorpay_key']) ? $master_data['razorpay_key'] : '',
                'website_favicon' => asset(config('constants.setting_image_path') . $master_data['logo']),
                'back_website_logo' => asset(config('constants.setting_image_path') . $master_data['logo']),
                'logo' => asset(config('constants.setting_image_path') . $master_data['logo']),
                'welcome_message' => !empty($notice['description']) ? $notice['description'] : '',
            );
        }
        // $data=$notices;

        $result = array(
            'status' => 1,
            'data' => $data,
            'msg' => 'Success',
        );
        return response()->json($result);
    }

    public function checkAdminID(Request $request)
    {

        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'company_code' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $data = User::where('company_code', $attributes['company_code'])->where('role_id', config('constants.admin_role_id'))->first();

        if (!empty($data)) {
            $result = array(
                'status' => 1,
                'data' => $data,
                'msg' => 'Success',
            );
        } else {
            $result = array(
                'status' => 0,
                'msg' => 'Invalid admin Id',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function login(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'username' => ['required'],
            'password' => ['required'],
            'admin_id' => ['required'],
            'user_fcm_token' => ['nullable'],
            'device_id' => ['nullable'],
            // 'device_id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $username = $attributes['username'];

        $user = User::leftJoin('employees', function ($join) {
            $join->on('users.id', '=', 'employees.user_id');
        }); //->first();
        $user->where(function ($query) use ($username) {
            $query->where('users.email', $username)
                ->orWhere('users.username', $username)
                ->orWhere('employees.employee_code', $username);
        });
        $user = $user->where('users.admin_id', $attributes['admin_id'])->select(['users.*'])->first();
        // pr(getQuery($user));die;
        // dd($user);
        if (!empty($user)) {
            $roles = Role::where('id', '=', $user->role_id)->first();
            // dd($roles);
            if (!$roles->status) {
                return response()->json([
                    "status" => 0,
                    "msg" => "Your role is inactive. Please contact to Authorised Person",
                ]);
            }
            if ($roles->id != config('constants.employee_role_id')) {
                return response()->json([
                    "status" => 0,
                    "msg" => "Unauthorized User. Please contact to Authorised Person",
                ]);
            }

            if (!$user->status) {
                return response()->json([
                    "status" => 0,
                    "msg" => "Your account is inactive. Please contact to Authorised Person",
                ]);
            }

            if (!Hash::check($attributes['password'], $user->password)) {
                return response()->json([
                    "status" => 0,
                    "msg" => "Invalid username or password",
                ]);
            }

            if (empty($user->device_id)) {
                $user->update(['device_id' => $attributes['device_id']]);
            } else {
                if ($user->device_id != $attributes['device_id']) {
                    return response()->json([
                        "status" => 0,
                        "msg" => "You are already login another device.",
                    ]);
                }
            }

            $user_id = $user->id;
            $api_key = Api::generateUserApiKey($user_id);
            if (!empty($attributes['user_fcm_token'])) {
                $user->update(['user_fcm_token' => $attributes['user_fcm_token']]);
            }
            $data = User::getUserDetails($user_id, 'emp');
            $data['user_auth_key'] = $api_key;
            $result = array(
                "status" => 1,
                "data" => $data,
                "msg" => 'You are logged in.',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'Username or password invalid',
            );
        }

        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function forgotPassword(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'username' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $user = User::where('email', $attributes['username'])->orwhere('username', $attributes['username'])->orwhere('user_uni_id', $attributes['username'])->first();
        if (!empty($user)) {
            $roles = Role::where('id', '=', $user->role_id)->first();
            // dd($roles);
            if (!$roles->status) {
                return response()->json([
                    "status" => 0,
                    "msg" => "Your role is inactive. Please contact to Authorised Person",
                ]);
            }
            if ($roles->id != config('constants.employee_staff_role_id')) {
                return response()->json([
                    "status" => 0,
                    "msg" => "Unauthorized User. Please contact to Authorised Person",
                ]);
            }

            if (!$user->status) {
                return response()->json([
                    "status" => 0,
                    "msg" => "Your account is inactive. Please contact to Authorised Person",
                ]);
            }
            $user_phone = $user->mobile;
            $hidden_mobile = 'XXXXXX' . substr($user_phone, -4);
            $user_email = $user->email;
            list($username, $domain) = explode('@', $user_email);
            $hidden_username = substr($username, 0, 3) . str_repeat('X', strlen($username) - 3);
            $hidden_email = strtoupper($hidden_username) . '@' . $domain;

            if (!empty(Config::get('sms_live_mode')) && !empty($user->mobile)) {
                $rand_number = rand(100000, 999999);
                $msg = $rand_number . ' is OTP for ' . Config::get('company_name') . ' forgot Password and valid for the next 30 minutes';
                MyCommand::send_sms($user->mobile, $msg);
            } else {
                $rand_number = config('constants.default_otp_code');
            }
            $template = EmailTemplates::where('template_code', 'forget-password')->first();
            if (!empty($template) && !empty($user->email)) {
                $other = [
                    'otp_code' => $rand_number,
                ];
                MyCommand::sendMail($template, $user, $other);
            }
            $expires_at = date('Y-m-d H:i:s', strtotime('30 minutes'));

            $user_otp_array = array('phone' => $user_phone, 'login_otp' => $rand_number, 'expires_at' => $expires_at);

            $data = UserOtp::where('phone', "=", $user_phone)->first();

            if (!empty($data)) {
                $data_count = $data->count();
            }
            if (empty($data_count)) {
                UserOtp::create($user_otp_array);
            } else {
                UserOtp::where('phone', $user_phone)->update($user_otp_array);
            }
            $result = array(
                'status' => 1,
                'data' => $user_otp_array,
                'msg' => 'Success! We\'ve sent a OTP to your email/mobie inbox. Please check on your Email Id ' . $hidden_email . ' & Mobile no ' . $hidden_mobile . '',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'Username or password invalid',
            );
        }

        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function resetPassword(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'username' => ['required'],
            'otp' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $user_otp = $attributes['otp'];
        $user = User::where('email', $attributes['username'])->orwhere('username', $attributes['username'])->orwhere('user_uni_id', $attributes['username'])->first();
        if (!empty($user)) {
            $res = UserOtp::where('phone', "=", $user->mobile)->first();
            if (empty($res)) {
                $result = array(
                    'status' => 0,
                    'msg' => 'Invalid Username',
                );
                return response()->json($result);
            }
            if (!empty($user_otp)) {
                // dd(Config::get('current_datetime'));
                if (($res->login_otp == $user_otp && $res->expires_at > Config::get('current_datetime')) || $user_otp == config('constants.default_otp_code')) {
                    $result = array(
                        'status' => 1,
                        'data' => $user,
                        'msg' => 'OTP Verified Successfully',
                    );
                } else {
                    $result = array(
                        'status' => 0,
                        'msg' => 'Incorrect Otp Entered.',
                    );
                }
            } else {
                $result = array(
                    'status' => 0,
                    'msg' => 'Invalid Email Or Phone',
                );
            }
        } else {
            $result = array(
                'status' => 0,
                'msg' => 'Invalid Username',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function changePassword(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'password' => ['required', 'min:6'],
            'old_password' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $user_id = $attributes['user_id'];
        unset($attributes['user_id']);
        $Check_data = User::where('id', "=", $user_id)->first();
        $attributes['password'] = bcrypt($attributes['password']);
        $CheckNo = '';
        if (!empty($Check_data)) {
            $user_id = $Check_data->id;

            if (!$Check_data->status) {
                return response()->json([
                    "status" => 0,
                    "msg" => "Your account is inactive. Please contact to Authorised Person",
                ]);
            }


            if (!Hash::check($attributes['old_password'], $Check_data->password)) {
                return response()->json([
                    "status" => 0,
                    "msg" => "Incorrect Old Password",
                ]);
            }

            // $CheckNo = $Check_data->count();
        } else {
            return response()->json([
                "status" => 0,
                "msg" => "User is Invalid",
            ]);
        }
        if ($Check_data) {
            $res = $Check_data->update($attributes);
            if ($res) {
                $result = array(
                    "status" => 1,
                    "msg" => 'Password Change Successfully',
                );
            } else {
                $result = array(
                    "status" => 0,
                    "msg" => 'Something went wrong.',
                );
            }
            Api::updateapiLogs($api, $result);
            return response()->json($result);
        }
    }

    public function attendancePunch(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'punch_type' => ['required'],
            'from_where' => ['required'],
            'image' => ['nullable'],
            'shift_id' => ['nullable'],
            'punchInOut' => ['required'],
            'lat' => ['required'],
            'long' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        if ($attributes['punchInOut'] == 'in') {
            $result = Attendance::punchIn($request);
        } elseif ($attributes['punchInOut'] == 'out') {
            $result = Attendance::punchOut($request);
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function wishesList(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['nullable'],
            'offset' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }

        $attributes = $request->all();
        $offset =  !empty($attributes['offset']) ? $attributes['offset'] : '0';
        $page_limit = config('constants.api_page_limit');
        $wish = Wish::where('admin_id', $attributes['admin_id']);

        $wish->offset($offset)->limit($page_limit);
        if (!empty($attributes['user_id'])) {
            $wish = $wish->where('user_id', $attributes['user_id']);
        }
        $wishes = $wish->get();
        if (!empty($wishes)) {
            $result = array(
                "status" => 1,
                "data" => $wishes,
                'offset' =>  $offset + $page_limit,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function addWishes(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'sender_id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $wishes = Wish::create($attributes);

        if ($wishes) {
            $result = array(
                "status" => 1,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'Something went wrong',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function attendanceList(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'offset' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $offset = !empty($attributes['offset']) ? $attributes['offset'] : '0';
        $page_limit = config('constants.api_page_limit');
        $attendance = Attendance::orderBy('attendance_date', 'DESC');
        $attendance = $attendance->where('admin_id', $attributes['admin_id']);
        $attendance = $attendance->where('user_id', $attributes['user_id']);
        $attendance->offset($offset)->limit($page_limit);
        $attendances = $attendance->get();
        foreach ($attendances as $key => $value) {
            $value->to_time = !empty($value->to_time) ? $value->to_time : '----/--/-- --:--:--';
        }

        if (!empty($attendances)) {
            $result = array(
                "status" => 1,
                "data" => $attendances,
                'offset' =>  $offset + $page_limit,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function attendanceApproval(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'ids' => ['required'],
            'status' => ['required'],
            'approved_by' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $ids = str_replace('[', '', $attributes['ids']);
        $ids = str_replace(']', '', $ids);
        $ids = explode(',', $ids);
        foreach ($ids as $key => $id) {
            $request->id = $id;
            $attendance = Attendance::attendanceApproval($request);
        }

        if ($attendance) {
            $result = array(
                "status" => 1,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'Something went wrong',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function leaveTypeLists(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'offset' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $offset =  !empty($attributes['offset']) ? $attributes['offset'] : '0';
        $page_limit = config('constants.api_page_limit');
        $leave = LeaveType::where('status', 1);
        $leave->offset($offset)->limit($page_limit);
        $leaves = $leave->get();


        if (!empty($leaves)) {
            $result = array(
                "status" => 1,
                "data" => $leaves,
                'offset' =>  $offset + $page_limit,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function leaveLists(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'status' => ['required'],
            'offset' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $offset =  !empty($attributes['offset']) ? $attributes['offset'] : '0';
        $page_limit = config('constants.api_page_limit');
        $user = User::where('id', $attributes['user_id'])->first();
        $leave = LeaveApplication::leftJoin('leave_types', function ($join) {
            $join->on('leave_applications.leave_type_id', '=', 'leave_types.id');
        })->select(['leave_types.leave_type', 'leave_applications.*'])->where('leave_applications.admin_id', $attributes['admin_id'])
            ->where('leave_applications.status', $attributes['status']);
        $leave = $leave->where('leave_applications.user_id', $attributes['user_id']);
        $leave->offset($offset)->limit($page_limit);
        $leaves = $leave->get();


        foreach ($leaves as $key => $value) {
            $imgPath = public_path(config('constants.user_image_path'));
            if (!empty($user->profile_image) && file_exists($imgPath . $user->profile_image)) {
                $value->profile_image = url(config('constants.user_image_path') . $user->profile_image);
            } else {
                $value->profile_image = asset(config('constants.default_user_image_path'));
            }
            $value->name = $user->name;
        }
        if (!empty($leaves)) {
            $result = array(
                "status" => 1,
                "data" => $leaves,
                'offset' =>  $offset + $page_limit,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function leaveRequestList(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'status' => ['required'],
            'offset' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }

        $attributes = $request->all();
        $offset =  !empty($attributes['offset']) ? $attributes['offset'] : '0';
        $page_limit = config('constants.api_page_limit');
        $leave =  LeaveApplication::leftJoin('leave_types', function ($join) {
            $join->on('leave_applications.leave_type_id', '=', 'leave_types.id');
        });

        $leave->select(['leave_applications.*', 'leave_types.leave_type as leave_type_name']);
        $leave->where('leave_applications.admin_id', $attributes['admin_id']);
        $leave->where('leave_applications.authorised_person_id', $attributes['user_id']);
        $leave->where('leave_applications.status', $attributes['status']);
        $offset = $attributes['offset'];
        $leave->offset($offset)->limit($page_limit);
        $leave->orderBy('leave_applications.id', 'desc');
        $leaves = $leave->get();

        foreach ($leaves as $key => $value) {
            $userData = User::getUserDetails($value->user_id);
            $value->profile_image = $userData->profile_image;
            $value->name = $userData->name;
        }

        if (!empty($leaves)) {
            $result = array(
                "status" => 1,
                "data" => $leaves,
                'offset' =>  $offset + $page_limit,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function addLeave(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'leave_type_id' => ['required'],
            'request_start_date' => ['required'],
            'request_end_date' => ['required'],
            'request_hard_copy' => ['nullable'],
            'request_remark' => ['required'],
            'request_leave_type_out_id' => ['required'],
            'request_leave_type_in_id' => ['required'],
            'request_day' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $attributes['request_date'] = Config::get('current_date');
        $userData = User::getUserDetails($request->user_id, 'emp');
        $attributes['authorised_person_id'] = $userData->authorised_person_id;
        $attributes['shift_id'] = $userData->shift_id;

        $ary = (object)array(
            'user_id'           => $request->user_id,
            'date'              => $request->request_start_date,
            'admin_id'          =>  $request->admin_id,
            'leave_type_id'     => $request->leave_type_id,
        );

        $balance =  LeaveApplication::checkLeaveBalance($ary);
        // dd($balance);
        if ($request->request_day > $balance->available_leave_blance) {
            $result = array(
                "status" => 0,
                "msg" => 'You do not enough leave balance.Your have avialable leaves is ' . $balance->available_leave_blance . '',
            );
            return response()->json($result);
        }
        if (!empty($attributes['request_hard_copy'])) {
            $imgKey     =   'request_hard_copy';
            $imgPath    =   public_path(config('constants.leave_image_path'));
            $filename = UploadImage($request, $imgPath, $imgKey);
            if (!empty($filename)) {
                $attributes['request_hard_copy'] = $filename;
            }
        }
        $leave = LeaveApplication::create($attributes);
        MyCommand::fireBaseNotification($userData->authorised_person_id, 'Leave Request', $userData->name . 'is sending a leave request for ' . $leave->request_day . ' day');

        if ($leave) {
            $result = array(
                "status" => 1,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'Something went wrong',
            );
        }

        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function editLeave(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'id' => ['required'],
            'leave_type_id' => ['required'],
            'request_start_date' => ['required'],
            'request_end_date' => ['required'],
            'request_hard_copy' => ['nullable'],
            'request_remark' => ['required'],
            'request_leave_type_out_id' => ['required'],
            'request_leave_type_in_id' => ['required'],
            'request_day' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();

        $ary = (object)array(
            'user_id'           => $request->user_id,
            'date'              => $request->request_start_date,
            'admin_id'          => $request->admin_id,
            'leave_type_id'     => $request->leave_type_id,
        );

        $balance =  LeaveApplication::checkLeaveBalance($ary);
        // dd($balance);
        if ($request->request_day > $balance->available_leave_blance) {
            $result = array(
                "status" => 0,
                "msg" => 'You do not enough leave balance.Your have avialable leaves is ' . $balance->available_leave_blance . '',
            );
            return response()->json($result);
        }


        if (!empty($attributes['request_hard_copy'])) {
            $imgKey     =   'request_hard_copy';
            $imgPath    =   public_path(config('constants.leave_image_path'));
            $filename = UploadImage($request, $imgPath, $imgKey);
            if (!empty($filename)) {
                $attributes['request_hard_copy'] = $filename;
            }
        }
        $leave = LeaveApplication::where('id', $attributes['id'])->first();
        // dd($attributes);
        if ($leave->status != 0) {
            $result = array(
                "status" => 1,
                "msg" => 'Leave is not Editable',
            );
        } else {
            $leave->update($attributes);
            if ($leave) {
                $result = array(
                    "status" => 1,
                    "msg" => 'Leave Updated Successfully',
                );
            } else {
                $result = array(
                    "status" => 0,
                    "msg" => 'Something went wrong',
                );
            }
        }

        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function deleteLeave(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'ids' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $ids = str_replace('[', '', $attributes['ids']);
        $ids = str_replace(']', '', $ids);
        $ids = explode(',', $ids);
        foreach ($ids as $key => $id) {
            $leave = LeaveApplication::where([['admin_id', $attributes['admin_id']], ['id', $id]])->delete();
        }

        if ($leave) {
            $result = array(
                "status" => 1,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'Something went wrong',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function leaveApproval(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'ids' => ['required'],
            'status' => ['required'],
            'approved_by' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $ids = str_replace('[', '', $attributes['ids']);
        $ids = str_replace(']', '', $ids);
        $ids = explode(',', $ids);
        foreach ($ids as $key => $id) {
            $leaveData =    LeaveApplication::where('id', $id)->first()->toArray();
            $array = [
                'status'                        =>  !empty($request['status']) ? $request['status'] : '',
                'approve_start_date'            =>  !empty($leaveData['request_start_date']) ? $leaveData['request_start_date'] : '',

                'approve_leave_type_out_id'     =>  !empty($leaveData['request_leave_type_out_id']) ? $leaveData['request_leave_type_out_id'] : '',

                'approve_end_date'              =>  !empty($leaveData['request_end_date']) ? $leaveData['request_end_date'] : '',

                'approve_leave_type_in_id'      =>  !empty($leaveData['request_leave_type_in_id']) ? $leaveData['request_leave_type_in_id'] : '',

                'approve_remark'                =>  '',

                'approve_day'                   =>  !empty($leaveData['request_day']) ? $leaveData['request_day'] : '',
                'approved_by'                   =>  !empty($request['approved_by']) ? $request['approved_by'] : '',
            ];
            $leave =  LeaveApplication::leaveApproval($leaveData, $array);
        }

        if ($leave) {
            $result = array(
                "status" => 1,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'Something went wrong',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function otpSend(Request $request)
    {
        // dd($request);
        $api = Api::saveapiLogs($request->all());
        $result = [];
        $validator = Validator::make($request->all(), [
            'phone' => ['required'],
            'admin_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $user_phone = $attributes['phone'];

        if (!empty(Config::get('sms_live_mode'))) {
            $rand_number = rand(100000, 999999);
            $msg = $rand_number . ' is OTP for ' . Config::get('company_name') . ' login and valid for the next 30 minutes';
            MyCommand::send_sms($user_phone, $msg);
        } else {
            $rand_number = config('constants.default_otp_code');
        }

        $expires_at = date('Y-m-d H:i:s', strtotime('30 minutes'));

        $user_otp_array = array('phone' => $user_phone, 'login_otp' => $rand_number, 'expires_at' => $expires_at);
        $data = UserOtp::where('phone', "=", $user_phone);

        if ($data->count() > 0) {
            $res = UserOtp::where('phone', $user_phone)->update($user_otp_array);
        } else {
            $res = UserOtp::create($user_otp_array);
        }

        if ($res) {
            $result = array(
                'status' => 1,
                'data' => $user_otp_array,
                'msg' => 'Your OTP is Send Successfully',
            );
        } else {
            $result = array(
                'status' => 0,
                'msg' => 'Something went wrong',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }



    public function otpVerify(Request $request)
    {

        $api = Api::saveapiLogs($request->all());
        $result = [];
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'phone' => ['required'],
            'user_ios_token' => ['nullable'],
            'user_fcm_token' => ['nullable'],
            'device_id' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $user_phone = $attributes['phone'];
        $user = User::where('mobile', "=", $user_phone)->where('admin_id', $attributes['admin_id'])->first();
        if (empty($user)) {
            $result = array(
                'status' => 0,
                'msg' => 'Invalid User',
            );
        } else {
            $saveData = [];
            if (!empty($attributes['user_fcm_token'])) {
                $saveData['user_fcm_token'] = $attributes['user_fcm_token'];
            }
            if (!empty($attributes['user_ios_token'])) {
                $saveData['user_ios_token'] = $attributes['user_ios_token'];
            }
            if (!empty($attributes['device_id'])) {
                User::where('id', $user->id)->update(['device_id' => $attributes['device_id']]);
            }
            if (!empty($saveData)) {
                User::where('mobile', $user_phone)->update($saveData);
            }
            $user_id = $user->id;
            $api_key = Api::generateUserApiKey($user_id);
            $data = User::getUserDetails($user_id, 'emp');

            $data['user_auth_key'] = $api_key;


            $result = array(
                'status' => 1,
                "data" => $data,
                'msg' => 'OTP Verified Successfully',
            );
        }
        // if ($request['web'] === 'web' && $result['msg'] === "OTP Verified Successfully") {
        //     $use = UserOtp::where('phone', "=", $request->phone)->where('login_otp', $attributes['login_otp'])->first();
        //     //   dd ($user);
        //     if (!empty($use)) {
        //         //   $ii =   Auth::login($user);
        //         $xc = Auth::login($user);
        //         dd($xc, "kjlkjjk");
        //         return redirect('/employee-dashboard');
        //     }
        // }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function attendanceReasons(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $attendanceReason = AttendanceReason::where('admin_id', $attributes['admin_id'])->where('status', 1);
        $attendanceReasons = $attendanceReason->get();

        if (!empty($attendanceReasons)) {
            $result = array(
                "status" => 1,
                "data" => $attendanceReasons,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function celebrations(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'offset' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $offset =  !empty($attributes['offset']) ? $attributes['offset'] : '0';
        $page_limit = config('constants.api_page_limit');
        $celebrationList = upcomingCelebrations($request);
        $todayCelebrationList = todayCelebrations($request);
        if ($celebrationList) {
            $result = array(
                "status" => 1,
                "celebration_list" => $celebrationList,
                "today_celebration_list" => $todayCelebrationList,
                'offset' =>  $offset + $page_limit,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function moodTypes(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $mood = MoodType::where('status', 1);
        $moods = $mood->get();

        if (!empty($moods)) {
            $result = array(
                "status" => 1,
                "data" => $moods,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function addTodayMood(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'type_id' => ['required'],
            'remark' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $leave = Mood::create($attributes);

        if ($leave) {
            $result = array(
                "status" => 1,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'Something went wrong',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function getTodayAttendance(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'shift_id' => ['required'],
            'date'  => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $data = Attendance::getTodayAttendanceData($request);
        if (!empty($data)) {
            $result = array(
                "status" => 1,
                "data" => $data,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function calender(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'month' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }

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

        foreach ($attendances as $key => $value) {
            $value->from_time = !empty($value->from_time) ? $value->from_time : '----/--/-- --:--:--';
            $value->to_time = !empty($value->to_time) ? $value->to_time : '----/--/-- --:--:--';
        }

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
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function manualAttendence(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'attendance_reason_id' => ['required'],
            'from_time' => ['nullable'],
            'to_time' => ['nullable'],
            'request_remark' => ['required'],
            'attendance_date' => ['required'],
            'request_hard_copy' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $userData = User::getUserDetails($attributes['user_id'], 'emp');
        $attributes['authorised_person_id'] = !empty($userData->authorised_person_id) ? $userData->authorised_person_id : '';
        $attributes['shift_id'] = !empty($userData->shift_id) ? $userData->shift_id : '';
        if (!empty($attributes['request_hard_copy'])) {
            $imgKey     =   'request_hard_copy';
            $imgPath    =   public_path(config('constants.attendance_image_path'));
            $filename = UploadImage($request, $imgPath, $imgKey);
            if (!empty($filename)) {
                $attributes['request_hard_copy'] = $filename;
            }
        }
        $attributes['is_manual_attendance'] = 1;

        $attendance =  ManualAttendance::create($attributes);
        if ($attendance) {
            MyCommand::fireBaseNotification($userData->authorised_person_id, 'Manual Attendance Request', $userData->name . 'is sending a manal attendance request for ' . prettyDateFormet($attributes['attendance_date'], 'date'));
            $result = array(
                "status" => 1,
                "msg" => 'Manual Ateendance created successfully.',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'Something went Wrong',
            );
        }

        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function editManualAttendence(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'attendance_reason_id' => ['required'],
            'from_time' => ['required'],
            'to_time' => ['required'],
            'request_remark' => ['required'],
            'attendance_date' => ['required'],
            'request_hard_copy' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        if (!empty($attributes['request_hard_copy'])) {
            $imgKey     =   'request_hard_copy';
            $imgPath    =   public_path(config('constants.attendance_image_path'));
            $filename = UploadImage($request, $imgPath, $imgKey);
            if (!empty($filename)) {
                $attributes['request_hard_copy'] = $filename;
            }
        }

        $attendance = ManualAttendance::where('attendance_date', $request->attendance_date)->where('user_id', $request->user_id)->first();
        // dd($attributes);
        if ($attendance->status != 0) {
            $result = array(
                "status" => 1,
                "msg" => 'ManualAttendance is not Editable',
            );
        } else {
            $attendance->update($attributes);
            if ($attendance) {
                $result = array(
                    "status" => 1,
                    "msg" => 'ManualAttendance Updated Successfully',
                );
            } else {
                $result = array(
                    "status" => 0,
                    "msg" => 'Something went wrong',
                );
            }
        }

        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function deleteManualAttendance(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'ids' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $ids = str_replace('[', '', $attributes['ids']);
        $ids = str_replace(']', '', $ids);
        $ids = explode(',', $ids);
        foreach ($ids as $key => $id) {
            $attendance = ManualAttendance::where([['admin_id', $attributes['admin_id']], ['user_id', $attributes['user_id']],  ['id', $id]])->delete();
        }
        if ($attendance) {
            $result = array(
                "status" => 1,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'Something went wrong',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }


    //for user
    public function mannualAttendanceList(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'status' => ['nullable'],
            'offset' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $offset = !empty($attributes['offset']) ? $attributes['offset'] : '0';
        $page_limit = config('constants.api_page_limit');
        $attendances = ManualAttendance::manualAttendanceList($request);
        if (!empty($attendances)) {
            $result = array(
                "status" => 1,
                "data" => $attendances,
                'offset' =>  $offset + $page_limit,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    //for supervisor
    public function mannualAttendanceRequestList(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'status' => ['required'],
            'offset' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $offset = !empty($attributes['offset']) ? $attributes['offset'] : '0';
        $page_limit = config('constants.api_page_limit');
        $attendance = ManualAttendance::leftJoin('attendance_reasons', function ($join) {
            $join->on('manual_attendances.attendance_reason_id', '=', 'attendance_reasons.id');
        });
        $attendance->select(['manual_attendances.*', 'attendance_reasons.name as reason_name']);
        $attendance->where('manual_attendances.admin_id', $attributes['admin_id']);
        $attendance->where('manual_attendances.authorised_person_id', $attributes['user_id']);
        $attendance->where('manual_attendances.status', $attributes['status']);
        $attendance->offset($offset)->limit($page_limit);
        $attendances = $attendance->orderBy('manual_attendances.id', 'DESC')->get();


        foreach ($attendances as $key => $value) {
            $user =  User::where('id', $value->user_id)->first();
            $value->name = $user->name;
            $imgPath = public_path(config('constants.user_image_path'));
            if (!empty($user->profile_image) && file_exists($imgPath . $user->profile_image)) {
                $value->profile_image = url(config('constants.user_image_path') . $user->profile_image);
            } else {
                $value->profile_image = asset(config('constants.default_user_image_path'));
            }
            $value->apply_time = niceDateFormet($value->created_at);
        }

        if (!empty($attendances)) {
            $result = array(
                "status" => 1,
                "data" => $attendances,
                'offset' =>  $offset + $page_limit,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    ////Soumya
    public function notice(Request $request)
    {
        $api = Api::saveapilogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }

        $attributes = $request->all();
        $attributes['type'] = 'announce';
        $announcements = getNotices($attributes, 0);

        $attributes['type'] = 'download';
        $downloads = getNotices($attributes, 0);

        $data = [
            'announcements' => $announcements,
            'downloads' => $downloads,
        ];

        if (!empty($data)) {
            $result = array(
                "status" => 1,
                "data" => $data,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function addLocationTrack(Request $request)
    {
        $api = Api::saveapilogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
            'location' => ['nullable'],
            'area' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }

        $attributes = $request->all();
        $attributes['datetime'] = config('current_datetime');
        $attributes['status'] = 1;
        $location = UserLocationTrack::create($attributes);

        if (!empty($location)) {
            $result = array(
                "status" => 1,
                "msg" => 'Saved Successfully',
            );
        } else {
            $result = array(
                'status' => 0,
                'msg' => "Data Not Saved"
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function leaveBlance(Request $request)
    {
        $api = Api::saveapilogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'leave_type_id' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $request->date = Config::get('current_date');
        $balance =  LeaveApplication::checkLeaveBalance($request);

        if (!empty($balance)) {
            $result = array(
                "status" => 1,
                "data" => $balance,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function salarySlipGenrate(Request $request)
    {
        $api = Api::saveapilogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'month' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }

        $salarey = Salary::where('admin_id', $request->admin_id)->where('user_id', $request->user_id);
        $salarey->where('salary_name', $request->month);
        $salary = $salarey->first();
        if (!empty($salary)) {
            $url = Salary::slipPdfGenrate($request);
            if ($url) {
                $result = array(
                    "status" => 1,
                    "url" => $url,
                    "msg" => 'Success',
                );
            } else {
                $result = array(
                    "status" => 0,
                    "msg" => 'Something went wrong',
                );
            }
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'Your slip is not genrated. Contact Your Authorised Person',
            );
        }

        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function attendanceLog(Request $request)
    {
        $api = Api::saveapilogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'month' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }

        $thismodel = AttendanceLog::leftJoin('users', function ($join) {
            $join->on('attendance_logs.user_id', '=', 'users.id');
        });
        $thismodel->where('attendance_logs.user_id', $request->user_id);
        $thismodel->where('attendance_logs.admin_id', $request->admin_id);
        if (!empty($request->month)) {
            $year  = date('Y', strtotime($request->month));
            $month  = date('m', strtotime($request->month));
            $thismodel->whereYear('attendance_logs.created_at', $year);
            $thismodel->whereMonth('attendance_logs.created_at', $month);
        }
        $headings = [
            "User",
            "Punch Type", "From Where", "Punch Time",  "Created At",
        ];
        $thismodel->select([
            'users.name',
            'attendance_logs.punch_type', 'attendance_logs.from_where', 'attendance_logs.punch_time',
            'attendance_logs.created_at',
        ]);
        $records = $thismodel->get();

        $userData = User::getUserDetails($request->user_id, 'emp');
        $tabel_keys = [];
        if ($records->count() > 0) {
            $tabel_keys = array_keys($records[0]->toArray());
        }

        $variabls = [
            'top_heading' => $userData->name . ' AttendanceLog List',
            'headings' => $headings,
            'tabel_keys' => $tabel_keys,
            'records' => $records,
        ];

        $pdf =  PDF::loadview('pdf', $variabls);
        $filename = $userData->name . '_AttendanceLog.pdf';
        $pdf->setPaper('a4', 'portrait');
        $filePath = public_path('uploads/logs');
        if (!File::exists($filePath)) {

            File::makeDirectory($filePath, $mode = 0755, true, true);
        }
        $output = $pdf->output();
        $pdfDownload = file_put_contents($filePath . '/' . $filename, $output);

        if ($pdfDownload) {
            $url = asset('public/uploads/logs/' . $filename);
            $result = array(
                "status" => 1,
                "url" => $url,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function getTeam(Request $request)
    {
        $api = Api::saveapilogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'offset' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }

        $attributes = $request->all();
        $offset = !empty($attributes['offset']) ? $attributes['offset'] : '0';
        $page_limit = config('constants.api_page_limit');
        $attribute['authorised_person_id'] = $attributes['user_id'];
        $attribute['admin_id'] = $attributes['admin_id'];
        $users = User::getUserData($attribute);

        if (!empty($users)) {
            $result = array(
                'status' => 1,
                'data' => $users,
                'offset' =>  $offset + $page_limit,
                'msg'     => 'Team list',
            );
        } else {
            $result = array(
                'status' => 0,
                'msg'     => 'NO record found',
            );
        }

        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function userNotificationList(Request $request)
    {
        $api = Api::saveapilogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'offset' => ['nullable'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }

        $attributes = $request->all();
        $imgPath = public_path(config('constants.notification_image_path'));
        $offset = !empty($attributes['offset']) ? $attributes['offset'] : '0';
        $page_limit = config('constants.api_page_limit');
        $userNotification = UserNotification::where('admin_id', $attributes['admin_id'])->where('user_id', $attributes['user_id'])->orderBy('created_at', 'DESC')->get();

        foreach ($userNotification as  $key => $notice) {
            if (!empty($notice['image']) && file_exists($imgPath . $notice['image'])) {
                $userNotification[$key]['image'] = url(config('constants.notification_image_path') . $notice['image']);
            }
        }

        if (!empty($userNotification)) {
            $result = array(
                'status' => 1,
                'data' => $userNotification,
                'offset' =>  $offset + $page_limit,
                'msg'     => 'Success',
            );
        } else {
            $result = array(
                'status' => 0,
                'msg'     => 'No record found',
            );
        }

        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function viewNotification(Request $request)
    {
        $api = Api::saveapilogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'id'    => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }

        $attributes = $request->all();
        $viewNotification = UserNotification::where('id', $attributes['id'])->update(['status' => 1]);

        if ($viewNotification) {
            $result = array(
                'status' => 1,
                'data' => $viewNotification,
                'msg'     => 'Success',
            );
        } else {
            $result = array(
                'status' => 0,
                'msg'     => 'Something went wrong',
            );
        }

        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function teamUserProfile(Request $request)
    {
        $api = Api::saveapilogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'authorised_person_id' => ['required'],
            'id' => ['required'],

        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }

        $attributes = $request->all();
        $attributes['user_id'] = $attributes['id'];
        $attributes['authorised_person_id'] = $attributes['authorised_person_id'];
        $attributes['admin_id'] = $attributes['admin_id'];
        $users = User::getUserData($attributes);


        if (!empty($users)) {
            $result = array(
                'status' => 1,
                'data' => $users,
                'msg'     => 'Team list',
            );
        } else {
            $result = array(
                'status' => 0,
                'msg'     => 'NO record found',
            );
        }

        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function teamUserLocation(Request $request)
    {
        $api = Api::saveapilogs($request->all());
        $validator = Validator::make($request->all(), [

            'admin_id' => ['required'],
            'id' => ['required'],
            'date' => ['required'],

        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }

        $thismodel = UserLocationTrack::leftJoin('users', function ($join) {
            $join->on('user_location_tracks.user_id', '=', 'users.id');
        })->select('user_location_tracks.*', 'users.name');

        if (!empty($request['date'])) {
            $start_date_format = mysqlDateFormat($request['date']);
            $thismodel->whereDate('user_location_tracks.datetime', '=', $start_date_format);
        } else {
            $date = Config::get('current_date');
            $filter['date'] = $date;
        }

        $thismodel->where('user_location_tracks.status', 1);
        $thismodel->where('user_location_tracks.user_id', $request->id);
        $thismodel->where('user_location_tracks.admin_id', $request->admin_id);
        $userlocations = $thismodel->get();
        // pr(getQuery($thismodel));die;

        $allUserLocationArray = [];

        foreach ($userlocations as $key => $value) {

            $allUserLocationArray[$key]['colour']    =    "white";
            $allUserLocationArray[$key]['name']      = $value->name;
            $allUserLocationArray[$key]['time']      =    $value->datetime;
            $allUserLocationArray[$key]['address']   =    $value->location;
            $allUserLocationArray[$key]['latitude']  =    $value->latitude;
            $allUserLocationArray[$key]['longitude'] =   $value->longitude;
        }

        if (!empty($allUserLocationArray)) {
            $result = array(
                'status' => 1,
                'data' => $allUserLocationArray,
                'msg'     => 'User Location',
            );
        } else {
            $result = array(
                'status' => 0,
                'msg'     => 'NO record found',
            );
        }

        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    public function allUserLocation(Request $request)
    {
        $api = Api::saveapilogs($request->all());
        $validator = Validator::make($request->all(), [

            'admin_id' => ['required'],
            'authorised_person_id' => ['required'],
            'date' => ['nullable'],

        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }

        $thismodel = UserLocationTrack::leftJoin('users', function ($join) {
            $join->on('user_location_tracks.user_id', '=', 'users.id');
        })->leftJoin('employees', function ($join) {
            $join->on('user_location_tracks.user_id', '=', 'employees.user_id');
        })->select('user_location_tracks.*', 'users.name');

        if (!empty($request['date'])) {
            $start_date_format = mysqlDateFormat($request['date']);
            $thismodel->whereDate('user_location_tracks.datetime', '=', $start_date_format);
        } else {
            $date = Config::get('current_date');
            $filter['date'] = $date;
        }

        $thismodel->where('user_location_tracks.status', 1);
        $thismodel->where('employees.authorised_person_id', $request->authorised_person_id);
        $thismodel->where('user_location_tracks.admin_id', $request->admin_id);

        $userlocations = $thismodel->orderBy('id', 'desc')->groupBy('users.id')->get();
        // pr(getQuery($thismodel));die;

        $allUserLocationArray = [];

        foreach ($userlocations as $key => $value) {

            $allUserLocationArray[$key]['colour']    =    "white";
            $allUserLocationArray[$key]['name']      = $value->name;
            $allUserLocationArray[$key]['time']      =    $value->datetime;
            $allUserLocationArray[$key]['address']   =    $value->location;
            $allUserLocationArray[$key]['latitude']  =    $value->latitude;
            $allUserLocationArray[$key]['longitude'] =   $value->longitude;
        }

        if (!empty($allUserLocationArray)) {
            $result = array(
                'status' => 1,
                'data' => $allUserLocationArray,
                'msg'     => 'User Location',
            );
        } else {
            $result = array(
                'status' => 0,
                'msg'     => 'NO record found',
            );
        }

        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }

    // public function userAttendanceListExcel(Request $request)
    // {
    //     $api = Api::saveapiLogs($request->all());
    //     $validator = Validator::make($request->all(), [
    //         'admin_id' => ['required'],
    //         'user_id' => ['required'],
    //         'month' => ['required'],
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             "status" => 0,
    //             "errors" => $validator->errors(),
    //             "message" => 'Something went wrong',
    //             "msg" => implode('\n', $validator->messages()->all()),
    //         ]);
    //     }
    //     $attributes = $request->all();
    //     $attendance = Attendance::leftJoin('shifts', function ($join) {
    //         $join->on('attendances.shift_id', '=', 'shifts.id');
    //     });
    //     $attendance->select(['attendances.*','shifts.shift_name']);
    //     $attendance->where('attendances.admin_id', $attributes['admin_id']);
    //     $attendance->where('attendances.user_id', $attributes['user_id']);
    //     if (!empty($attributes['month'])) {
    //         $year  = date('Y', strtotime($attributes['month']));
    //         $month  = date('m', strtotime($attributes['month']));
    //         $attendance->whereYear('attendances.attendance_date', $year);
    //         $attendance->whereMonth('attendances.attendance_date', $month);
    //     }
    //     $attendance->orderBy('attendances.attendance_date', 'ASC');
    //     $attendances = $attendance->get();
    //     foreach($attendances as $key => $val){
    //         $records[$key][] =  $val->attendance_date;
    //         $records[$key][] =  $val->from_time;
    //         $records[$key][] =  $val->to_time;
    //         $records[$key][] =  $val->shift_name;
    //         $records[$key][] =  $val->working_hours;
    //         $records[$key][] =  $val->attendance_status;
    //     }
    //     $userData = User::getUserDetails($attributes['user_id'],'emp');
    //     $headings = ["Date", "Time In", "Time Out", "Shift", "Working Hours", "Day Status"];
    //     $adminData = getAdminSettingData($attributes['admin_id']);
    //     $headerLine = [
    //         'Company Name'  =>  $adminData['company_name'],
    //         'Excel'         =>  'Users List',
    //         'Employee Name' =>  $userData->name,
    //         'Employee Code' =>  $userData->employee_code,
    //     ];
    //     $filename = str_replace(' ','',$userData->name) . '_Attendance.csv';
    //     // $filename = time() . '_Attendance.xlsx';
    //     $filePath = 'excel';
    //     if (!File::exists($filePath)) {
    //         File::makeDirectory($filePath, $mode = 0755, true, true);
    //     }
    //     // dd($headings);
    //     $excelDownload =  Excel::store(new ExportUser($records, $headings,$headerLine), $filePath . '/' .  $filename);

    //     if ($excelDownload) {
    //         $url = asset(Storage::url($filePath. '/' .  $filename));
    //         $result = array(
    //             "status" => 1,
    //             "url" => $url,
    //             "msg" => 'Success',
    //         );
    //     } else {
    //         $result = array(
    //             "status" => 0,
    //             "msg" => 'No Record Found',
    //         );
    //     }


    //     Api::updateapiLogs($api, $result);
    //     return response()->json($result);

    // }

    public function userAttendanceListPdf(Request $request)
    {
        $api = Api::saveapiLogs($request->all());
        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'month' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }
        $attributes = $request->all();
        $attendance = Attendance::leftJoin('shifts', function ($join) {
            $join->on('attendances.shift_id', '=', 'shifts.id');
        });
        $attendance->select(['attendances.*', 'shifts.shift_name']);
        $attendance->where('attendances.admin_id', $attributes['admin_id']);
        $attendance->where('attendances.user_id', $attributes['user_id']);
        if (!empty($attributes['month'])) {
            $year  = date('Y', strtotime($attributes['month']));
            $month  = date('m', strtotime($attributes['month']));
            $attendance->whereYear('attendances.attendance_date', $year);
            $attendance->whereMonth('attendances.attendance_date', $month);
        }
        $attendance->orderBy('attendances.attendance_date', 'ASC');
        $attendances = $attendance->get();

        foreach ($attendances as $key => $val) {
            $records[$key][] =  $val->attendance_date;
            $records[$key][] =  $val->from_time;
            $records[$key][] =  $val->to_time;
            $records[$key][] =  $val->shift_name;
            $records[$key][] =  $val->working_hours;
            $records[$key][] =  $val->attendance_status;
        }
        $userData = User::getUserDetails($attributes['user_id'], 'emp');
        $headings = ["Date", "Time In", "Time Out", "Shift", "Working Hours", "Day Status"];
        $adminData = getAdminSettingData($attributes['admin_id']);
        $header = [
            'Company Name'  =>  $adminData['company_name'],
            'File'          =>  'Users List',
            'Employee Name' =>  $userData->name,
            'Employee Code' =>  $userData->employee_code,
        ];
        $tabel_keys = [];
        if (count($records) > 0) {
            $tabel_keys = array_keys($records[0]);
        }

        $variabls = [
            'top_heading' => $userData->name . ' AttendanceList',
            'headings' => $headings,
            'tabel_keys' => $tabel_keys,
            'records' => $records,
            'header' => $header,
        ];

        $pdf =  PDF::loadview('inoutpdf', $variabls);
        $filename = str_replace(' ', '-', $userData->name) . '_Attendance.pdf';
        $pdf->setPaper('a4', 'portrait');
        $filePath = public_path('uploads/pdf');
        if (!File::exists($filePath)) {

            File::makeDirectory($filePath, $mode = 0755, true, true);
        }
        $output = $pdf->output();
        $pdfDownload = file_put_contents($filePath . '/' . $filename, $output);

        if ($pdfDownload) {
            $url = asset('public/uploads/pdf/' . $filename);
            $result = array(
                "status" => 1,
                "url" => $url,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }


    public function updateProfileImage(Request $request)
    {
        $api = Api::saveapiLogs($request->all());

        $validator = Validator::make($request->all(), [
            'admin_id' => ['required'],
            'user_id' => ['required'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Validation failed',
            ], 400);
        }

        $attributes = $request->all();
        $profileUpdate = User::where('admin_id', $attributes['admin_id'])
            ->where('id', $attributes['user_id'])
            ->first();


        if (!empty($attributes['profile_image'])) {
            $img        =   'profile_image';
            $imgPath    =   public_path(config('constants.user_image_path'));
            $img_path   =   $imgPath . $profileUpdate->profile_image;
            if (!empty($profileUpdate->profile_image) && file_exists($img_path)) {
                @unlink($img_path);
            };
            $filename   =   UploadImage($request, $imgPath, $img);
            $attributes['profile_image'] = $filename;
        }

        $profileUpdate->update($attributes);
        $userProfile = $profileUpdate->profile_image;
        if ($userProfile) {
            $url = asset('public/uploads/users/' .$userProfile);
            $result = [
                "status" => 1,
                "user_image_url" => $url,
                "msg" => 'profile image upload successfully',
            ];
        } else {
            $result = [
                "status" => 0,
                "msg" => 'Something went wrong',
            ];
        }

        Api::updateapiLogs($api, $result);
        return response()->json($result);
    }



    public function razorpayXPayout(Request $request)
    {
        // dd($request->getContent());
        $api = Api::saveapiLogs($request->all());
        $resData = json_decode($api->request, true);

        $t = $resData['payload']['payout']['entity'];
        $sal =  Salary::where('gateway_payment_id', $t['id'])->first();
        if ($t['status'] == 'processed') {
            $sal->update(['payment_status' => "paid"]);
        }
        // else {
        //     $sal->update(['payment_status' => "fail"]);
        // }
    }
}
