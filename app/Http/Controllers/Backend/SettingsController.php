<?php

namespace App\Http\Controllers\Backend;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function company()
    {
        $settings   =   getSettings('company');
        return view('backend.setting.index', ['settings' => $settings,'setting_type' => 'company']);
    }

    public function email()
    {
        $settings   =   getSettings('email');
        return view('backend.setting.index', ['settings' => $settings ,'setting_type' => 'email', 'no_change_msg' => Config::get('constants.no_change_msg')]);
    }

    public function sms()
    {
        $settings   =   getSettings('sms');
        return view('backend.setting.index', ['settings' => $settings ,'setting_type' => 'sms', 'no_change_msg' => Config::get('constants.no_change_msg')]);
    }
    
    public function payment()
    {
        $settings   =   getSettings('payment');
        return view('backend.setting.index', ['settings' => $settings ,'setting_type' => 'payment', 'no_change_msg' => Config::get('constants.no_change_msg')]);
    }

    

    public function social()
    {
        $settings   =   getSettings('social');
        return view('backend.setting.index', ['settings' => $settings ,'setting_type' => 'social']);
    }

    public function ChangePassword()
    {
        $settings   =   getSettings('change_password');
        return view('backend.setting.change_password', ['settings' => $settings ,'setting_type' => 'change_password']);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $settings = [];
        $user_id = 0;
        if (!empty($request->id)) {
            $user_id = $request->id;
            $settings = Setting::where('user_id', $request->id)->get();
            if ($settings->count() == 0) {
                $settings = Setting::where('user_id', 0)->where('default_setting', 1)->get();
            }
        } else if ($user->role_id == 1) {
            $settings = Setting::where('user_id', 0)->get();
            $user_id = $user->id;
        }
        // dd($settings);

        return view('backend.setting.index', ['settings' => $settings, 'user_id' => $user_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        return view('backend.setting.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $attributes = request()->validate([
            'setting_name' => ['required', 'string'],
            // 'logo' => ['nullable'],
        ]);
        if (!empty($attributes['logo'])) {
            $img = 'logo';
            $imgPath = public_path('constants.setting_image_path');

            $filename = UploadImage($request, $imgPath, $img);
            $attributes['logo'] = $filename;
        }

        $attributes['status'] = 1;
        $attributes['created_at'] = date('Y-m-d H:i:s');
        $attributes['updated_at'] = date('Y-m-d H:i:s');

        $Setting = Setting::create($attributes);

        return redirect()->route('admin.setting.index')
            ->with('success', __('Setting created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    

    public function updateSetting(Request $request, Setting $settings)
    {
        // dd($request);
        $attributes = request()->validate([
            'setting' => ['required'],
            'setting_type' => ['nullable'],
        ]);
        $user = Auth::user();
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
                $filename = UploadImage($img, $imgPath, $key,$setting->setting_value,1);              
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
            }else{
                $setting->where([['setting_name', $key], ['admin_id', $user->id]])->update($saveDate);
            }
        }
        return back()->with('success', 'Setting updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
