@extends('layouts.front.app')

@section('content')
    <main class="mainindex">
        <div class="row container mx-auto">
            <div class="col-xl-6  col-lg-6 col-md-12 col-sm-12  mt-5 mx-auto">
                <h2 class="mainheading text-light mt-5"> Hr Software
                    for the evelved</h2>
                <p class="mainpara text-light fw-bold">In publishing and graphic design, Lorem ipsum is a placeholder
                    text commonly used to demonstrate the visual form of a document or a typeface without relying on
                    meaningful content.</p>

                <input type="text" class="maininput px-2 " placeholder="Enter Your Mail Here">
                <span class="mainbtn text-light ps-3 pt-3 ">Get Started</span>

            </div>

            <div class="col-xl-6 col-lg-6 d-none d-lg-block">
                <img src="{{ asset('assets/front/img/Group 136.png') }}" class="group136  pe-5" alt="">
            </div>
        </div>

    </main>


    <!--our services-->
    <div class="ourservice" id="service">
        <div class="text-center">
            <p class="fs-2 fw-bold mb-1 themeclr">Our Services</p>
            <span class="text-black fw-600 subhead fw-semibold">Single Intergreted HR Platform for Growing Teams</span>
        </div>

        <!---->

        <div class="container services mx-auto my-5">
            <div class="row row-cols-1 row-cols-sm-1 row-cols-md-2 row-cols-lg-3 g-5">
                <div class="col">
                    <div class="servicecard">
                        <img src="{{ asset('assets/front/img/box-shape2 2.png') }}" alt=""
                            class="float-right hiddenimg righthoverimg" srcset="">
                        <div class="text-center pt-5 px-3">
                            <img src="{{ asset('assets/front/img/select 1.png') }}" class="seviceimg" alt="">
                            <h5 class="fw-bolder text-black my-3">Core HR</h5>
                            <p class="text-black">Powerful engine that drives every HR
                                function in your organization and acts
                                as the backbone of HR.</p>
                        </div>
                        <img src="{{ asset('assets/front/img/box-shape2 2.png') }}" alt=""
                            class="hoverimg hiddenimg" srcset="">
                    </div>
                </div>
                <!---->
                <div class="col">
                    <div class="servicecard">
                        <img src="{{ asset('assets/front/img/box-shape2 2.png') }}" alt=""
                            class="float-right hiddenimg righthoverimg" srcset="">
                        <div class="text-center pt-5 px-3">
                            <img src="{{ asset('assets/front/img/time-management 1.png') }}" class="seviceimg"
                                alt="">
                            <h5 class="fw-bolder text-black my-3">Expense Management</h5>
                            <p class="text-black">Powerful engine that drives every HR
                                function in your organization and acts
                                as the backbone of HR.</p>

                        </div>
                        <img src="{{ asset('assets/front/img/box-shape2 2.png') }}" alt=""
                            class="hoverimg hiddenimg" srcset="">
                    </div>
                </div>
                <div class="col">
                    <div class="servicecard">
                        <img src="{{ asset('assets/front/img/box-shape2 2.png') }}" alt=""
                            class="float-right hiddenimg righthoverimg" srcset="">
                        <div class="text-center pt-5  px-3">
                            <img src="{{ asset('assets/front/img/project-management 1.png') }}" class="seviceimg"
                                alt="">
                            <h5 class="fw-bolder text-black my-3">Time & Attendance</h5>
                            <p class="text-black">Powerful engine that drives every HR
                                function in your organization and acts
                                as the backbone of HR.</p>
                        </div>
                        <img src="{{ asset('assets/front/img/box-shape2 2.png') }}" alt=""
                            class="hoverimg hiddenimg" srcset="">
                    </div>
                </div>
                <div class="col">
                    <div class="servicecard">
                        <img src="{{ asset('assets/front/img/box-shape2 2.png') }}" alt=""
                            class="float-right hiddenimg righthoverimg" srcset="">
                        <div class="text-center pt-5  px-3">
                            <img src="{{ asset('assets/front/img/paycheck 1.png') }}" class="seviceimg1" alt="">
                            <h5 class="fw-bolder text-black my-3">Payroll</h5>
                            <p class="text-black">Powerful engine that drives every HR
                                function in your organization and acts
                                as the backbone of HR.</p>
                        </div>
                        <img src="{{ asset('assets/front/img/box-shape2 2.png') }}" alt=""
                            class="hoverimg hiddenimg" srcset="">
                    </div>
                </div>
                <!---->
                <div class="col">
                    <div class="servicecard">
                        <img src="{{ asset('assets/front/img/box-shape2 2.png') }}" alt=""
                            class="float-right hiddenimg righthoverimg" srcset="">
                        <div class="text-center pt-5 px-3">
                            <img src="{{ asset('assets/front/img/Layer 1 1.png') }}" class="seviceimg1" alt="">
                            <h5 class="fw-bolder text-black my-3">Performance </h5>
                            <p class="text-black">Powerful engine that drives every HR
                                function in your organization and acts
                                as the backbone of HR.</p>
                        </div>
                        <img src="{{ asset('assets/front/img/box-shape2 2.png') }}" alt=""
                            class="hoverimg hiddenimg" srcset="">
                    </div>
                </div>
                <!---->
                <div class="col">
                    <div class="servicecard">
                        <img src="{{ asset('assets/front/img/box-shape2 2.png') }}" alt=""
                            class="float-right hiddenimg righthoverimg" srcset="">
                        <div class="text-center pt-5 px-3">
                            <img src="{{ asset('assets/front/img/self-employed 1.png') }}" class="seviceimg"
                                alt="">
                            <h5 class="fw-bolder text-black my-3">Employee Onboarding</h5>
                            <p class="text-black">Powerful engine that drives every HR
                                function in your organization and acts
                                as the backbone of HR.</p>
                        </div>
                        <img src="{{ asset('assets/front/img/box-shape2 2.png') }}" alt=""
                            class="hoverimg hiddenimg" srcset="">
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!--sidebox-->
    <div class="sidetoggle">
        <div class="sidetab sidecircle toggle" id="loginTab"><i
                class="fa-solid fa-clipboard-list fs-4 text-light px-2"></i>
        </div>
        <div class="sidebar1" style={{ Auth::guard('front-admin')->check() ? 'display:none' : '' }}></div>
    </div>
    <div class="sidebox rounded-5">

        <div id="Login" class="tabcontent">
            <div class="payroll">
                <p class="text-light fw-bold fs-4 text-center px-4 my-4">Transform Your Payroll and Hr Process Today</p>


                <input type="text" class="bg-light p-3 rounded-5 w-100 my-3 border-0 subhead username"
                    placeholder="Username">
                <br>
                <input type="password" class="bg-light p-3 rounded-5 w-100 my-3  border-0 subhead pstr"
                    placeholder="Password"><br>

                <button
                    class="themeclr border-0  py-2 my-3 px-5 mx-auto rounded-5 border-0 subhead d-flex fw-semibold tablinks login-box">Login</button>
                {{-- onclick="openClass(event, 'Otp')" --}}

                <p class="text-light fs-5 text-center my-2 tablinks" id="defaultOpen"
                    onclick="openClass(event, 'Login')">Or</p>

                <button class="themeclr border-0  py-2 px-5 mx-auto rounded-5 border-0 subhead d-flex fw-semibold"
                    onclick="openClass(event, 'Register')">Register</button>
            </div>

        </div>


        <div id="Otp" class="tabcontent">
            <div class="text-center">
                <p class="fw-bold fs-5 text-light mt-5">Please enter the One-Time password to verify Your account</p>
                <h4 class="fs-6 mt-5 text-light fw-600">A code has been sent to</span> <small>*******9897</small> </h4>
                <div class="card-text text-center mt-5">
                    <form action="" id="otp" class="otp-form">
                        <input class="otp-field text-light fw-bold" type="text" id="first" name="opt-field[]"
                            maxlength=1>
                        <input class="otp-field text-light fw-bold" type="text" id="second" name="opt-field[]"
                            maxlength=1>
                        <input class="otp-field text-light fw-bold" type="text" id="third" name="opt-field[]"
                            maxlength=1>
                        <input class="otp-field text-light fw-bold" type="text" id="fourth" name="opt-field[]"
                            maxlength=1>


                        <!-- Store OTP Value -->
                        <!-- <input class="otp-value" type="hidden" name="opt-value"> -->
                        <div class="d-block my-5">
                            <button class="bg-light border px-5 py-2 rounded-4 themeclr fw-bold subhead tablinks"
                                type="button" onclick="openClass(event, 'Verified')">Validate</button>
                        </div>
                        <div class="d-block mt-4">
                            <a href="">
                                <p class="text-light mb-1 fw-semibold">Resend OTP</p>
                            </a>
                            <p class="text-light mt-0 fw-semibold">Entered a wrong number?</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="Register" class="tabcontent">
            <div class="payroll">
                <p class="text-light fw-bold fs-4 text-center px-4 my-4">Transform Your Payroll and Hr Process Today</p>
                <input type="text" class="bg-light p-3 rounded-5 w-100  my-3 border-0 subhead1"
                    placeholder="Full Name">
                <br>
                <input type="text" class="bg-light p-3 rounded-5 w-100  my-3  border-0 subhead1"
                    placeholder="Email Address"><br>
                <input type="text" class="bg-light p-3 rounded-5 w-100  my-3 border-0 subhead1"
                    placeholder="Phone Number"><br>
                <input type="text" class="bg-light p-3 rounded-5 w-100  my-3  border-0 subhead1"
                    placeholder="Your Company Name"><br>
                <button
                    class="themeclr border-0  py-2 px-4 mx-auto rounded-5 border-0 subhead fw-semibold d-flex">Register</button>

                <p class="text-light fs-5 text-center my-2 tablinks" id="defaultOpen">Or</p>

                <button class="themeclr border-0  py-2 px-5 mx-auto rounded-5 border-0 subhead d-flex fw-semibold"
                    onclick="openClass(event, 'Login')">Login</button>

            </div>
        </div>

        <div id="Verified" class="tabcontent">
            <div class="payroll text-center ">
                <img src="{{ asset('assets/front/img/Group156.png') }}" class="mt-5 mb-3" alt=""
                    srcset="">
                <div class="card py-2 mt-4 mx-3">
                    <p class="text-black fw-bold fs-5 mb-2">Successful!</p>
                    <p class="fw-bold fs-6 text-secondary mt-0">Verification Successfully done<i
                            class="fas fa-check-circle  fs-5 bggrn mx-1"></i></p>
                    <button
                        class="text-warning border-0  py-2 px-5 mx-auto rounded-5 border-0 subhead fw-semibold d-flex my-3"
                        style="background-color: rgba(255, 217, 0, 0.311);">Done</button>
                </div>
            </div>
        </div>

    </div>

    <!--feature-->
    <div class="feature">
        <div class="text-center">
            <p class="fs-2 fw-bold mb-1 themeclr">OUR App Feature </p>
            <span class="text-black fw-600 subhead fw-semibold">Synilogic HR App helps you optimize and improve your
                employee's efficiency</span>
        </div>
        <div class="feature mx-auto" style="width: 90%;">
            <div class="row mt-5">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex flex-column">
                        <div class="featureimg">

                            <p class="fw-bold fs-4 text-light col-5 mt-5 px-5  mx-5">Contactless
                                Attendance</p>
                            <p class="fs-6 text-light  col-11 mx-4  px-5 text-justify">Ensure employe's safety during
                                COVID-19 with
                                contactless attendance.</p>

                            <button
                                class="border-0 text-light bg-transparent mx-5 fs-6 fw-semibold d-flex align-items-center">Read
                                more <i class="fa-solid fa-arrow-right mx-1"></i></button>
                        </div>

                        <div class="featureimg mt-5">

                            <p class="fw-bold fs-4 text-light  mt-5 pt-3 mx-5 px-5">Geo Fencing</p>
                            <p class="fs-6 text-light col-9 px-4 mx-5 mt-4">Authorize your employees to punch within an
                                assigned
                                fence.</p>

                            <button
                                class="border-0 text-light bg-transparent mx-5 fs-6 fw-semibold d-flex align-items-center px-4">Read
                                more <i class="fa-solid fa-arrow-right mx-1"></i></button>
                        </div>
                    </div>
                </div>

                <div class="d-lg-block d-sm-none col-lg-4">
                    <img src="{{ asset('assets/front/img/1 4.png') }}" alt="" srcset="">

                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="d-flex flex-column">
                        <div class="featureimg">

                            <p class="fw-bold fs-4 text-light col-8 mt-5 px-5 mx-5  text-center">Face Recognization</p>
                            <p class="text-light col-10 px-4 mx-5">Capture employee attendance in a hygienic way with A.I.
                                powered
                                face recognition .</p>

                            <button
                                class="border-0 text-light bg-transparent mx-5 fs-6 fw-semibold d-flex align-items-center">Read
                                more <i class="fa-solid fa-arrow-right mx-1"></i></button>
                        </div>

                        <div class="featureimg mt-5">

                            <p class="fw-bold fs-4 text-light  col-8 mt-5 pt-3 px-5 mx-5  text-center">Chatbot</p>
                            <p class="fs-6 text-light col-11 mx-4 pt-3 mt-2 px-5 text-justify">Let employees resolve their
                                queries
                                through a mobile-driven chatbot option.</p>

                            <button
                                class="border-0 text-light bg-transparent mx-5 fs-6 fw-semibold d-flex align-items-center px-4">Read
                                more <i class="fa-solid fa-arrow-right mx-1"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--optimize-->
    <div class="optimize my-5 pt-5">
        <div class="row text-center">
            <div class="col-3 d-none d-lg-block">
                <img src="{{ asset('assets/front/img/LAMP 2.png') }}" class="mb-0 lampimg" alt=""
                    srcset="">
            </div>

            <div class="col-lg-6  col-xl-6 col-sm-12 mt-5">
                <div class="text-center">
                    <p class="fs-2 fw-bold mb-1 themeclr">Fully Optimized</p>
                    <span class="text-black fw-600 subhead fw-semibold">Facto HR App helps you optimize and improve your
                        employee's efficiency</span>
                    <img src="{{ asset('assets/front/img/device.png') }}" class="deviceimg text-start mt-5"
                        alt="">
                </div>
            </div>

            <div class="col-3  d-none d-lg-block">
                <img src="{{ asset('assets/front/img/LAMP 1.png') }}" class="mb-0 lampimg" alt=""
                    srcset="">

            </div>
        </div>
    </div>

    <!--our achievments-->
    <div class="ourservice my-5">
        <div class="text-center">
            <p class="fs-2 fw-bold mb-1 themeclr">OUR Achievements </p>
            <span class="text-black fw-600 subhead fw-semibold">We are the best service provider to our clients</span>
        </div>

        <!---->

        <div class="container services mx-auto">
            <div class="row">
                <div class="col mt-5">

                    <div class="achievecard py-4 text-center">
                        <i class="fa-solid fa-paper-plane themeclr mx-2 fs-2"></i>
                        <p class="fs-4 fw-bold mb-0 mt-2">12M+</p>
                        <p class="fs-5 mt-1">Downloads</p>

                    </div>
                </div>
                <!---->

                <div class="col mt-5">
                    <div class="achievecard py-4 text-center">
                        <i class="fa-regular fa-user themeclr mx-2 fs-2"></i>
                        <p class="fs-4 fw-bold mb-0 mt-2">234M+</p>
                        <p class="fs-5 mt-1">Followers

                        </p>
                    </div>
                </div>

                <div class="col mt-5">
                    <div class="achievecard py-4 text-center">
                        <i class="fa-solid fa-file-lines themeclr mx-2 fs-2"></i>
                        <p class="fs-4 fw-bold mb-0 mt-2">373M+</p>
                        <p class="fs-5 mt-1">Review

                        </p>
                    </div>
                </div>

                <div class="col mt-5">
                    <div class="achievecard py-4 text-center">
                        <i class="fa-solid fa-globe themeclr mx-2 fs-2"></i>
                        <p class="fs-4 fw-bold mb-0 mt-2">256M+</p>
                        <p class="fs-5 mt-1">Countries

                        </p>
                    </div>
                </div>


            </div>

        </div>
    </div>

    <!--our achievments-->
    <div class="ourservice my-5" id="pricing">
        <div class="text-center">
            <p class="fs-2 fw-bold mb-1 themeclr">OUR Pricing</p>
            <span class="text-black fw-600 subhead fw-semibold">Find the Right Plan That Suits Your Business</span>
        </div>

        <div class="container">
            <div class="d-lg-flex mx-auto  justify-content-evenly d-md-block">
                @foreach ($packages as $key => $value)
                <a href="{{ route('packagedetail', Crypt::encrypt($value->package_uni_id)) }}">
                    <div class="pricecard text-center mx-auto mt-5">
                            <div class="featurepriceimg mx-auto">
                                <div class="ribbon ribbon-top-left"><span>{{ $value->label }}</span></div>
                                <p class="fw-bold fs-1 text-light mt-4 pt-4 mb-0">₹{{ $value->price }}</p>
                                <p class="fs-6 text-light mx-2 mt-4">{{ $value->name }}</p>
                            </div>
                            {!! $value->description !!}
                            <button class="border border-2 text-light fw-bold bgtheme py-2 mb-2 px-5 rounded-5">View More</button>
                        </div>
                    </a>
                @endforeach

            </div>
        </div>
    </div>

    <script>
        $(document).on('click', '.login-box', function() {
            let _token = $('meta[name="csrf-token"]').attr('content');
            var username = $('.username').val();
            var password = $('.pstr').val();
            $.ajax({
                url: "{{ route('loginstore') }}",
                type: 'post',
                data: {
                    _token: _token,
                    username: username,
                    password: password,
                },
                success: function(result) {
                    if (result.status == 1) {
                        toastr.success(result.msg)
                        if (result.process_status == 5) {
                            location.href = "{{ route('admindashboard') }}";
                        } else {
                            location.href = "{{ route('setting') }}";
                        }
                    } else {
                        toastr.error(result.msg)
                    }
                }
            });

        })
    </script>
@endsection
