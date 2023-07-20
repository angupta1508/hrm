<!DOCTYPE html>
<html lang="en">

<head>
    <title>Employee login</title>

    <!--animation-->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link href="{{ asset('assets/front/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/front/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/toastr/toastr.css') }}" rel="stylesheet" />

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <style>
     /* Center the main section vertically and add top padding */
.emplogin {
  display: flex;
  align-items: center;
  justify-content: center;
  padding-top: 5rem;
}

/* Set the container width and height */
.cotainer {
  width: 800px;
  height: 400px;
}

/* Add margin and font size to the labels */
label.col-form-label {
  margin: 8px 0;
  font-size: 0.875rem; /* Adjust the font size as needed */
}

   </style>
</head>

<body>
    @include('layouts.front-user.alert_message')
 
    <main class="emplogin pt-5">
        <div class="cotainer">
            <div class="row justify-content-center">
                                <div class="row">
                <div class="col-md-10">
                    <div class="">
                        @if(session('message'))
                        <div class="alert alert-danger">
                            {{ session('message') }}
                        </div>
                        @endif
                        <div class="card-header text-light fs-4 offset-md-3 px-2 mb-3">Reset Password</div>
                        <div class="card-body">
                            <form action="{{ route('submitResetPasswordForm') }}" method="POST">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">

                                <div class="row" style="margin: 20px 0">
                                    <label for="email_address" class="col-md-3 col-form-label text-md-right text-light  fs-6">E-Mail Address</label>
                                    <div class="col-md-8">
                                        <input type="text" id="email_address" class="form-control" name="email" required autofocus>
                                        @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row" style="margin: 20px 0">
                                    <label for="password" class="col-md-3 col-form-label text-md-right text-light fs-6">Password</label>
                                    <div class="col-md-8">
                                        <input type="password" id="password" class="form-control" name="password" required autofocus>
                                        @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row" style="margin: 20px 0">
                                    <label for="password-confirm" class="col-md-3 col-form-label text-md-right text-light  fs-6">Confirm
                                        Password</label>
                                    <div class="col-md-8">
                                        <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required autofocus>
                                        @if ($errors->has('password_confirmation'))
                                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row" style="margin: 20px 0">
                                    <div class="col-md-6 offset-md-3">
                                        <div class="form-check text-start">
                                            <input class="form-check-input" type="checkbox" id="show-password-checkbox">
                                            <label class="form-check-label text-light" for="show-password-checkbox">
                                                Show Password
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group offset-md-3 px-2">
                                    <button type="submit" class="btn btn-primary">
                                        Reset Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </div>



    <script>
        $(document).ready(function() {
            $('#show-password-checkbox').change(function() {
                var passwordInput = $('#password');
                var confirmPasswordInput = $('#password-confirm');

                if ($(this).is(':checked')) {
                    passwordInput.attr('type', 'text');
                    confirmPasswordInput.attr('type', 'text');
                } else {
                    passwordInput.attr('type', 'password');
                    confirmPasswordInput.attr('type', 'password');
                }
            });
        });
    </script>
</body>

</html>