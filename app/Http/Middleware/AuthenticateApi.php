<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Api;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $user = User::where('id',$request->admin_id)->where('role_id',config('constants.admin_role_id'))->first();
        if(!empty($user))
        {
            if($user->package_valid_date >= Config::get('current_date'))
            {
                $authorizationHeader = $request->header('Authorization');
                if (!$authorizationHeader) {
                    return response()->json(['status' => 0,'msg' => 'Unauthorized']);
                }
                
                $authorizationHeader = str_replace('Basic ', '', $authorizationHeader);
                $decodedHeader = base64_decode($authorizationHeader);
                $credentials = explode(':', $decodedHeader);
                // dd(Api::checkUserApiKey($credentials[1],$credentials[0]));
                if (count($credentials) !== 2 || Api::checkUserApiKey($credentials[1],$credentials[0]) == false) {
                    return response()->json(['status' => 0, 'msg' => 'Unauthorized']);
                }
            }else{
                return response()->json(['status' => 0, 'msg' => __('Your Admin Package Expiry.')]);
            }
        }else{
            return response()->json(['status' => 0, 'msg' => __('Admin Id is Invalid.')]);
        }

        return $next($request);
    }
}
