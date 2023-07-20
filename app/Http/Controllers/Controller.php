<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Package;
use App\Models\Setting;
use App\Models\Attendance;
use App\Models\RechargeHistory;
use App\Models\LeaveApplication;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
        // dd(url("assets/fonts/hindi-eng/SAMAN___.ttf"));
        // tt();
        Config::set('current_time', date('H:i:s'));
        Config::set('current_date', date('Y-m-d'));
        Config::set('current_datetime', date('Y-m-d H:i:s'));
        Config::set('yesterday_date', Carbon::yesterday()->toDateString());
        if(empty(session()->get('locale'))){
            App::setLocale(Config::get('company_lang'));
            session()->put('locale', Config::get('company_lang'));
        }
        // Attendance::dailyAttendanceCalculation();
       
        $this->middleware(function ($request, $next) {
            $settings = Setting::where('admin_id', '1')->get();
            foreach ($settings as $setting) {
                Config::set($setting['setting_name'], $setting['setting_value']);
            }
            if (Auth::guard('front-admin')->check()) {
                $packageDetails = Package::where('package_uni_id',Auth::guard('front-admin')->user()->package_uni_id)->first();
                $rechargeHistory = RechargeHistory::where('package_uni_id', Auth::guard('front-admin')->user()->package_uni_id)->where('admin_id', Auth::guard('front-admin')->user()->admin_id)->orderBy('id','desc')->first();
                Auth::guard('front-admin')->user()->setAttribute('package_name',$packageDetails->name);
                Auth::guard('front-admin')->user()->setAttribute('package_price',$packageDetails->price);
                Auth::guard('front-admin')->user()->setAttribute('package_duration',$packageDetails->duration);
                Auth::guard('front-admin')->user()->setAttribute('package_type',$packageDetails->package_type);
                Auth::guard('front-admin')->user()->setAttribute('package_label',$packageDetails->label);
                if(!empty($rechargeHistory)){
                    Auth::guard('front-admin')->user()->setAttribute('recharge_order_id',$rechargeHistory->order_id);
                    Auth::guard('front-admin')->user()->setAttribute('recharge_razorpay_id',$rechargeHistory->razorpay_id);
                    Auth::guard('front-admin')->user()->setAttribute('recharge_date',$rechargeHistory->created_at);
                    Auth::guard('front-admin')->user()->setAttribute('recharge_uni_id',$rechargeHistory->recharge_uni_id);
                }
                $loggedUser = Auth::guard('front-admin')->user();
            } elseif (Auth::guard('front-user')->check()) {
                $loggedUser = Auth::guard('front-user')->user();
            } else {
                $loggedUser = Auth::user();
            }
            if (!empty($loggedUser)) {
                Config::set('auth_detail', $loggedUser->toArray());
                $settings = Setting::where('admin_id', $loggedUser->admin_id)->get();
                foreach ($settings as $key => $setting) {
                    Config::set($setting['setting_name'], $setting['setting_value']);
                }
            }
            // dd(config());
            // createSalaryGenrate();
            $loginOperationValue = arrayReplaceValue();
            if (!in_array($loginOperationValue, ['dashboard', 'login', 'logout'])) {
                // dd(role_module_permission());
                if (!role_module_permission()) {
                    return redirect()->route('admin.dashboard')->with('error', 'You do not have permission that page');
                }
            }
            return $next($request);
        });
        // 
    }
}
