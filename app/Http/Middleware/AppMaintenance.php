<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AppMaintenance
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
        $data = getSettingData('maintenance_mode',config('constants.superadmin_role_id'),'val');
        if($data == 1){
            $result = array(
                'status' => 0,
                'msg' => 'App is on Maintenance',
            );
            return response()->json($result);
        }
        return $next($request);
    }
}
