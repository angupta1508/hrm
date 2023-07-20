@extends('layouts.front-user.app')

@section('content')


<!-- main screen -->
<main class="maindashatten">
    <section class="d-none d-sm-block">
        <div class="search  mx-auto d-flex mb-0 align-items-center">
            <!-- <span class="fa fa-search"></span> -->
            <input placeholder="Type your Keywords to search..." class="rounded-pill px-4 py-4 mb-0">

            <div class="d-flex border border-2 bgtheme ms-auto  settingicon fs-2 text-light">
                Approvel

            </div>

        </div>
    </section>
    <p class="fw-semibold fs-1 text-center d-block d-sm-none">Approvel</p>

    <div class="bgtheme mt-1 pb-3">
      
        <div class="d-flex pt-5 pb-3 px-1">
            <div class="col-md-4 rounded-5 bg-secondary">
                <span class="py-2 rounded-5 col-lg-3 col-xl-3 col-md-4 col-sm-4 col-5 ms-3 fw-semibold text-center subhead">
                        <h3 class="Leavetext  py-3 rounded-5">Leave</h3>
                </span>
            </div>
            <div class="col-md-4">
            </div>
            <div class="col-md-4 rounded-5 bg-light">
                <span class="py-2 rounded-5 col-lg-3 col-xl-3 col-md-4 col-sm-4 col-5 ms-auto  me-3 fw-semibold text-center subhead">
                <a href="{{ route('approveAttendanceList') }}" class="text-decoration-none">   <h3 class="Leavetext  py-3 rounded-5">Attendance</h3></a>
                </span>
            </div>
        </div>

        <div class="col-sm-12  my-3">
            <div class="accordion" id="accordionExamplewallet">
                <div class="accordion-item border-0">
                    <h2 class="accordion-header" id="headingOnewallet">
                        <button class="accordion-button text-black bgthemelight rounded-4 border fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOnewallet" aria-expanded="true" aria-controls="collapseOnewallet">
                            <i class="fa-solid fa-filter mx-1 themeclr"></i><b class="themeclr fw-semibold"> Filter</b>
                        </button>
                    </h2>
                    <div id="collapseOnewallet" class="accordion-collapse collapse @if (!empty($filter)) show @endif" aria-labelledby="headingOnewallet" data-bs-parent="#accordionExamplewallet">
                        <div class="accordion-body">

                            <form action="{{ route('approveLeaveList') }}" method="GET">
                                @csrf
                                <div class="row border border-2 rounded-2 p-2 align-items-center">
                                    <div class="p-2 flex-fill w-100">
                                        <input type="text" id="search" name="search" class="form-control" value="{{ !empty($filter['search']) ? $filter['search'] : '' }}" autocomplete="off" placeholder="{{ __('Search By Name') }}">
                                    </div>
                                    <div class="col-lg-3 col-xl-6 col-md-6 col-sm-6 col-12 mt-2">
                                        {{ Form::select('status', ['' => __('Select Status')] + config('constants.default_leave_status'), isset($filter['status']) ? $filter['status'] : '', ['class' => 'w-100  p-3 rounded-5 bgthemelight border-0 fw-semibold']) }}
                                    </div>
                                    <div class="col-lg-3 col-xl-6 col-md-6 col-sm-6 col-12 mt-2">
                                        <button type="submit" class="btn w-100 p-3 rounded-5 bgtheme mb-0 button text-light mx-auto approvebtn " name="submit">
                                            Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="mx-3">
            @foreach($leaveApprovel as $leave)
            <div class="card pb-2 pt-3 px-3 d-flex flex-row mt-3">

                <img src="{{ ImageShow(config('constants.user_image_path'), $leave->profile_image, 'small') }}" class="pyslipempimg" alt="" srcset="">
                <div class="d-flex flex-column mx-3">
                    <div class="fw-bold  text-dark"><strong class="text-dark">User Name :-</strong>&nbsp;
                        <span class="fw-bold  text-black">{{ $leave->name }}</span>
                    </div>
                    <div class="fw-bold text-dark"><strong class="text-dark"> From Date :-</strong>&nbsp;
                        <span class="fw-bold  text-black">{{ $leave->request_start_date }}</span>
                    </div>

                    <div class="fw-bold  text-dark"><strong class="text-dark"> To Date :-</strong>&nbsp;
                        <span class="fw-bold text-black">{{ $leave->request_end_date }}</span>
                    </div>

                    <div class="fw-bold  text-dark"><strong class="text-dark"> Request Remark :-</strong>&nbsp;
                        <span class="fw-bold  text-black">{{ $leave->request_remark }}</span>
                    </div>

                </div>

                <div class="d-flex flex-column ms-auto">
                    <p class="mb-0 text-secondary  ms-auto">Status</p>
                    <div class="d-flex align-items-center mt-2">
                        <p class="text-xs font-weight-bold mb-0">
                            @if($leave->status == 0)
                            <span class="updateStatus badge bg-info text-white">Pending</span>
                            @elseif($leave->status == 1)
                            <span class="badge badge-pill  bg-success">Approve</span>
                            @elseif($leave->status == 2)
                            <span class="badge badge-pill bg-danger">Cancel</span>
                            @endif
                        </p>
                    </div>
                    <div class="d-flex align-items-center mt-2">
                        <p class="text-xs font-weight-bold mb-0">
                            <a class="btn btn-info btn-sm text-white" title="Approve Leave" data-toggle="tooltip" href="{{ route('employe-leave.show', $leave->id) }}"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>

                        </p>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
</main>

@section('content')