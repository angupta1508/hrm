<?php

// use IPLocation;
use Carbon\Carbon;
use App\Models\City;
use App\Models\Page;
use App\Models\Role;
use App\Models\User;
use App\Models\Shift;
use App\Models\State;
use App\Models\Notice;
use App\Models\Company;
use App\Models\Country;
use App\Models\Holiday;
use App\Models\Package;
use App\Models\Setting;
use App\Mail\NotifyMail;
use App\Models\Employee;
use App\Models\Language;
use App\Models\Location;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\UserPolicy;
use App\Models\Designation;
use App\Models\AdminModules;
use App\Models\LanguagePage;
use App\Models\SequenceCode;
use App\Models\PackageModule;
use App\Models\AttendanceReason;
use App\Models\ManualAttendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\AdminModulePermission;
use Illuminate\Support\Facades\Route;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;
use App\Models\PackageModulePermission;
use Stevebauman\Location\Facades\Location as IPLocation;

/**
 * Write code on Method
 *
 * @return response()
 */
if (!function_exists('convertYmdToMdy')) {

    function convertYmdToMdy($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('m-d-Y');
    }
}


if (!function_exists('pr')) {
    function pr($arr)
    {

        echo '<pre>';

        print_r($arr);

        echo '</pre>';
    }
}

/**
 * Write code on Method
 *
 * @return response()
 */
if (!function_exists('convertMdyToYmd')) {

    function convertMdyToYmd($date)
    {
        return Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
    }
}

/**
 * Write code on Method
 *
 * @return response()
 */
function getAllSettings($admin_id = '')
{
    if (!empty($admin_id)) {
        $thismodal = Setting::where('admin_id', $admin_id);
    } else {
        $thismodal = Setting::where('admin_id', 1);
    }

    $thismodal->where('status', 1);
    $data = $thismodal->get();

    return $data;
}

function getSettings($setting_type = '')
{
    if (Auth::guard('front-admin')->check()) {
        $user = Auth::guard('front-admin')->user();
    } else {
        $user = Auth::user();
    }

    $data = '';
    if (!empty($user)) {
        $thismodal = Setting::orderBy('setting_label');
        if ($user->role_id >= config('constant.admin_role_id')) {
            $thismodal->where([['setting_type', $setting_type], ['admin_id', $user->id]]);
        }
        if ($user->role_id == config('constant.superadmin_role_id')) {
            $thismodal->where('admin_id', '1');
        }
        $thismodal->where('status', 1);
        $data = $thismodal->get();
    }
    return $data;
}

function getSettingData($setting_name = '', $admin_id = '', $type = '')
{
    $data = Setting::where('setting_name', $setting_name);
    if (!empty($admin_id)) {
        $data->where('admin_id', $admin_id);
    }

    $settingData = $data->first();
    // dd($settingData);
    if ($type == 'val') {
        return !empty($settingData->setting_value) ? $settingData->setting_value : '';
    } else {
        return $settingData;
    }
}

function settingSaver($admin_id)
{
    $thismodal = Setting::where([['admin_id', config('constants.superadmin_role_id')], ['default_setting', 1]])->get();
    foreach ($thismodal as $key => $value) {
        $saveDate = [];
        $saveDate['admin_id'] = $admin_id;
        $saveDate['setting_name'] = $value->setting_name;
        $saveDate['setting_label'] = $value->setting_label;
        $saveDate['setting_value'] = '';
        $saveDate['input_type'] = $value->input_type;
        $saveDate['setting_type'] = $value->setting_type;
        $saveDate['default_setting'] = 0;
        $saveDate['status'] = 1;
        Setting::create($saveDate);
    }
    return true;
}

function findArrayOfColumn($array, $keySearch, $valueSearch)
{
    $out = null;
    foreach ($array as $key => $item) {
        // dd($valueSearch);
        // echo $item[$keySearch].'<br>';
        // echo $key.'<br>'; 
        // echo $keySearch.'<br>'; 
        // echo $valueSearch.'<br>';
        if (!empty($item[$keySearch]) && $valueSearch == $item[$keySearch]) {
            $out = $key;
        }
    }
    return $out;
}

function getJsonData($fileName)
{
    $jsonString = file_get_contents(public_path($fileName));
    $json = json_decode($jsonString, false);
    return $json;
}

function joined($query, $table)
{
    $joins = $query->getQuery()->joins;
    if ($joins == null) {
        return false;
    }
    foreach ($joins as $join) {
        if ($join->table == $table) {
            return true;
        }
    }
    return false;
}

function send_sms($c_number, $c_message)
{
    $c_number = str_replace('+91', '', $c_number);
    $fields = [
        "route" => Config::get('route'),
        "sender_id" => Config::get('sender_id'),
        "message" => $c_message,
        "language" => "english",
        "flash" => 0,
        "numbers" => $c_number,
    ];

    $headers = array(
        'Authorization: ' . Config::get('auth_key'),
        'Content-Type: application/json',
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, Config::get('sms_url'));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === false) {
        die('Curl failed: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
}

function pre_zero($num, $dig)
{
    $num_padded = sprintf("%0" . $dig . "d", $num);
    return $num_padded;
}

////////////Get sequence No to Update sequence table////////////
function new_sequence_code($code)
{
    $rescode = SequenceCode::where('sequence_code', $code)->first();
    if (empty($rescode->id)) {
        $attributes['sequence_code'] = $code;
        $attributes['sequence_number'] = '0000';
        $rescode = SequenceCode::create($attributes);
    }
    //pr(getQuery($rescode));die;
    // pr($rescode);die;
    $sequence_code = $rescode->sequence_number;
    $code_uni = $sequence_code + 1;
    $uni_idd =  $code . pre_zero($code_uni, 4);
    $seq_array = array('sequence_number' => pre_zero($code_uni, 4));
    SequenceCode::where('sequence_code', $code)->update($seq_array);
    return $uni_idd;
}

function getQuery($query)
{
    return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
        $binding = addslashes($binding);
        return is_numeric($binding) ? $binding : "'{$binding}'";
    })->toArray());
}
// es se coll karana hai

// pr(getQueryWithBindings($thismodel));die;
function getRolelist($role_type = 'User')
{
    $thismodel = Role::orderBy('name');
    if (!empty($role_type)) {
        $thismodel->where("role_type", $role_type);
    }
    $thismodel->where('status', '=', 1);
    return $thismodel->pluck('name', 'id')->all();
}

function getCountrylist()
{
    $thismodel = Country::orderBy('name');
    return $thismodel->pluck('name', 'id')->all();
}

function getStatelist($country_id = '')
{
    $thismodel = State::orderBy('name');
    if (!empty($country_id)) {
        $thismodel->where("country_id", $country_id);
    }
    $thismodel->where('status', '=', 1);
    return $thismodel->pluck('name', 'id')->all();
}

