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
</head>

<body>
    @include('layouts.front-user.alert_message')

    <section class="emplogin pt-5">
        <div id="Emplogin" class="tabcontent">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">

                        <div class="Emplogin">
                            <p class="text-light fw-bold fs-2 text-center px-4">Login</p>
                            <p class="text-light  text-center">Enter Your Details to get started</p>
                            <form action="{{ route('employeeloginstore') }}" method="post">
                                @csrf
                                <input type="text" class="bg-light p-3 rounded-5 w-100 my-3 border-0 subhead" name="username" placeholder="Username"> <br>
                                <input type="password" class="bg-light p-3 rounded-5 w-100 my-3  border-0 subhead" name="password" placeholder="Password"><br>

                                <div class="d-flex align-items-center">
                                    <div class="mt-3 form-check">
                                        <input type="checkbox" class="form-check-input " id="remember">
                                        <label for="" class="text-light">Remember me</label>
                                    </div>
                                    <p class="text-light my-2 ms-auto">Forget Password?<a href="{{ route('showForgetPasswordForm') }}" class="mx-2 text-decoration-none fw-semibold">Click Here</a></p>
                                </div>
                                <button class="bgtheme text-light border-0  py-2 my-3 col-12 mx-auto rounded-3 border-0 subhead fw-semibold tablinks">Login</button>
                            </form>
                            <p class="text-light text-center fs-5 my-3 tablinks" id="defaultOpen" onclick="openClass(event, 'Emplogin')">----------- Or -----------</p>

                            <button class="themeclr border-0  py-2 col-12 px-5 mx-auto rounded-3 border-0 subhead fw-semibold  loginmob text-light" onclick="openClass(event, 'Numberlogin')">Login with Mobile Number</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div id="Numberlogin" class="tabcontent">
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

                                <form id="getAdminId" method="POST">
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


                                        <button type="submit" class="themeclr border-0  py-2 col-10 px-5 mx-auto rounded-3 border-0 subhead fw-semibold  loginmob text-light" id="defaultOpen" onclick="openClass(event, 'Number')">Submit</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="Number" class="tabcontent">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <!-- <form action="" id="loginwithOtp" method="post"> -->
                        <form  id="loginwithOtp" method="POST">

                            @csrf
                            <div class="Emplogin">
                                <p class="text-light fw-bold fs-2 text-center px-4">Login</p>
                                <p class="text-light  text-center">Enter Your Details to get started</p>

                                <input type="hidden" name="admin_id" class="admin_id">
                                <input type="text" id="phoneInput" name="phone" class="bg-light p-3 rounded-5 w-100 my-3 border-0 subhead" placeholder="Enter Your Mobile No."> <br>
                                <button class="bgtheme border-0 px-5 py-2 col-12 rounded-4  my-3 text-light fw-bold subhead tablinks" type="submit" onclick="openClass(event, 'Sendotp')">Send Otp</button>
                                <p class="text-light text-center fs-5 my-3" id="defaultOpen" onclick="openClass(event, 'Login')">
                                    ----------- Or -----------</p>

                                <button class="themeclr border-0  py-2 col-12 px-5 mx-auto rounded-3 border-0 subhead fw-semibold  loginmob text-light" id="defaultOpen" onclick="openClass(event, 'Emplogin')">Login with Username</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="Sendotp" class="tabcontent">
            <div class="text-center">
                <p class="fw-bold fs-5 text-light mt-5">Please enter the One-Time password to verify Your account</p>
                <h4 class="fs-6 mt-5 text-light fw-600">A Otp has been sent to</span> <small>your mobile no </small> </h4>
                <div class="card-text text-center mt-5">
                    <!-- <form id="otp" method="post"> -->
                    <form id="otp" class="otp-form" method="post">
                        @csrf
                        <input type="integer" name="mobile_otp" class="otp-field text-light fw-bold mobile_otp" id="mobile_otp" maxlength="6" placeholder="Enter OTP" />
                        <input type="hidden" name="admin_id" class="admin_id">
                        <input type="hidden" id="phone" name="phone" id="phone" class="phone">
                        <!-- <input type="hidden" class="form-control" id="web" value="web" name="web" required> -->

                        <!-- Store OTP Value -->
                        <!-- <input class="otp-value" type="hidden" name="opt-value"> -->
                        <div class="d-block my-5">
                            <button class="bg-light border px-5 py-2 rounded-4 themeclr fw-bold subhead tablinks" type="submit">Verify</button>
                        </div>
                    </form>
                    <div class="d-block mt-4">
                        <a href="">
                            <p class="text-light mb-1 fw-semibold">Resend OTP</p>
                        </a>
                        <p class="text-light mt-0 fw-semibold">Entered a wrong number?</p>
                    </div>

                </div>
            </div>
        </div>


    </section>

    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/index.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.js') }}"></script>

    <script>
        $(document).on('submit', '#otp', function(e) {
            e.preventDefault();
            // alert('xdd');
            let _token = $('meta[name="csrf-token"]').attr('content');
            var formData = new FormData(this);
            var name = $('#login_otp').val();
            $.ajax({
                url: "{{ url('verify-otp') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(result) {
                    // console.log(result);
                    // $('.admin_id').val(result.data.admin_id);
                }
            });
        });


        $(document).on('submit', '#loginwithOtp', function(e) {

            let _token = $('meta[name="csrf-token"]').attr('content');
            e.preventDefault();
            var formData = new FormData(this)

            $.ajax({
                url: "{{ url('get-otp') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(result) {
                    // console.log(result);
                    $('.phone').val(result.phone);
                    if (result.data == 'You Are Login') { // if true (1)
                        toastr.success(result.data);
                        setTimeout(function() { // wait for 5 secs(2)
                            location.reload(); // then reload the page.(3)
                        }, 500);
                        $('.logiform').modal('hide')
                        $('.modal-backdrop').modal('hide')
                    } else {
                        toastr.error(result.data);
                    }

                }
            });
        });


        $(document).on('submit', '#getAdminId', function(e) {
            e.preventDefault();
            let _token = $('meta[name="csrf-token"]').attr('content');
            var formData = new FormData(this);
            // formData.append('admin_id', $('#admin_id').val());

            $.ajax({
                url: "{{ url('api/checkAdminID') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(result) {
                    $('.admin_id').val(result.data.admin_id);
                }
            });
        });


        function openClass(evt, className) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(className).style.display = "block";
            evt.currentTarget.className += " active";
        };


        document.getElementById("defaultOpen").click();

        document.addEventListener("DOMContentLoaded", function(event) {

            function OTPInput() {
                const inputs = document.querySelectorAll('#otp > *[id]');
                for (let i = 0; i < inputs.length; i++) {
                    inputs[i].addEventListener('keydown', function(event) {
                        if (event.key === "Backspace") {
                            inputs[i].value = '';
                            if (i !== 0) inputs[i - 1].focus();
                        } else {
                            if (i === inputs.length - 1 && inputs[i].value !== '') {
                                return true;
                            } else if (event.keyCode > 47 && event.keyCode < 58) {
                                inputs[i].value = event.key;
                                if (i !== inputs.length - 1) inputs[i + 1].focus();
                                event.preventDefault();
                            } else if (event.keyCode > 64 && event.keyCode < 91) {
                                inputs[i].value = String.fromCharCode(event.keyCode);
                                if (i !== inputs.length - 1) inputs[i + 1].focus();
                                event.preventDefault();
                            }
                        }
                    });
                }
            }
            OTPInput();
        });


        var phoneInput = document.getElementById("phoneInput");
        phoneInput.addEventListener("input", addCountryCode);

        function addCountryCode() {
            var phoneNumber = phoneInput.value;
            if (!phoneNumber.startsWith("+91")) {
                phoneInput.value = "+91" + phoneNumber;
            }
        }
    </script>
</body>

</html>