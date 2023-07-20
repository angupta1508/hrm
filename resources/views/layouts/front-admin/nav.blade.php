@php
    $userData = Auth::guard('front-admin')->user();
@endphp
<section class="packdetail">
    <div class="search  mx-auto d-flex mb-0 bgtheme justify-content-around py-4 px-4">
        <!-- <span class="fa fa-search"></span> -->
        {{-- <input placeholder="Type your Keywords to search..." class="rounded-pill px-4 w-50 py-4 mt-2 mb-0 bg-light"> --}}

        <div class="d-flex border border-0 ms-auto">
            <form action="{{ route('gotoadminpanel') }}" method="post" target="_blank">
                @csrf
                <button class="py-3 rounded-pill subhead border-0 me-3 themeclr bg-light fw-semibold px-5">Go to Admin
                    Panel</button>
            </form>
            <a href="{{ route('employeeLogin') }}" target="_blank">
                <button class="py-3 rounded-pill subhead border-0 themeclr bg-light fw-semibold px-5 me-3">Go to
                    Employee
                    Panel</button>
            </a>

        </div>

    </div>

    <div class="row gap-5 mb-5 mt-3">
        <div class="col-lg-3 me-auto packcard border  py-5 pe-5">
            <a href="{{ route('admindashboard') }}">
                <div class="d-flex align-items-center bgtheme packround py-1 justify-content-evenly">
                    <i class="fa-solid fa-cube fs-2 text-light"></i>
                    <p class="text-light fs-5 mt-3">Dashboard</p>
                </div>
            </a>
            @if ($userData->process_status != 5)
                <a href="{{ route('setting') }}">
                    <div class="d-flex align-items-center bgtheme packround py-1 justify-content-evenly mt-4 ">
                        <i class="fa-solid fa-gear fs-2 text-light ms-2"></i>
                        <p class="text-light fs-5 mt-3"> Basic Settings</p>
                    </div>
                </a>
            @endif
            <a href="{{ route('rechargehistory') }}">
                <div class="d-flex align-items-center bgtheme packround py-1 justify-content-evenly mt-4 ">
                    <i class="fa-solid fa-gear fs-2 text-light ms-2"></i>
                    <p class="text-light fs-5 mt-3"> Recharge History</p>
                </div>
            </a>
        </div>
