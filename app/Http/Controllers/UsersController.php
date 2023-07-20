<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::latest()->paginate(5);

        return view('users.index', compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function login()
    {
        if (!Auth::gaurd('fron-user')->guest()) {
            return redirect()->route('admin.dashboard');
        }

        return view('backend.users.login');
    }

    public function loginStore(Request $request)
    {
       
        if (!Auth::gaurd('fron-user')->guest()) {
            return redirect()->route('admin.dashboard');
        }

        $attributes = request()->validate([
            'username' => 'required',
            'password' => 'required'
        ]);


        if (filter_var($attributes['username'], FILTER_VALIDATE_EMAIL)) {
            $attributes['email'] = $attributes['username'];
            unset($attributes['username']);
        }
        if (Auth::gaurd('fron-user')->attempt($attributes)) {
            session()->regenerate();
            $roles = Role::where('id', '=', Auth::gaurd('fron-user')->user()->role_id)->first();
            if (!$roles->status) {
                Auth::gaurd('fron-user')->logout();
                return back()->with('error', 'Your role is inactive. Contact with Authorises Person');
            }
            if (!Auth::gaurd('fron-user')->user()->status) {
                Auth::gaurd('fron-user')->logout();
                return back()->with('error', 'Your account is inactive. Contact with Authorises Person');
            }

            // if (checkRoleWisePermissions(Auth::gaurd('fron-user')->user())) {
                return redirect()->route('admin.dashboard')->with(['success' => __('You are logged in.')]);
            // } else {
            //     return redirect()->route('admin.dashboard')->with('error', 'Your admin plan expiry. Contact with Authorises Person');
            // }
        } else {
            return back()->withErrors(['email' => 'Username or password invalid.']);
        }

    }

}