function getCitylist($state_id = '')
{
    $thismodel = City::orderBy('name');
    if (!empty($state_id)) {
        $thismodel->where("state_id", $state_id);
    }
    $thismodel->where('status', '=', 1);
    return $thismodel->pluck('name', 'id')->all();
}

function getCompanyList()
{
    $companies = Company::where('admin_id', Config::get('auth_detail')['admin_id'])->where('status', 1)->get()->pluck("name", "id")->toArray();
    return $companies;
}
function getReasonList()
{
    $loggedUser = Auth::user();
    $reasons = AttendanceReason::where('attendance_reasons.status', 1)->where('attendance_reasons.admin_id', $loggedUser->admin_id)
        ->get()
        ->pluck("name", "id")->toArray();
    return $reasons;
}

function getDepartmentList()
{
    $departments = Department::where('admin_id', Config::get('auth_detail')['admin_id'])->where('status', 1)->get()->pluck("department_name", "id")->toArray();
    return $departments;
}

function getLocationList()
{
    $locations = Location::where('admin_id', Config::get('auth_detail')['admin_id'])->where('status', 1)->get()->pluck("location_name", "id")->toArray();
    return $locations;
}

function getDesignationList()
{
    $designation = Designation::where('admin_id', Config::get('auth_detail')['admin_id'])->where('status', 1)->get()->pluck("name", "id")->toArray();
    return $designation;
}

function getShiftList()
{
    $shifts = Shift::where('admin_id', Config::get('auth_detail')['admin_id'])->where('status', 1)->get()->pluck("shift_name", "id")->toArray();
    return $shifts;
}

function getMangerList()
{

    $manager = User::leftJoin('employees', function ($join) {
        $join->on('users.id', '=', 'employees.user_id');
    })->select(['users.id', 'users.name', 'users.username', 'users.email', 'users.mobile', 'users.user_uni_id',]);
    $managers   =   $manager->where('employees.is_manager', 1)->where('users.admin_id', Config::get('auth_detail')['admin_id'])->where('users.status', 1)->get()->pluck("full_info", "id")->toArray();
    return $managers;
}


function getUserList($data = [])
{
    $loggedUser = Auth::user();
    $thismodel = User::orderBy('users.created_at', 'ASC');

    $thismodel->leftJoin('employees', function ($join) {
        $join->on('users.id', '=', 'employees.user_id');
    });

    if (!empty($data['status'])) {
        $thismodel->where('users.status', $data['status']);
    }

    $thismodel->where('users.role_id', config('constants.employee_role_id'));
    $thismodel->where('users.admin_id', $loggedUser->admin_id);
    $thismodel->where('users.trash', 0);
    $thismodel->select(['users.*', 'employees.employee_code', 'employees.machine_code', 'employees.company_id']);
    // dd(getQuery($thismodel));
    return $thismodel->get()->sortBy('name')->pluck("name", "id")->toArray();
}

function getNotices($data = [], $is_first = 0)
{
    $imgPath = public_path(config('constants.notice_image_path'));
    $thismodel = Notice::where('admin_id', $data['admin_id']);
    $thismodel->where('status', 1);
    if (!empty($data['type'])) {
        $thismodel->where('type', $data['type']);
        $thismodel->orderBy('id', 'desc');
        if ($is_first == 2) {
            $record =   $thismodel->get()->count();
        } else if ($is_first == 1) {
            $record =  $thismodel->first();
            if (!empty($record['attachment']) && file_exists($imgPath . $record['attachment'])) {
                $record['attachment'] = url(config('constants.notice_image_path') . $record['attachment']);
            }
        } else {
            $record = $thismodel->get();
            foreach ($record as  $key => $notice) {
                if (!empty($notice['attachment']) && file_exists($imgPath . $notice['attachment'])) {
                    $record[$key]['attachment'] = url(config('constants.notice_image_path') . $notice['attachment']);
                }
            }
        }
        foreach ($record as $rec) {
            $rec->description = strip_tags($rec->description);
        }
        return $record;
    }
}

function UploadImage($request, $imgPath, $imgKey, $lastFile = '', $isSetting = 0)
{
    $smallimgpath = $imgPath . 'small/';
    $iconimgpath = $imgPath . 'icon/';
    if (!file_exists($imgPath)) {
        @mkdir($imgPath, 0777, true);
    }

    if (!file_exists($smallimgpath)) {
        @mkdir($smallimgpath, 0777, true);
    }

    if (!file_exists($iconimgpath)) {
        @mkdir($iconimgpath, 0777, true);
    }

    $lastFilePath = $imgPath . $lastFile;
    $smallFilePath = $smallimgpath . $lastFile;
    $iconFilePath = $iconimgpath . $lastFile;
    if (!empty($lastFile) && file_exists($lastFilePath)) {
        @unlink($lastFilePath);
    }

    if (!empty($lastFile) && file_exists($smallFilePath)) {
        @unlink($smallFilePath);
    }

    if (!empty($lastFile) && file_exists($iconFilePath)) {
        @unlink($iconFilePath);
    }

    //get file extension
    if ($isSetting == 1) {
        $file = $request;
    } else {
        $file = $request->file($imgKey);
    }
    $extension = $file->getClientOriginalExtension();
    $filename = time() . '.' . $extension;
    //small thumbnail name
    $img = Image::make($file->getRealPath());
    $thumb_small = $img->resize(config('constants.small_image_width'), config('constants.small_image_height'), function ($constraint) {
        $constraint->aspectRatio();
    });
    $thumb_small->save($smallimgpath . $filename);

    $img = Image::make($file->getRealPath());
    $thumb_icon = $img->resize(config('constants.icon_image_width'), config('constants.icon_image_height'), function ($constraint) {
        $constraint->aspectRatio();
    });
    $thumb_icon->save($iconimgpath . $filename);

    if ($file->move($imgPath, $filename)) {
        return $filename;
    }

    return false;
}


function documentUpload($request, $imgPath, $imgKey, $lastFile = '', $isSetting = 0)
{

    if (!file_exists($imgPath)) {
        @mkdir($imgPath, 0777, true);
    }

    $lastFilePath = $imgPath . $lastFile;
    if (!empty($lastFile) && file_exists($lastFilePath)) {
        @unlink($lastFilePath);
    }

    //get file extension
    if ($isSetting == 1) {
        $file = $request;
    } else {
        $file = $request->file($imgKey);
    }
    $extension = $file->getClientOriginalExtension();
    $filename = time() . '.' . $extension;

    if ($file->move($imgPath, $filename)) {
        return $filename;
    }

    return false;
}


