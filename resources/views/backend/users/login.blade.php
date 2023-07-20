@extends('layouts.admin.app')

@section('content')

<main class="main-content mt-0">
    <section>
        <div class="page-header min-vh-75">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                        <div class="card mt-8">
                            <div class="card-header pb-0 text-center bg-transparent">
                                @php 
                                    $company_logo = getSettingData('logo', config('constants.superadmin_role_id'), 'val');
                                    $imgPath = config('constants.setting_image_path');
                                    $imgDefaultPath = config('constants.default_image_path');
                                    $logo = ImageShow($imgPath, $company_logo, 'icon', $imgDefaultPath);
                                @endphp
                                <img src="{{ $logo }}"
                                                      style="height: 50px; width: auto;">
                                <h3 class="font-weight-bolder  text-info text-gradient">Login</h3>

                            </div>
                            <div class="card-body">
                                <form method="post" action="{{ route('admin.loginStore') }}">
                                    @csrf
                                    <label>Username</label>
                                    <div class="mb-3">
                                        <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="" aria-label="Username" aria-describedby="username-addon">
                                        @error('email')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <label>Password</label>
                                    <div class="mb-3" style="position: relative;">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="" aria-label="Password" aria-describedby="password-addon">
                                        <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password" style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%);"></span>                                        
                                        @error('password')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                                        <label class="form-check-label" for="rememberMe">Remember me</label>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Sign in</button>
                                    </div>
                                </form>
                                
                            </div>
                            <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <small class="text-muted">Forgot you password? Reset you password 
                                    <a href="{{ route('admin.forgotPassword') }}" class="text-info text-gradient font-weight-bold">here</a>
                                </small>
                                                
                                <!-- <p class="mb-4 text-sm mx-auto">
                                    Don't have an account?
                                    <a href="{{ route('admin.register') }}" class="text-info text-gradient font-weight-bold">Sign up</a>
                                </p> -->
                               
                                <div class="mt-3">
                                    @include('layouts.alert_message')
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>

@endsection
