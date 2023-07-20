<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $loggedUser = Auth::user();
        $modulearray = packageloginOperaton();
        if ($modulearray == 'success') {
            $module = $modulearray;
        } else {
            $module =  $modulearray[0];
        }
        
        $result = '';
        if (!empty($loggedUser) && $module != 'success' && $loggedUser->role_id > config('constants.superadmin_role_id')) {
            $parent = User::where('user_uni_id', $loggedUser->admin_id)->first();
            if ($loggedUser->role_id == config('constants.admin_role_id')) {
                $result = getModuleAccess($module, $loggedUser->package_uni_id);
                $packageData    =   Package::where('package_uni_id',$loggedUser->package_uni_id)->first();
            }else{
                $result = getModuleAccess($module, $parent->package_uni_id);
                $packageData    =   Package::where('package_uni_id',$parent->package_uni_id)->first();
            }
            if($packageData->package_type == 'paid'){
                if (!$result) {
                    return redirect()->route('admin.dashboard')->with('error', 'You do not have permission that page. Your plan expiry or not in package');
                }
                if ($loggedUser->role_id == config('constants.admin_role_id') && $loggedUser->package_valid_date < Config::get('current_date')) {
                    return redirect()->route('admin.dashboard')->with('error', 'You plan expiry.Please recharege immediately');
                } 
                // if ($loggedUser->role_id > config('constants.admin_role_id') && $parent->package_valid_date < Config::get('current_date')) {
                //     return redirect()->route('admin.dashboard')->with('error', 'Your admin plan expiry.Contact with Authorises Person');
                // }
            }else{
                if (!$result) {
                    return redirect()->route('admin.dashboard')->with('error', 'You do not have permission that page. These module not in package');
                }
            }
        }
        return $next($request);
    }
}