function ImageShow($path, $img, $type = '', $default = '')
{
    $originalImgPath = public_path($path) . $img;
    $originalImgUrl = url($path) . '/' . $img;
    if (!empty($type)) {
        $url = url($path . $type . '/');
        $imgPath = public_path($path) . $type . '/';
        $imgUrl = $url . '/' . $img;
    } else {
        $url = url($path);
        $imgPath = public_path($path);
        $imgUrl = $url . '/' . $img;
    }

    if (!empty($default)) {
        $defaultimg = url($default);
    } else {
        $defaultimg = url(config('constants.default_image_path'));
    }
    $publicpath = $imgPath . $img;
    if (!empty($img) && file_exists($publicpath)) {
        $imagUrl = $imgUrl;
    } elseif (!empty($img) && file_exists($originalImgPath)) {
        $imagUrl = $originalImgUrl;
    } else {
        $imagUrl = $defaultimg;
    }
    return $imagUrl;
}

function pages($slug)
{
    return Page::where('page_slug', $slug)->where('status', 1)->first();
    // return $data->page_description;
}

function pushNotification($data)
{
    // $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();

    $SERVER_API_KEY = Config::get('firbase_api_token');
    // dd($SERVER_API_KEY);

    $data = [
        "registration_ids" => $data['chunk'],
        "data" => [
            "title" => !empty($data['title']) ? $data['title'] : '',
            "body" => !empty($data['description']) ? $data['description'] : '',
            'largeIcon' => Url::to("/assets/img/logo3.png"),
            'sound' => 'mySound',
            'type' => !empty($data['type']) ? $data['type'] : '',
            'channelName' => !empty($data['channelName']) ? $data['channelName'] : '',
        ],
        'priority' => 'high',
    ];
    $dataString = json_encode($data);

    $headers = [
        'Authorization: key=' . $SERVER_API_KEY,
        'Content-Type: application/json',
    ];

    // pr($headers);
    // pr($data);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    $response = curl_exec($ch);
    // pr($response);
    return $response;
}

function sendMail($template, $user)
{
    $result = false;
    if (!empty($template)) {
        $emailFindReplace = array(
            '##WEBSITE_URL##' => URL::to('/'),
            '##SITE_NAME##' => Config::get('company_name'),
            '##USER_EMAIL##' => $user->email,
            '##FIRST_NAME##' => $user->name,
            '##WEBSITE_LOGO##' => Config::get('logo'),
            '##SUPPORT_EMAIL##' => Config::get('email'),
            '##CONTACT_EMAIL##' => Config::get('mobile_no'),
            '##PDF_URL##' => !empty($user->pdf_link) ? $user->pdf_link : '',
        );

        $subject = strtr($template->title, $emailFindReplace);
        $body = strtr($template->content, $emailFindReplace);

        $mailData = [
            'action' => $template->title,
            'subject' => $subject,
            'body' => $body,
        ];
        $mailData['template_code'] = $template->template_code;
        $mailData['name'] = $user->name;
        $mailData['email'] = $user->email;

        Config::set('mail.mailers.smtp.host', Config::get('smtp_host'));
        Config::set('mail.mailers.smtp.username', Config::get('smtp_username'));
        Config::set('mail.mailers.smtp.password', Config::get('smtp_password'));
        Config::set('mail.mailers.smtp.port', Config::get('smtp_port'));
        Config::set('mail.from.address', Config::get('smtp_username'));
        Config::set('mail.from.name', Config::get('company_name'));
        // dd(Config::get('mail'));
        $mail = Mail::to($mailData['email'])->send(new NotifyMail($mailData));
        if ($mail) {
            $result = true;
        } else {
            $result = false;
        }
    }
    return $result;
}

// Change date format
function prettyDateFormet($date, $key = '')
{
    $result = '';
    if (!empty($date)) {
        $time = strtotime($date);
        $result = '';
        if ($key == 'date') {
            $result = date("d M,", $time);
        } elseif ($key == 'time') {
            $result = date("h:i:s A", $time);
        } else {
            $result = date("D, d M, Y h:i:s A", $time);
        }
        // dd($result);
    }
    return $result;
}


// function prettyDate($date, $key = '')
// {
//     // dd($date);
//     $result = '';
//     if (!empty($date)) {
//         $time = strtotime($date);
//         $result = '';
//         if ($key == 'date') {
//             $result = date("D, d M");
//         } elseif ($key == 'time') {
//             $result = date("H:i:s A", $time);
//         } else {
//             $result = date("D, d M, Y H:i:s A", $time);
//         }
//         // dd($result);
//     }
//     return $result;
// }

function mysqlDateFormat($date)
{
    if (!empty($date)) {
        $time = str_replace(',', '', $date);
        $time = str_replace(' ', '-', $time);
        $time = strtotime($time);
    }
    return date("Y-m-d", $time);
}

function niceDateFormet($date)
{
    $result = '';
    if (!empty($date)) {
        $time = strtotime(date("Y-m-d H:i:s"));
        $current_time = strtotime($date);

        if ($time > $current_time) {
            $getsecound = $time - $current_time;
            if ($getsecound > 3600 * 24 * 30 * 12) {
                $duration = floor($getsecound / (3600 * 24 * 30 * 12));
                if ($duration > 1) {
                    $result = $duration . " Years ago";
                } else {
                    $result = $duration . " Year ago";
                }
            } elseif ($getsecound > 3600 * 24 * 30) {
                $duration = floor($getsecound / (3600 * 24 * 30));
                if ($duration > 1) {
                    $result = $duration . " months ago";
                } else {
                    $result = $duration . " month ago";
                }
            } elseif ($getsecound > 3600 * 24 * 7) {
                $duration = floor($getsecound / (3600 * 24 * 7));
                if ($duration > 1) {
                    $result = $duration . " weeks ago";
                } else {
                    $result = $duration . " week ago";
                }
            } elseif ($getsecound > 3600 * 24) {
                $duration = floor($getsecound / (3600 * 24));
                if ($duration > 1) {
                    $result = $duration . " days ago";
                } else {
                    $result = $duration . " day ago";
                }
            } elseif ($getsecound > 3600) {
                $duration = floor($getsecound / (3600));
                if ($duration > 1) {
                    $result = $duration . " hours ago";
                } else {
                    $result = $duration . " hour ago";
                }
            } elseif ($getsecound > 60) {
                $duration = floor($getsecound / 60);
                if ($duration > 1) {
                    $result = $duration . " minutes ago";
                } else {
                    $result = $duration . " minute ago";
                }
            } elseif ($getsecound > 0) {
                $result = "now";
            }
        }
    }

    return $result;
}

