@extends('layouts.front-user.app')
@section('content')



<!-- main screen -->
<main class="maindashatten">
    <section class="">
        <div class="search  mx-auto d-flex mb-0 align-items-center">
            <!-- <span class="fa fa-search"></span> -->
            <input placeholder="" class="d-none d-sm-block rounded-pill px-4 py-4  mb-0">

            <div class="d-flex border border-2 d-none d-sm-block bgtheme ms-auto  settingicon fs-2 text-light">
                Attendance List

            </div>

        </div>
    </section>

    <!--table start from here-->
    <div class="row">
        <div class="col-lg-12 mb-2 ">
            <div class="table-responsive text-center">
                <table class="table table-striped custom-table mb-2 my-2">
                    <thead class="bgtheme text-light fw-600 subhead">
                        <tr>
                            <td>S.No</td>
                            <td>Attendence Date</td>
                            <td>Punch In Time</td>
                            <td>Punch Out Time</td>
                            <td>Status</td>

                        </tr>
                    </thead>
                    <tbody class="text-dark fw-600 subhead fst-normal">
                        @foreach ($attendance as $leav)
                        <tr class="text-dark">
                            <td>{{ ++$i }}</td>
                            <td>{{ $leav->attendance_date  }}</td>
                            <td>{{ $leav->from_time  }}</td>
                            <td>{{ $leav->to_time  }}</td>

                            <td class="text-center">
                                <p class="text-xs font-weight-bold mb-0">
                                    @if ($leav->attendance_status == 'A')
                                    <span class="updateStatus badge bg-danger text-white">Absent</span>
                                    @elseif($leav->attendance_status == 'P')
                                    <span class="badge badge-pill  bg-success">Present</span>
                                    @elseif($leav->attendance_status == 'MP')
                                    <span class="badge badge-pill bg-primary">Miss Punch</span>
                                    @elseif($leav->attendance_status == 'HD')
                                    <span class="badge badge-pill bg-warning">Half Day</span>
                                    @elseif($leav->attendance_status == 'AL')
                                    <span class="badge badge-pill bg-info">Approved Leave</span>
                                    @elseif($leav->attendance_status == 'UL')
                                    <span class="badge badge-pill bg-secondary">Un Approved Leave</span>
                                    @elseif($leav->attendance_status == 'WO')
                                    <span class="badge badge-pill bg-info">Week Off</span>
                                    @endif
                                </p>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3 px-3">
                {{ $attendance->appends($filter)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</main>


@endsection