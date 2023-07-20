@extends('layouts.front-admin.app')

@section('content')
    <div class="col-lg-8 col-xl-8 col-md-8 col-sm-7 col-11 card border py-4 mx-auto rounded-5 mt-3">
        <div class="row align-items-center ">
            <div class="col-lg-3 col-xl-3 col-md-6 col-sm-6 col-10 mx-auto">
                <p class="fs-5 fw-semibold text-black">Package Details</p>
            </div>
            <div class="col-lg-3 col-xl-3 col-md-6 col-sm-6 col-10 mx-auto">
                <p class="py-2 mx-auto text-center rounded-pill fs-5 border-0 text-light bgtheme fw-semibold">{{!empty($authdetail['package_name']) ? $authdetail['package_name'] : '' }}</p>
            </div>
            <div class="col-lg-3 col-xl-3 col-md-6 col-sm-6 col-10 mx-auto">
                <a href="{{route('package')}}"><p class="py-2 fs-5  text-center rounded-pill fs-5 border-0 text-light bgtheme fw-semibold">Upgrade</p></a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-xl-3 col-md-6 col-sm-6 col-10 mx-auto mt-2 ">
                <div class="rounded-4 py-4 text-center bgthemelight">

                    <a href="./profile.html">
                        <div class="card__border">
                            <img src="{{ asset('assets/front/img/Mask1.png') }}" alt="" class="" />
                        </div>
                    </a>
                    <h3 class="empcard__name themeclr mb-1 mt-2 subhead fw-semibold">Package Date</h3>
                    <span class="card__profession text-dark fw-semibold fs-subhead">{{!empty($authdetail['recharge_date']) ? prettyDateFormet($authdetail['recharge_date'],'date') : prettyDateFormet($authdetail['created_at'],'date') }}</span>
                </div>
            </div>

            <div class="col-lg-3 col-xl-3 col-md-6 col-sm-6 col-10 mx-auto  mt-2">
                <div class="rounded-4 py-4 text-center bgthemelight">

                    <div class="card__border">
                        <img src="{{ asset('assets/front/img/Mask2.png') }}" alt="" class="" />
                    </div>
                    <h3 class="empcard__name themeclr mb-1 mt-2 subhead fw-semibold">Valid Till</h3>
                    <span class="card__profession text-dark fw-semibold subhead">{{prettyDateFormet($authdetail['package_valid_date'],'date') }}</span>
                </div>
            </div>

            <div class="col-lg-3 col-xl-3 col-md-6 col-sm-6 col-10 mx-auto  mt-2">
                <div class="rounded-4 py-4 text-center bgthemelight">
                    <div class="card__border">
                        <img src="{{ asset('assets/front/img/Mask3.png') }}" alt="" class="" />
                    </div>

                    <h3 class="empcard__name themeclr mb-1 mt-2 subhead fw-semibold">Payment Type</h3>
                    <span class="card__profession text-dark fw-semibold subhead">Phone pay</span>
                </div>
            </div>

            <div class="col-lg-3 col-xl-3 col-md-6 col-sm-6 col-10 mx-auto  mt-2">
                <div class="rounded-4 py-4 text-center bgthemelight">
                    <div class="card__border">
                        <img src="{{ asset('assets/front/img/Mask4.png') }}" alt="" class="" />
                    </div>  

                    <h3 class="empcard__name themeclr mb-1 mt-2 subhead fw-semibold">Package Type</h3>
                    <span class="card__profession text-dark fw-semibold subhead">{{$authdetail['package_label']}}</span>
                </div>
            </div>

        </div>


    </div>
@endsection