function getLanguages($filter = [])
{
    $thismodel = Language::orderBy('language_name', 'ASC');
    if (isset($filter['status']) && $filter['status'] != '') {
        $thismodel->where('status', $filter['status']);
    }

    if (isset($filter['system_language_status']) && $filter['system_language_status'] != '') {
        $thismodel->where('system_language_status', $filter['system_language_status']);
    }

    if (isset($filter['tongue_language_status']) && $filter['tongue_language_status'] != '') {
        $thismodel->where('tongue_language_status', $filter['tongue_language_status']);
    }

    return $thismodel->get();
}

function getLanguageIdByCode($language_code)
{
    $language_id = 0;
    $langPage = Language::where('language_code', $language_code)->first();
    if (!empty($langPage->id)) {
        $language_id = $langPage->id;
    }

    return $language_id;
}

function getLanguagePage($data = [])
{
    $thismodel = LanguagePage::leftJoin('languages', function ($join) {
        $join->on('language_pages.language_id', '=', 'languages.id');
    });

    if (!empty($data['language_id'])) {
        $thismodel->where('language_pages.language_id', $data['language_id']);
    }
    if (!empty($data['page_id'])) {
        $thismodel->where('language_pages.page_id', $data['page_id']);
    }
    if (!empty($data['language_code'])) {
        $thismodel->where('languages.language_code', $data['language_code']);
    }
    return $thismodel->first();
}

function getPage($data = [])
{
    $thismodel = LanguagePage::leftJoin('languages', function ($join) {
        $join->on('language_pages.language_id', '=', 'languages.id');
    });
    $thismodel->select(['language_pages.*', 'pages.*']);
    $thismodel->leftJoin('pages', function ($join) {
        $join->on('language_pages.page_id', '=', 'pages.id');
    });

    if (!empty($data['page_slug'])) {
        $thismodel->where('pages.page_slug', $data['page_slug']);
    }
    if (!empty($data['language_id'])) {
        $thismodel->where('language_pages.language_id', $data['language_id']);
    }
    if (!empty($data['page_id'])) {
        $thismodel->where('language_pages.page_id', $data['page_id']);
    }
    if (!empty($data['language_code'])) {
        $thismodel->where('languages.language_code', $data['language_code']);
    } else {
        $thismodel->where('languages.language_code', config('constants.default_company_lang'));
    }
    // dd(getQueryWithBindings($thismodel));
    return $thismodel->first();
}

function getRoleWiseUserData($data = [])
{
    $loggedUser = Auth::user();
    $thismodel = User::orderBy('users.created_at', 'ASC');

    $thismodel->leftJoin('roles', function ($join) {
        $join->on('users.role_id', '=', 'roles.id');
    });

    if (!empty($data['role_type'])) {
        $thismodel->whereIn('roles.role_type', $data['role_type']);
    }

    if (!empty($data['role_ids'])) {
        $thismodel->whereIn('roles.role_id', $data['role_ids']);
    }

    if (!empty($data['status'])) {
        $thismodel->where('users.status', $data['status']);
    }
    $thismodel->where('users.admin_id', $loggedUser->admin_id);

    $thismodel->where('roles.status', 1);
    $thismodel->where('users.trash', 0);
    $thismodel->select(['users.*', 'roles.name as role_name']);
    // dd(getQuery($thismodel));
    return $thismodel->get()->sortBy('full_info')->pluck("full_info", "id")->toArray();
}

function wordlimit($worddata, $limit = 25)
{
    $wordchange = strip_tags($worddata);
    if (!empty($wordchange) && strlen($wordchange) > $limit) {
        $wordchange = substr($wordchange, 0, $limit) . '...';
    }

    return $wordchange;
}

