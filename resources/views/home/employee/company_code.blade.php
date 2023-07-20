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
        html,
        body {
            height: 100%;
            display: flex;
            align-items: center;
            /* Center vertically */
            justify-content: center;


        }

        main.login-form {
            width: 800px;
            height: 400px;
            margin-left: auto;
            margin-right: auto;
            margin-top: 200px;
            margin-bottom: auto;

        }

       
    </style>
</head>

<body>
    @include('layouts.front-user.alert_message')

    <main class="emplogin pt-5">

        <main class="login-form">
            <div class="cotainer">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="">
                            <p class="text-light fw-bold fs-4 offset-md-4 px-2 mb-4">Company Code</p>
                            <!-- <div class="text-light">Reset Password</div> -->
                            <div class="card-body">

                                @if (Session::has('message'))
                                <div class="alert alert-success" role="alert">
                                    {{ Session::get('message') }}
                                </div>
                                @endif

                                <form action="{{ route('submitForgetPasswordForm') }}" method="POST">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="company_code" class="col-md-4 col-form-label fs-4 text-md-right text-light">Company Code</label>
                                        <div class="col-md-5">
                                            <input type="text" id="company_code" class="form-control" name="company_code" required autofocus>
                                            @if ($errors->has('company_code'))
                                            <span class="text-danger">{{ $errors->first('company_code') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-8 offset-md-3 px-5">
                                        <button type="submit" class="btn btn-primary my-4 mx-3">
                                            Submit
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </main>

</body>

</html>