@extends('layouts.front-user.app')
@section('content')

<main class="maindashatten">
    <section class="d-none d-sm-block">
        <div class="search  mx-auto d-flex mb-0 align-items-center">
            <!-- <span class="fa fa-search"></span> -->
            <input placeholder="Type your Keywords to search..." class="rounded-pill px-4 py-4 mb-0">

            <div class="d-flex border border-2 bgtheme ms-auto  settingicon fs-2 text-light">
                Profile Details

            </div>

        </div>
    </section>
    <p class="fw-semibold fs-1 text-center d-block d-sm-none"> Profile Details</p>

    <div class=" mt-1 pb-5">



        <section class="bg-light pt-3 pb-2">
            <div class="mx-3">
                <div class="maincontent card p-4">
                    <div class="row align-items-center">
                        <div class="col-xl-3 col-lg-12 col-sm-12 ">
                            <!---->
                            <div class="astrloger_profile_picture ">
                                <div class="empimg mx-auto">
                                    <img src="{{ $users->profile_image  }}" alt="Generic rounded-circle" class="img-fluid rounded-circle border  border-3 profile_img empimg" />
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-12 col-md-12 my-3 ">
                            <!---->
                            <div class="row mx-auto">
                                <div class="col-xl-6 col-lg-7 col-sm-5  col-12  ">
                                    <h3 class="astroname me-auto">
                                        {{ $users->name }}
                                        <span class="verifyicon"><img alt="verified" loading="lazy" src="https://d1gcna0o0ldu5v.cloudfront.net/fit-in/20x20/assets/images/astrologer_profile/verified.webp" /><!----></span>

                                    </h3>
                                </div>
                                <div class="col-xl-6  col-lg-5 col-sm-7 col-12 text-center ">
                                    <span class="fs-6 border border-0 border-successs text-light fw-bold rounded-5 py-2 px-3 bgtheme">
                                        Current Employee
                                    </span>
                                </div>
                            </div>
                            <div class="row  mx-auto">
                                <div class="col-xl-4 col-lg-7 col-sm-7 col-12 subhead mx-auto mt-2">
                                    <i class="fa-solid fa-computer subhead me-2"></i>{{ $users->designation }}
                                </div>
                                <!---->
                                <div class="col-xl-8 col-lg-5 col-sm-5 col-6 subhead mt-2">
                                    <i class="fa-solid fa-phone  subhead mx-2"></i>{{ $users->mobile }}

                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-xl-4 col-lg-7 col-sm-7 col-6 subhead  mt-2">
                                    <i class="fa-solid fa-at   mx-2"></i>{{ $users->email }}
                                </div>
                                <div class="col-xl-8 col-lg-5 col-sm-5 col-6 subhead  mt-2">
                                </div>
                            </div>

                        </div>
                    </div>

                    <!---->
                </div>
            </div>
        </section>

        <!--Profile.html tabbing-->
        <div class="card mx-3 my-4 mt-3">
            <div class="d-flex text-center justify-content-around py-2 text-secondary">
                <div class="profiledata fs-5 tablinks" id="defaultOpen" onclick="openClass(event, 'Personaldata')">
                    Personal Data
                </div>
                <div class="profiledata fs-5 tablinks" onclick="openClass(event, 'Workdetails')">
                    Work Profile
                </div>
                <div class="profiledata fs-5 tablinks" onclick="openClass(event, 'Education')">
                    Education
                </div>
                <div class="profiledata fs-5 tablinks" onclick="openClass(event, 'Document')">
                    Document
                </div>
            </div>
        </div>

        <!--personaldata tab-->
        <div id="Personaldata" class="tabcontent">
            <div class="col-12 col-sm-12 col-md-12">
                <div class="card">
                    <div class="card-header bgtheme">
                        <h6 class="subhead text-light">Personal Data</h6>

                    </div>
                    <div class="row p-2">
                        <div class="row">
                            <div class="col-3 fs-5">Name :</div>
                            <div class="col-9 fs-5">{{ $users->name }}</div>
                        </div>
                        <div class="row">
                            <div class="col-3 fs-5">Contact :</div>
                            <div class="col-9 fs-5">{{ $users->mobile }}</div>
                        </div>
                        <div class="row">
                            <div class="col-3 fs-5">Address :</div>
                            <div class="col-9 fs-5">{{ $users->address }}</div>
                        </div>
                        <div class="row">
                            <div class="col-3 fs-5">PAN :</div>
                            <div class="col-9 fs-5">{{ $users->pan_no }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Workdetails tab-->
        <div id="Workdetails" class="tabcontent">
            <div class="col-12 col-sm-12 col-md-12">
                <div class="card">
                    <div class="card-header bgtheme">
                        <h6 class="subhead text-light">Work Details</h6>

                    </div>
                    <div class="row p-2">

                        <div class="row">
                            <div class="col-3 fs-5">Date Of Joining :</div>
                            <div class="col-9 fs-5">{{ $users->joined_date }}</div>
                        </div>
                        <div class="row">
                            <div class="col-3 fs-5">Employment Stage :</div>
                            <div class="col-9 fs-5">{{ $users->designation }}</div>
                        </div>
                        <div class="row">
                            <div class="col-3 fs-5">Probation End Date:</div>
                            <div class="col-9 fs-5">{{ $users->termination_date }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Education tab-->
        <div id="Education" class="tabcontent">
            <div class="col-12 col-sm-12 col-md-12">
                <div class="card ">
                    <div class="card-header bgtheme">
                        <h6 class="subhead text-light">Education Details</h6>
                    </div>
                    <div class="row p-2">
                        <div class="row">
                            <div class="col-3 fs-5">Education Qualification :</div>
                            <div class="col-9 fs-5">{{ $users->education_qualification }}</div>
                        </div>
                        <div class="row">
                            <div class="col-3 fs-5">Technical Qualification :</div>
                            <div class="col-9 fs-5">{{ $users->technical_qualification }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Document tab-->
        <div id="Document" class="tabcontent">
            <div class="col-12 col-sm-12 col-md-12">
                <div class="card">
                    <div class="card-header bgtheme">
                        <h6 class="subhead text-light">Documents</h6>
                    </div>
                    <div class="row p-2">

                        <div class="row">
                            <div class="col-3 fs-5">Aadhaar Card:</div>
                            <div class="col-9 fs-5">{{ $users->aadhaar_no }}</div>
                        </div>
                        <div class="row">
                            <div class="col-3 fs-5">Pan Card :</div>
                            <div class="col-9 fs-5">{{ $users->pan_no }}</div>
                        </div>
                        <div class="row">
                            <div class="col-3 fs-5">Driving License :</div>
                            <div class="col-9 fs-5">{{ $users->driving_license_no }}</div>
                        </div>
                        <div class="row">
                            <div class="col-3 fs-5">Passport :</div>
                            <div class="col-9 fs-5">{{ $users->passport_no }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

@endsection('content')