function getCurlReponse($userId, $apiKey, $resource, array $data, $language)
{
    $apiEndPoint = "http://json.astrologyapi.com/v1";

    $serviceUrl = $apiEndPoint . '/' . $resource . '/';
    $authData = $userId . ":" . $apiKey;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $serviceUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

    $header[] = 'Authorization: Basic ' . base64_encode($authData);
    /* Setting the Language of Response */
    if ($language != null) {
        array_push($header, 'Accept-Language: ' . $language);
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    $response = curl_exec($ch);

    curl_close($ch);

    return $response;
}

function todaycustomerBirthday()
{
    $today = now();
    $today_customer = User::leftJoin('customers', function ($join) {
        $join->on('customers.customer_uni_id', '=', 'users.user_uni_id');
    })->select([
        'users.name AS name', 'users.mobile AS phone', 'customers.customer_uni_id AS uni_id', 'customers.birth_date AS date', 'customers.customer_img AS images',
    ])
        ->whereMonth('birth_date', $today->month)
        // ->orderBy(DB::raw("DATE_FORMAT(birth_date,'%M-%D')"), 'ASC')
        ->whereDay('birth_date', $today->day)
        ->get()->toArray();

    foreach ($today_customer as $key => $value) {
        //dd($value);
        $imgPath = public_path(config('constants.customer_image_path'));
        if (!empty($value['images']) && file_exists($imgPath . $value['images'])) {
            $today_customer[$key]['images'] = url(config('constants.customer_image_path') . $value['images']);
        } else {
            $today_customer[$key]['images'] = asset(config('constants.default_customer_image_path'));
        }
    }
    return $today_customer;
}

function birthSort($a, $b)
{
    if ($a == $b) {
        return 0;
    }

    return (date('m-d', strtotime($a['date'])) < date('m-d', strtotime($b['date']))) ? -1 : 1;
}

function upcomingCustomer()
{
    $tomorrow = Carbon::tomorrow();
    $upcoming_cust_birth = User::leftJoin('customers', function ($join) {
        $join->on('customers.customer_uni_id', '=', 'users.user_uni_id');
    })->select([
        'users.name AS name', 'users.role_id AS role_id', 'users.mobile AS phone', 'customers.customer_uni_id AS uni_id', 'customers.birth_date AS date', 'customers.customer_img AS images',
    ])->where(DB::raw("DATE_FORMAT(birth_date,'%m-%d')"), '>=', Carbon::now()->addDays(1)->format('m-d'))
        ->where(DB::raw("DATE_FORMAT(birth_date,'%m-%d')"), '<=', Carbon::now()->addDays(30)->format('m-d'))
        // ->orderBy(DB::raw("DATE_FORMAT(birth_date,'%M-%D')"), 'ASC')
        ->get()->toArray();
    foreach ($upcoming_cust_birth as $key => $value) {
        $imgPath = public_path(config('constants.customer_image_path'));
        if (!empty($value['images']) && file_exists($imgPath . $value['images'])) {
            $upcoming_cust_birth[$key]['images'] = url(config('constants.customer_image_path') . $value['images']);
        } else {
            $upcoming_cust_birth[$key]['images'] = asset(config('constants.default_customer_image_path'));
        }
    }

    return $upcoming_cust_birth;
}

function secoundToTime($seconds)
{
    $time = '';
    if (!empty($seconds)) {
        $time = gmdate("H:i:s", $seconds);
    }
    // dd($time);
    return $time;
}

function getRoleFromRoute($route)
{
    $uri = str_replace($route->action['prefix'], '', $route->uri);
    $slugArray = explode('/', $uri);
    // dd($slugArray);
    $routeData = [];
    if (!empty($slugArray[0])) {
        $slug = $slugArray[0];
        // dd($slug);
        $getRole = Role::where(['slug' => $slug])->first();
        // dd($getRole);
        if (!empty($getRole->id)) {
            // dd($getRole->id);
            $routeData['routeSlug'] = $slug;
            $routeData['routeId'] = $getRole->id;
        }
    }
    return $routeData;
}

function getRoleTypeFromRoute($route)
{
    $uri = str_replace($route->action['prefix'], '', $route->uri);
    $slugArray = explode('/', $uri);

    $routeData = [];
    if (!empty($slugArray[0])) {
        $slug = $slugArray[0];
        $getRoleType = explode('-', $slug);
        if (!empty($getRoleType[0])) {
            $routeData['routeSlug'] = $slug;
            $routeData['routeId'] = $getRoleType[0];
        }
    }
    return $routeData;
}

function checkPackageModulePermission($module = '', $operation = '')
{
    $loggedUser = Auth::user();
    if (!empty($loggedUser)) {
        if ($loggedUser->role_id == config('constants.superadmin_role_id')) {
            return true;
        }
        if ($loggedUser->role_id == config('constants.admin_role_id')) {
            if ($loggedUser->package_valid_date >= Config::get('current_date')) {
                if (!empty($operation)) {
                    return getRoleModuleAccess($module, $operation);
                } else {
                    return getModuleAccess($module, $loggedUser->package_uni_id);
                }
            } else {
                return false;
            }
        } elseif ($loggedUser->role_id > config('constants.admin_role_id')) {
            $parent = User::where('user_uni_id', $loggedUser->admin_id)->first();
            if (!empty($parent->package_valid_date) >= Config::get('current_date')) {
                if (!empty($operation)) {
                    return getRoleModuleAccess($module, $operation);
                } else {
                    return getModuleAccess($module, $parent->package_uni_id);
                }
            } else {
                return false;
            }
        }
    }
}

function getModuleAccess($module, $package_id)
{
    $loggedUser = Auth::user();
    $package = PackageModulePermission::where([['status', 1], ['trash', 0], ['package_uni_id', $package_id]])->get();
    $data = array();
    foreach ($package as $key => $value) {
        $modules = PackageModule::where('id', $value->module_id)->first();
        $data[] = $modules->module_name;
    }
    // dd($module);
    if (in_array($module, $data)) {
        return true;
    } else {
        return false;
    }
}

function getRoleModuleAccess($module, $operation)
{
    $loggedUser = Auth::user();
    $rolepermission = AdminModulePermission::where([['status', 1], ['module', $module], ['operation', $operation], ['role_id', $loggedUser->role_id]])->first();
    if (!empty($rolepermission) && $rolepermission->status == 1) {
        return true;
    } else {
        return false;
    }
}

function role_module_permission()
{
    $loggedUser = Auth::user();
    if (!empty($loggedUser)) {
        $role_id = $loggedUser->role_id;
        $user_uni_id = $loggedUser->user_uni_id;
    }
    // dd($user_uni_id);

    $moduleData = adminModuleOperaton();
    $login_operation = arrayReplaceValue();
    if (!empty($user_uni_id) && !empty($role_id) && $moduleData != 'success') {
        $permission = AdminModulePermission::where([['role_id', $role_id], ['operation', $login_operation], ['module_id', $moduleData->id]])->first();

        $module_data = AdminModules::get();
        foreach ($module_data as $key => $value) {
            $operation = explode('|', $value->operation);
        }
        // dd($permission);
        if (in_array($role_id, [config('constant.superadmin_role_id'), config('constant.admin_role_id')])) {
            return true;
        } elseif (!empty($permission)) {
            //echo "dsfs"; die;
            $role_perm = $permission->status; // dd($role_perm);
            if ($login_operation == $permission->operation && in_array($login_operation, $operation) && $role_perm) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    } else {
        return true;
    }
}

function adminModuleOperaton()
{
    $route = Route::current()->getName();
    $route = str_replace('-', '.', $route);
    $route = explode('.', $route);
    $admineModules = AdminModules::get(['module_name']);
    foreach ($admineModules as $key => $value) {
        $moduledata[] = $value->module_name;
    };
    $modulevalue = array_values(array_intersect($route, $moduledata));
    if (!empty($modulevalue)) {
        $moduleData = AdminModules::where('module_name', $modulevalue[0])->first();
        if (!empty($moduleData)) {
            return $moduleData;
        }
    } else {
        return 'success';
    }
}

function packageloginOperaton()
{
    $route = Route::current()->getName();
    $route = str_replace('-', '.', $route);
    $route = explode('.', $route);
    $packageModules = PackageModule::get(['module_name']);
    foreach ($packageModules as $key => $value) {
        $moduledata[] = $value->module_name;
    };
    $modulevalue = array_values(array_intersect($route, $moduledata));
    if (!empty($modulevalue)) {
        return $modulevalue;
    } else {
        return 'success';
    }
}

function arrayReplaceValue()
{
    $route = Route::current()->getName();
    $route = str_replace('-', '.', $route);
    $route = explode('.', $route);
    $end = end($route);
    if (!in_array($end, ['dashboard', 'login', 'logout'])) {
        if (in_array($end, ['index'])) {
            $loginOperationValue = 'read';
        } elseif (in_array($end, ['create', 'store'])) {
            $loginOperationValue = 'create';
        } elseif (in_array($end, ['edit', 'update'])) {
            $loginOperationValue = 'edit';
        } elseif (in_array($end, ['destroy'])) {
            $loginOperationValue = 'delete';
        } else {
            $loginOperationValue = '';
        }
        return $loginOperationValue;
    } else {
        return $end;
    }
}

function getRoleTypeData($role_id)
{
    $data = Role::where('id', $role_id)->first();
    return $data;
}

function upcomingCelebrations($request)
{
    $offset =  !empty($request->offset) ? $request->offset : '0';
    $page_limit = config('constants.api_page_limit');
    $today = date('Y-m-d');
    $one_month_later = date('Y-m-d', strtotime('+1 month'));

    $data = DB::table('users')->leftJoin('employees', function ($join) {
        $join->on('users.id', '=', 'employees.user_id');
    })
        ->whereRaw("( ( DATE_FORMAT(users.dob, '%m-%d') > DATE_FORMAT('$today','%m-%d') and DATE_FORMAT(users.dob, '%m-%d') < DATE_FORMAT('$one_month_later','%m-%d') ) or 
        ( DATE_FORMAT(users.anniversary_date, '%m-%d') > DATE_FORMAT('$today','%m-%d') and DATE_FORMAT(users.anniversary_date, '%m-%d') < DATE_FORMAT('$one_month_later','%m-%d') )
         
          or ( DATE_FORMAT(employees.joined_date, '%m-%d') > DATE_FORMAT('$today','%m-%d') and DATE_FORMAT(employees.joined_date, '%m-%d') < DATE_FORMAT('$one_month_later','%m-%d')
          ))")

        ->orderByRaw("DATE_FORMAT(users.dob, '%m-%d'),
         DATE_FORMAT(users.anniversary_date, '%m-%d'),
          DATE_FORMAT(employees.joined_date, '%m-%d')");
    $data->where('users.admin_id', $request->admin_id)->where('users.status', 1);
    // $data->where('users.id', 38);
    // pr($data->toSql());die;
    if (!empty($request->offset)) {
        $offset = $request->offset;
        $data->offset($offset)->limit($page_limit);
    }

    $celebrationns = $data->get();
    // dd($celebrationns);

    foreach ($celebrationns as $key => $value) {

        $imgPath = config('constants.user_image_path');
        $imgDefaultPath = config('constants.default_user_image_path');
        $value->profile_image = ImageShow($imgPath, $value->profile_image, 'icon', $imgDefaultPath);

        $dob = date('m-d', strtotime($value->dob));

        $aniversary = date('m-d', strtotime($value->anniversary_date));

        $joined_date = date('m-d', strtotime($value->joined_date));

        $todayData = date('m-d', strtotime($today));

        $oneMonthDate = date('m-d', strtotime($one_month_later));

        $year = date('Y');

        $date = [];

        if ($dob >= $todayData && $dob <= $oneMonthDate) {
            $date['birthday'] = $dob;
        }
        if ($aniversary >= $todayData && $aniversary <= $oneMonthDate) {
            $date['anniversary'] = $aniversary;
        }
        if ($joined_date >= $todayData && $joined_date <= $oneMonthDate) {
            $date['joined_date'] = $joined_date;
        }

        $minDate = min($date);
        $type = array_search($minDate, $date);

        if ($type == "birthday") {
            $celebrationns[$key]->type = "Birthday";
            if ($value->gender == "Male") {
                $celebrationns[$key]->wish_msg = 'Happy birthday to you ' . $value->name . ' sir';
            } elseif ($value->gender == "Female") {
                $celebrationns[$key]->wish_msg = 'Happy birthday to you ' . $value->name . ' madam';
            } else {
                $celebrationns[$key]->wish_msg = 'Happy birthday to you ' . $value->name . ' sir';
            }
        } elseif ($type == "anniversary") {
            $celebrationns[$key]->type = "Anniversary";
            if ($value->gender == "Male") {
                $celebrationns[$key]->wish_msg = 'Happy anniversary to you ' . $value->name . ' sir';
            } elseif ($value->gender == "Female") {
                $celebrationns[$key]->wish_msg = 'Happy anniversary to you ' . $value->name . ' madam';
            } else {
                $celebrationns[$key]->wish_msg = 'Happy anniversary to you ' . $value->name . ' sir';
            }
        } elseif ($type == "joined_date") {
            $celebrationns[$key]->type = "Joined Date";
            if ($value->gender == "Male") {
                $celebrationns[$key]->wish_msg = 'Happy joining anniversary to you ' . $value->name . ' sir';
            } elseif ($value->gender == "Female") {
                $celebrationns[$key]->wish_msg = 'Happy joining anniversary to you ' . $value->name . ' madam';
            } else {
                $celebrationns[$key]->wish_msg = 'Happy joining anniversary to you ' . $value->name . ' sir';
            }
        }
        $celebrationns[$key]->wish_date = $year . '-' . $minDate;
    }
    return collect($celebrationns)->sortBy('wish_date')->values()->all();
}

function todayCelebrations($request)
{
    // dd($request->admin_id);
    $offset =  !empty($request->offset) ? $request->offset : '0';
    $page_limit = config('constants.api_page_limit');
    $today = Carbon::today();
    $todaycelebrationn = DB::table('users')->leftJoin('employees', function ($join) {
        $join->on('users.id', '=', 'employees.user_id');
    });
    $todaycelebrationn->where(function ($query) use ($today) {
        $query->whereMonth('dob', $today->month)
            ->whereDay('dob', $today->day);
    })->orWhere(function ($query) use ($today) {
        $query->whereMonth('anniversary_date', $today->month)
            ->whereDay('anniversary_date', $today->day);
    })->orWhere(function ($query) use ($today) {
        $query->whereMonth('joined_date', $today->month)
            ->whereDay('joined_date', $today->day);
    });
    $todaycelebrationn->where('users.status', 1);
    $todaycelebrationn->where('users.admin_id', $request->admin_id);
    if (!empty($request->offset)) {
        $offset = $request->offset;
        $todaycelebrationn->offset($offset)->limit($page_limit);
    }
    // pr(getQuery($todaycelebrationn));die;
    $todaycelebrationns = $todaycelebrationn->get();
    // dd($todaycelebrationns);

    foreach ($todaycelebrationns as $key => $value) {
        $imgPath = config('constants.user_image_path');
        $imgDefaultPath = config('constants.default_user_image_path');
        $value->profile_image = ImageShow($imgPath, $value->profile_image, 'icon', $imgDefaultPath);

        $dob = strtotime($value->dob);
        $dob = date('m-d', $dob);

        $aniversary = strtotime($value->anniversary_date);
        $aniversary = date('m-d', $aniversary);

        $joined_date = strtotime($value->joined_date);
        $joined_date = date('m', $joined_date);
        // dd($dob == date('m-d'));
        if ($dob == date('m-d')) {
            $todaycelebrationns[$key]->type = "birthday";
            $todaycelebrationns[$key]->wish_msg = 'Happy birthday to you ' . $value->name . ' sir';
        } elseif ($aniversary == date('m-d')) {
            $todaycelebrationns[$key]->type = "anniversary";
            $todaycelebrationns[$key]->wish_msg = 'Happy anniversary to you ' . $value->name . ' sir';
        } elseif ($joined_date == date('m-d')) {
            $todaycelebrationns[$key]->type = "joined_date";
            $todaycelebrationns[$key]->wish_msg = 'Happy joining anniversary to you ' . $value->name . ' sir';
        }
    }
    return $todaycelebrationns;
}

function ip_in_range($ip, $range)
{
    if (strpos($range, '/') == false) {
        $range .= '/32';
    }
    // $range is in IP/CIDR format eg 127.0.0.1/24
    list($range, $netmask) = explode('/', $range, 2);
    $range_decimal = ip2long($range);
    $ip_decimal = ip2long($ip);
    $wildcard_decimal = pow(2, (32 - $netmask)) - 1;
    $netmask_decimal = ~$wildcard_decimal;
    return (($ip_decimal & $netmask_decimal) == ($range_decimal & $netmask_decimal));
}

function checkIpAddress($request)
{
    $userIp = request()->ip();
    $locationData = IPLocation::get($userIp);
    $ip = '';
    if ($locationData != false) {
        $ip = $locationData->ip;
    }
    $location = getLocationUser($request);
    foreach ($location as $key => $value) {
        if (!empty($value->ip)) {
            $check =  ip_in_range($ip, $value->ip);
            if ($check == true) {
                return true;
            }
        }
    }
    return false;
}

function getLocationUser($request)
{
    $location = Location::leftJoin('location_users', function ($join) {
        $join->on('locations.id', '=', 'location_users.location_id');
    });
    $locationUser   =   $location->where('location_users.user_id', $request->user_id)->where('location_users.admin_id', $request->admin_id)->where('locations.status', 1)->get();
    return $locationUser;
}

function getPolicyList()
{
    $policy = UserPolicy::where('admin_id', Config::get('auth_detail')['admin_id'])->where('status', 1)->get()->pluck("policy_name", "id")->toArray();
    return $policy;
}


function checkAttendanceCondition($attendance)
{
    $userData = User::getUserDetails($attendance->user_id, 'emp');
    $shifts = Shift::where('shifts.id', $attendance->shift_id)->select(['shifts.*'])->first();
    $todayAttendance = Attendance::getTodayAttendanceData($attendance);
    $userPolicy = UserPolicy::getEmployeePolicy($userData->policy_id);
    $current_date = Config::get('current_date');
    $workingHour = strtotime($shifts->to_time) - strtotime($shifts->from_time);
    $halfworkingHour = $workingHour / 2;
    $workingHour = gmdate('H:i:s', $workingHour);
    $halfworkingHour = gmdate('H:i:s', $halfworkingHour);
    $weekday = date('l', strtotime($attendance->attendance_date));
    $holiday = Holiday::where('admin_id', $attendance->admin_id)->where('status', 1)->where('date', $attendance->attendance_date)->first();
    $attendance_status =    'P';
    $description =    'Present';

    if ($userPolicy->eneble_weekoff_working_hours == 1 && $userData->weekly_holiday == $weekday) {
        Attendance::where('id', $attendance->id)->update(['overday' => 1]);
        ///Weekoff Woking Hours Policy
        if (!empty($userPolicy->weekoff_working_hours)) {
            $weekoff_relaxation =  date('H:i:s', strtotime('-' . $userPolicy->weekoff_working_hours . ' min', strtotime($workingHour)));
            if ($weekoff_relaxation > $attendance->working_hours) {
                $attendance_status = 'HD';
                $description = 'Incomplete Hours';
            }
        }
    } elseif ($userPolicy->eneble_holiday_working_hours == 1 && !empty($holiday) && $holiday->date == $attendance->attendance_date) {
        Attendance::where('id', $attendance->id)->update(['overday' => 1]);
        ///Holiday Woking Hours Policy
        if (!empty($userPolicy->holiday_working_hours)) {
            $holiday_relaxation =  date('H:i:s', strtotime('-' . $userPolicy->holiday_working_hours . ' min', strtotime($workingHour)));
            if ($holiday_relaxation > $attendance->working_hours) {
                $attendance_status = 'HD';
                $description = 'Incomplete Hours';
            }
        }
    } elseif ($userPolicy->eneble_working_hours_relaxation == 1) {
        // Daily Working Hours Policy
        if (!empty($userPolicy->fullday_relaxation)) {
            $fullday_relaxation =  date('H:i:s', strtotime('-' . $userPolicy->fullday_relaxation . ' min', strtotime($workingHour)));
            if ($attendance->attendance_status == "HL") {
                $attendance_status = 'HD-HL';
                $description = 'Half Day and Haif Leave';
            } elseif ($fullday_relaxation > $attendance->working_hours) {
                $attendance_status = 'HD';
                $description = 'Incomplete Hours';
            }
        }
        if (!empty($userPolicy->halfday_relaxation)) {
            $halfday_relaxation =  date('H:i:s', strtotime('-' . $userPolicy->halfday_relaxation . ' min', strtotime($halfworkingHour)));
            if ($halfday_relaxation > $attendance->working_hours) {
                $attendance_status = 'A';
                $description = 'Incomplete Hours';
            }
        }
    }

    Attendance::where('id', $attendance->id)->update(['attendance_status' => $attendance_status, 'description' => $description]);

    // Late Coming Policy
    if ($userPolicy->eneble_late_coming == 1) {
        $month = date('Y-m', strtotime($attendance->attendance_date));
        $thismodel =   Attendance::where('admin_id', $attendance->admin_id)->where('user_id', $attendance->user_id)->where('overday', '0')->where('status', 1);
        $thismodel->where('attendance_date', 'Like', '%' . $month . '%')->whereDate('attendance_date', '<=', $attendance->attendance_date);

        $thismodel->where(function ($query) {
            $query->where('attendance_status', 'P');
            $query->orWhere(function ($qu) {
                $qu->where('attendance_status', 'HD');
                $qu->where('description', 'Like', '%Late Come%');
            });
        });
        $thismodel->groupBy('attendance_date')->orderBy('attendance_date', 'ASC');
        // pr(getQuery($thismodel));die;
        $lateattendance = $thismodel->get();
        // pr(getQuery($lateattendance));die;
        $i = 0;
        foreach ($lateattendance as $key => $value) {
            $from_date = date('Y-m-d', strtotime($attendance->from_time));
            $from_time = date('Y-m-d H:i:s', strtotime('+' . $userPolicy->late_coming_relaxation . ' minutes', strtotime($from_date . ' ' . $shifts->from_time)));

            if ($value->from_time > $from_time) {
                $late_come_date[] = $value->attendance_date;
                $i++;
                if ($i == $userPolicy->late_coming_deduction_repeate) {
                    $late_come_date = implode(',', $late_come_date);
                    $attendance_status = 'HD';
                    $description = 'Late Come (' . $late_come_date . ')';
                    Attendance::where('id', $value->id)->update(['attendance_status' => $attendance_status, 'description' => $description]);
                    $i = 0;
                    $late_come_date = [];
                }
            }
        }
    }

    // Early Going Policy

    if ($userPolicy->eneble_early_going == 1) {

        $month = date('Y-m', strtotime($attendance->attendance_date));

        $thismodel = Attendance::where('admin_id', $attendance->admin_id)->where('user_id', $attendance->user_id)->where('overday', '0')->where('status', 1)->where('attendance_date', 'Like', '%' . $month . '%')->whereDate('attendance_date', '<=', $attendance->attendance_date);


        $thismodel->where(function ($query) {
            $query->where('attendance_status', 'P');
            $query->orWhere(function ($qu) {
                $qu->where('attendance_status', 'HD');
                $qu->where('description', 'Like', '%Early Go%');
            });
        });

        $thismodel->groupBy('attendance_date')->orderBy('attendance_date', 'ASC');
        // pr(getQuery($thismodel));die;
        $earlygoattendances = $thismodel->get();
        // dd($earlygoattendances->toArray());
        $i = 0;
        $early_come_date = [];
        foreach ($earlygoattendances as $key => $value) {
            $to_date = date('Y-m-d', strtotime($value->to_time));
            $to_time = date('Y-m-d H:i:s', strtotime('-' . $userPolicy->early_going_relaxation . ' minutes', strtotime($to_date . ' ' . $shifts->to_time)));
            if ($value->to_time < $to_time) {
                $early_come_date[] = $value->attendance_date;
                $i++;
                if ($i == $userPolicy->late_coming_deduction_repeate) {
                    $early_come_date = implode(',', $early_come_date);
                    $attendance_status = 'HD';
                    $description = 'Early Go (' . $early_come_date . ')';
                    Attendance::where('id', $value->id)->update(['attendance_status' => $attendance_status, 'description' => $description]);
                    $i = 0;
                    $early_come_date = [];
                }
            }
        }
    }
    return true;
}


function getMonthDates($attributes = [])
{
    $year  = date('Y', strtotime($attributes['month']));
    $month  = date('m', strtotime($attributes['month']));
    $today =    now();
    $numDays = Carbon::create($year, $month, 1)->daysInMonth;
    if ($year == $today->year && $month == $today->month) {
        $restDays = $numDays - $today->day;
        $numDays = $numDays - $restDays;
    }
    for ($i = 1; $i <= $numDays; $i++) {
        $data = Carbon::create($year, $month, $i);
        $dates[] = $data->format('Y-m-d');
    }
    return $dates;
}


function MonthlyAttendance($admin_id, $user_id, $shift_id)
{
    $month = 03;
    $year = 2023;

    $numDays = Carbon::create($year, $month, 1)->daysInMonth;

    $userData = User::getUserDetails($user_id, 'emp');
    for ($i = 1; $i <= $numDays; $i++) {
        $data = Carbon::create($year, $month, $i);
        $date = $data->format('Y-m-d');
        $datetime = $data->format('Y-m-d H:i:s');
        $shifts = Shift::getUserShift($shift_id);
        $in_time =  $shifts->from_time;
        $out_time =  $shifts->to_time;
        $format = 'Y-m-d H:i:s';
        $fromtime = DateTime::createFromFormat($format, $date . ' ' . $in_time)->format('Y-m-d H:i:s');
        $totime = DateTime::createFromFormat($format, $date . ' ' . $out_time)->format('Y-m-d H:i:s');
        $weekday = date('l', strtotime($date));
        // dd($weekday);

        $array = [];


        $array = array(
            'admin_id' => $admin_id,
            'user_id' => $user_id,
            'shift_id' => $shift_id,
            'from_time' => $fromtime,
            'to_time' => $totime,
            'working_hours' => '09:00:00',
            'overtime' => '00:00:00',
            'early_in' => '00:00:00',
            'late_out' => '00:00:00',
            'late_in' => '00:00:00',
            'early_out' => '00:00:00',
            'attendance_date' => $date,
            'status' => 1,
            'created_at' => $datetime,
        );

        if ($userData->weekly_holiday == $weekday) {
            $array['attendance_type'] = 'None';
            $array['attendance_status'] = 'WO';
        } else {
            $array['attendance_type'] = 'Auto';
            $array['attendance_status'] = 'P';
        }
        Attendance::create($array);
    }

    dd('done');
}

function getListTranslate($data)
{
    if (is_array($data)) {
        foreach ($data as $key => $tet) {
            $data[$key] = __($tet);
        }
    } else {
        $data = __($data);
    }
    return $data;
}

function gettWeekOffDates($userData, $userMonth)
{
    $dates = getMonthDates(['month' => $userMonth]);
    foreach ($dates as $key => $date) {
        $weekday = date('l', strtotime($date));
        if ($userData->weekly_holiday == $weekday) {
            $weekDates[] =  $date;
        }
    }
    return $weekDates;
}

function decimalHours($time)
{
    $hms = explode(":", $time);
    return ($hms[0] + ($hms[1] / 60));
}

function checkUserLimit()
{
    $admin_id = Config::get('auth_detail')['admin_id'];
    $adminData = User::getUserDetails($admin_id);
    $packageDetail = Package::getPackageDetail($adminData->package_uni_id);
    $activeUser = User::getAdminActiveUser($admin_id);
    if ($packageDetail->user_limit < $activeUser) {
        return false;
    }
    return true;
}

function getAdminSettingData($admin_id)
{
    $adminSeting = getAllSettings($admin_id);
    $master_data = [];
    foreach ($adminSeting as $key => $v) {
        $master_data[$v['setting_name']] = !empty($v['setting_value']) ? $v['setting_value'] : '';
    }
    return $master_data;
}

function multiPackageTimePeriod($package_uni_id)
{
    $package = Package::where('package_uni_id', $package_uni_id)->first();
    $timePeriodArray = [
        0   =>  '1',
        5   =>  '3',
        10  =>  '6',
        15  =>  '12'
    ];
    $i = 1;
    foreach ($timePeriodArray as $key => $val) {
        $TimePeriodPackageArray[$i]['package_type']       =   $package->package_type;
        $TimePeriodPackageArray[$i]['package_uni_id']     =   $package->package_uni_id;
        $TimePeriodPackageArray[$i]['name']               =   $package->name;
        $TimePeriodPackageArray[$i]['price']              =   packagePriceCalculation($package, $val, $key);
        $TimePeriodPackageArray[$i]['trial_duration']     =   $package->trial_duration;
        $TimePeriodPackageArray[$i]['duration']           =   $package->duration * $val;
        $TimePeriodPackageArray[$i]['user_limit']         =   $package->user_limit;
        $TimePeriodPackageArray[$i]['label']              =   $package->label;
        $TimePeriodPackageArray[$i]['description']        =   $package->description;
        $TimePeriodPackageArray[$i]['status']             =   $package->status;
        $i++;
    }
    return $TimePeriodPackageArray;
}

function packagePriceCalculation($package, $time, $discount)
{
    $packOriginalPrice  = $package->price * $time;
    $Discount = $packOriginalPrice *  $discount / 100;
    $packagePrice = $packOriginalPrice - $Discount;
    return round($packagePrice);
}