<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserAuth
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
        $data = session()->get('userdetail');
        // dd($data);
        // if (empty($data) && $data->role_id == 4) {
        //     return redirect()->route('index')
        //         ->with('error', 'Please login in Panel');
        // }
        // if (!empty($data) && $data->role_id != 4) {
        //     return redirect()->route('index')
        //         ->with('error', 'You don\'t have permission this page.');
        // }
        return $next($request);
    }
}
