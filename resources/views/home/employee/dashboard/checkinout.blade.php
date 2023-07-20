@extends('layouts.front-user.app')
@section('content')



<!-- main screen -->
<main class="maindashatten">
    <section class="d-none d-sm-block">
        <div class="search  mx-auto d-flex mb-0 align-items-center ">
            <!-- <span class="fa fa-search"></span> -->
            <input placeholder="Type your Keywords to search..." class="rounded-pill px-4 py-4  mb-0">

            <div class="d-flex border border-2 bgtheme ms-auto  settingicon fs-2 text-light">
                Attendance

            </div>
        </div>
    </section>
    <p class="fw-semibold fs-1 text-center d-block d-sm-none">Attendence</p>
    <div class="row bgtheme p-3">
        <div class="col-12 col-lg-4">
            <div class="card p-3 mx-auto my-3 rounded-4">
                <div class="pb-2 pt-3 px-3 d-flex flex-column mt-1">

                    <img src="{{$authdetail->profile_image}}" class="empimg mx-auto" alt="" srcset="">
                    <div class="d-flex flex-column text-center mt-3">
                        <span class="fw-bold fs-3 text-black">{{$authdetail->name}}</span>
                        <p class="mb-0 text-danger fw-semibold">{{$authdetail->designation_name}}</p>
                        <p class="text-secondary fw-semibold mt-1">ID : {{$authdetail->employee_code}}</p>
                    </div>
                </div>

            </div>
            <div class="row">

                <div class="col-6">
                    <button class="border-0 rounded-4 bg-light themeclr subhead fw-semibold w-100 py-2 subhead punch" data-type='in'>Check-in <br>
                        <span class="text-secondary fw-600 fs-6">Press for check-in &nbsp;</span>
                        {{ !empty($attendence) ? prettyDateFormet($attendence->from_time, 'time') : '' }}
                        <span id="from_time" class="mx-3 fw-600 fs-6"></span></button>

                </div>

                <div class="col-6">
                    <button class="border-0 rounded-4 bg-light themeclr subhead fw-semibold w-100 py-2 subhead punch" data-type='out'>Check-out<br>
                        <span class="text-secondary fw-600 fs-6">Press for check-out</span>
                        {{ !empty($attendence) ? prettyDateFormet($attendence->to_time, 'time') : '' }}
                        <span id="to_time" class="mx-3 fw-600 fs-6"></span></button>
                </div>

            </div>
        </div>
        <div class="col-12 col-lg-8 p-3 mx-auto">
            <div class="maploction">

                <iframe width="100%" height="495" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.co.uk/maps?f=q&source=s_q&hl=en&geocode=&815&sspn=8.047465,
                13.666992&ie=UTF8&hq=&hnear=15+Springfield+Way,+Hythe+CT21+5SH,
                +United+Kingdom&t=m&z=14&ll=25.215620,75.866737&output=embed" class="rounded-4"></iframe>
            </div>

        </div>
    </div>
</main>


<script>
    $(document).ready(function() {
        $('.punch').on('click', function() {
            let _token = $('meta[name="csrf-token"]').attr('content');
            var admin_id = "{{$authdetail->admin_id}}";
            var user_id = "{{$authdetail->user_id}}";
            var punch_type = 'Web';
            var from_where = 'Web';
            var punchInOut = $(this).data('type');
            $.ajax({
                url: "{{ route('attendancePunch') }}",
                type: 'post',
                data: {
                    _token: _token,
                    admin_id: admin_id,
                    user_id: user_id,
                    punch_type: punch_type,
                    from_where: from_where,
                    punchInOut: punchInOut,
                },
                success: function(result) {
                    if (result.status == 1) {
                        toastr.success(result.msg)
                    } else {
                        toastr.error(result.msg)
                    }
                }
            });

        })
    })
</script>

<script>
    $(document).ready(function() {
        $('.punch').on('click', function() {
            let _token = $('meta[name="csrf-token"]').attr('content');
            var punchInOut = $(this).data('type');
            var admin_id = "{{$authdetail->admin_id}}";
            var user_id = "{{$authdetail->user_id}}";
            var shift_id = "{{$authdetail->shift_id}}";
            var d = "{{date('Y-m-d')}}";

            $.ajax({
                url: "{{ route('attendanceTime') }}",
                type: 'post',
                data: {
                    _token: _token,
                    punchInOut: punchInOut,
                    admin_id: admin_id,
                    shift_id: shift_id,
                    user_id: user_id,
                    attendance_date: d,

                },
                dataType: 'json',
                success: function(result) {
                    if (result.type == 'from') {
                        var fromTime = extractTime(result.from_time);
                        $('#from_time').text(fromTime);
                    } else if (result.type == 'to') {
                        var toTime = extractTime(result.to_time);
                        $('#to_time').text(toTime);
                    }
                    if (result.status == 1) {
                        toastr.success(result.msg)
                    } else {
                        toastr.error(result.msg)
                    }
                }
            });

        })
    })

    function extractTime(datetimeString) {
        var timeString = datetimeString.split(' ')[1];
        var timeParts = timeString.split(':');
        var hours = parseInt(timeParts[0], 10);
        var period = (hours >= 12) ? 'PM' : 'AM';

        // Convert to 12-hour format
        if (hours > 12) {
            hours -= 12;
        } else if (hours === 0) {
            hours = 12;
        }

        var formattedTime = hours.toString().padStart(2, '0') + ':' + timeParts[1] + ':' + timeParts[2] + ' ' + period;
        return formattedTime;
    }
</script>



@endsection