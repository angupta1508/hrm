@extends('layouts.front-user.app')
@section('content')
    <main class="maindashatten">
        <section class="d-none d-sm-block">
            <div class="search  mx-auto d-flex mb-0 align-items-center">
                <!-- <span class="fa fa-search"></span> -->
                <input placeholder="Type your Keywords to search..." class="rounded-pill px-4 py-4 mb-0">
                <div class="d-flex border border-2 bgtheme ms-auto  settingicon fs-2 fw-semibold text-light">
                    Calendar
                </div>
            </div>
        </section>

        <div class="notice">
            <p class="fs-2 text-blacl fw-semibold">Calendar</p>
            <div class="row">
                <div class="row">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="">
                                <div class="calendar  me-3"></div>
                            </div>
                        </div>

                        <div class="row  mb-3">
                            <div class="card mx-2 dateTitle">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <div class="boxgreen mb-3" style="width:23px; height:23px"></div>
                                                <p class="text-dark fs-6 fw-300  mx-2">Present</p>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-4">
                                            <div class="d-flex align-items-center">
                                                <div class="boxred mb-3" style="width:23px; height:23px"></div>
                                                <p class="text-dark fs-6 fw-300 mx-2">Absent</p>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-4">
                                            <div class="d-flex align-items-center">
                                                <div class="boxblue mb-3" style="width:23px; height:23px"></div>
                                                <p class="text-dark fs-6 fw-300  mx-2">Partial</p>
                                            </div>

                                        </div>

                                        <div class="col-md-3 col-sm-4">
                                            <div class="d-flex align-items-center">
                                                <div class="selectedday mb-3" style="width:23px; height:23px"></div>
                                                <p class="text-dark fs-6 fw-300 mx-2">Selected Day</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 col-sm-4">
                                            <div class="d-flex align-items-center">
                                                <div class="boxholiday mb-3" style="width:23px; height:23px"></div>
                                                <p class="text-dark fs-6 fw-300 mx-2">Holiday</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-4">
                                            <div class="d-flex align-items-center">
                                                <div class="boxweekoff mb-3" style="width:23px; height:23px"></div>
                                                <p class="text-dark fs-6 fw-300  mx-2">Week Off</p>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-4">
                                            <div class="d-flex align-items-center">
                                                <div class="boxleave mb-3" style="width:23px; height:23px"></div>
                                                <p class="text-dark fs-6 fw-300  mx-2">Leave</p>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-sm-4">
                                            <div class="d-flex align-items-center">
                                                <div class="boxhalfday mb-3" style="width:23px; height:23px"></div>
                                                <p class="text-dark fs-6 fw-300  mx-2">Half Day</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card calender-detail">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="attendance_status"><strong class="text-dark"
                                                style="font-size:13px">Attendance Status:-</strong>&nbsp;
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <span id="attendance_status" class="mx-3"></span><br><br>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="attendance_status"><strong class="text-dark"
                                                style="font-size:14px">Description:-</strong>&nbsp;
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <span id="description" class="mx-3"></span><br><br>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="from_time"><strong class="text-dark" style="font-size:14px">From
                                                Time:-</strong>&nbsp;
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <span id="from_time" class="mx-3"></span><br><br>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="to_time"><strong class="text-dark" style="font-size:14px">To
                                                Time:-</strong>&nbsp;
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <span id="to_time" class="mx-3"></span><br><br>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="working_hours"><strong class="text-dark"
                                                style="font-size:14px">Working Hours:-</strong>&nbsp;
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <span id="working_hours" class="mx-3"></span><br><br>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="late_in"><strong class="text-dark" style="font-size:14px">Late
                                                In:-</strong>&nbsp;
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <span id="late_in" class="mx-3"></span><br><br>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="late_out"><strong class="text-dark" style="font-size:14px">Late
                                                Out:-</strong>&nbsp;
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <span id="late_out" class="mx-3"></span><br><br>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="early_in"><strong class="text-dark" style="font-size:14px">Early
                                                In:-</strong>&nbsp;
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <span id="early_in" class="mx-3"></span><br><br>
                                    </div>
                                </div>
                                {{-- <p id="userid"></p> --}}
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="early_out"><strong class="text-dark" style="font-size:14px">Early
                                                Out:-</strong>&nbsp;
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <span id="early_out" class="mx-3"></span><br><br>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script>
        $(document).ready(function() {
            $('.punch').on('click', function() {
                let _token = $('meta[name="csrf-token"]').attr('content');
                var admin_id = "{{ $authdetail->admin_id }}";
                var user_id = "{{ $authdetail->user_id }}";
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
        function getTodayAttendanceData(attendance_date) {
            let _token = $('meta[name="csrf-token"]').attr('content');
            var admin_id = "{{ $authdetail->admin_id }}";
            var user_id = "{{ $authdetail->user_id }}";
            var shift_id = "{{ $authdetail->shift_id }}";

            $.ajax({
                url: "{{ route('getUserPresentDetail') }}",
                type: 'post',
                data: {
                    _token: _token,
                    admin_id: admin_id,
                    user_id: user_id,
                    user_id: user_id,
                    date: attendance_date,
                    shift_id: shift_id,

                },
                dataType: 'json',

                success: function(result) {

                    // $('#userid').text(result.data.user_id);
                    $('#attendance_status').text(result.data.attendance_status);
                    $('#description').text(result.data.description);
                    $('#from_time').text(result.data.from_time);
                    $('#to_time').text(result.data.to_time);
                    $('#working_hours').text(result.data.working_hours);
                    $('#late_in').text(result.data.late_in);
                    $('#late_out').text(result.data.late_out);
                    $('#working_hours').text(result.data.working_hours);
                    $('#early_in').text(result.data.early_in);
                    $('#early_out').text(result.data.early_out);

                    if (result.status == 1) {
                        toastr.success(result.msg)
                    } else {
                        toastr.error(result.msg)
                    }
                }
            })
        }
    </script>
    <script>
        function getCalenderData(mon, year) {
            let _token = $('meta[name="csrf-token"]').attr('content');
            var admin_id = "{{ $authdetail->admin_id }}";
            var user_id = "{{ $authdetail->user_id }}";
            var date = new Date();
            var currentyear = date.getFullYear();
            var currentmonth = date.getMonth();
            var currentmonth = currentmonth + 1;
            if (currentmonth < 10) {
                var period = '0' + currentmonth;
            }
            var mon = mon + 1;
            if (mon < 10) {
                var mon = '0' + mon;
            }
            var month = year + '-' + mon;
            var currentPeriod = currentyear + '-' + period;
            if (currentPeriod >= month) {
                setTimeout(function() {
                    $.ajax({
                        url: "{{ route('getCalenderData') }}",
                        type: 'post',
                        data: {
                            _token: _token,
                            month: month,
                            admin_id: admin_id,
                            user_id: user_id,
                        },
                        dataType: 'json',

                        success: function(result) {
                            $.each(result.data, function(key, value) {
                                var attend_status = value.attendance_status;
                                var d = new Date(value.attendance_date);
                                let day = d.getDate();
                                var a = $('.number-item[data-num="' + day + '"]');
                                if (attend_status == 'P') {
                                    a.addClass('boxgreen')
                                } else if (attend_status == 'A') {
                                    a.addClass('boxred')
                                } else if (attend_status == 'MP') {
                                    a.addClass('boxblue')
                                } else if (attend_status == 'HO') {
                                    a.addClass('boxholiday')
                                } else if (attend_status == 'WO') {
                                    a.addClass('boxweekoff')
                                } else if (attend_status == 'HD') {
                                    a.addClass('boxhalfday')
                                } else if (attend_status == 'L' || attend_status == 'HL') {
                                    a.addClass('boxleave')
                                } else if (attend_status == 'HO') {
                                    a.addClass('boxholiday')
                                }
                            });

                            // console.log(result);
                            if (result.status == 1) {
                                toastr.success(result.msg)
                            } else {
                                toastr.error(result.msg)
                            }

                        }
                    })

                }, 1500)
            }

        }


        function CalendarControl() {
            const calendar = new Date();
            const calendarControl = {
                localDate: new Date(),
                prevMonthLastDate: null,
                calWeekDays: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                calMonthName: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec"
                ],
                daysInMonth: function(month, year) {
                    return new Date(year, month, 0).getDate();
                },
                firstDay: function() {
                    return new Date(calendar.getFullYear(), calendar.getMonth(), 1);
                },
                lastDay: function() {
                    return new Date(calendar.getFullYear(), calendar.getMonth() + 1, 0);
                },
                firstDayNumber: function() {
                    return calendarControl.firstDay().getDay() + 1;
                },
                lastDayNumber: function() {
                    return calendarControl.lastDay().getDay() + 1;
                },
                getPreviousMonthLastDate: function() {
                    let lastDate = new Date(
                        calendar.getFullYear(),
                        calendar.getMonth(),
                        0
                    ).getDate();
                    return lastDate;
                },
                navigateToPreviousMonth: function() {
                    calendar.setMonth(calendar.getMonth() - 1);
                    calendarControl.attachEventsOnNextPrev();
                    getCalenderData(calendar.getMonth(), calendar.getFullYear());
                },
                navigateToNextMonth: function() {
                    calendar.setMonth(calendar.getMonth() + 1);
                    calendarControl.attachEventsOnNextPrev();
                    getCalenderData(calendar.getMonth(), calendar.getFullYear());
                },
                navigateToCurrentMonth: function() {
                    let currentMonth = calendarControl.localDate.getMonth();
                    let currentYear = calendarControl.localDate.getFullYear();
                    calendar.setMonth(currentMonth);
                    calendar.setYear(currentYear);
                    calendarControl.attachEventsOnNextPrev();
                },
                displayYear: function() {
                    let yearLabel = document.querySelector(".calendar .calendar-year-label");
                    yearLabel.innerHTML = calendar.getFullYear();
                },
                displayMonth: function() {
                    let monthLabel = document.querySelector(
                        ".calendar .calendar-month-label"
                    );
                    monthLabel.innerHTML = calendarControl.calMonthName[calendar.getMonth()];
                },
                selectDate: function(e) {
                    // alert(calendar.getDate());
                    getTodayAttendanceData(`${e.target.textContent} ${
              calendarControl.calMonthName[calendar.getMonth()]
            } ${calendar.getFullYear()}`);
                    console.log(
                        `${e.target.textContent} ${
              calendarControl.calMonthName[calendar.getMonth()]
            } ${calendar.getFullYear()}`
                    );
                },
                plotSelectors: function() {
                    document.querySelector(
                        ".calendar"
                    ).innerHTML += `<div class="calendar-inner"><div class="calendar-controls">
            <div class="calendar-prev"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128"><path fill="#666" d="M88.2 3.8L35.8 56.23 28 64l7.8 7.78 52.4 52.4 9.78-7.76L45.58 64l52.4-52.4z"/></svg></a></div>
            <div class="calendar-year-month">
            <div class="calendar-month-label"></div>
            <div>-</div>
            <div class="calendar-year-label"></div>
            </div>
            <div class="calendar-next"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128"><path fill="#666" d="M38.8 124.2l52.4-52.42L99 64l-7.77-7.78-52.4-52.4-9.8 7.77L81.44 64 29 116.42z"/></svg></a></div>
            </div>

      
            <div class="calendar-today-date">Today: 
              ${calendarControl.calWeekDays[calendarControl.localDate.getDay()]}, 
              ${calendarControl.localDate.getDate()}, 
              ${calendarControl.calMonthName[calendarControl.localDate.getMonth()]} 
              ${calendarControl.localDate.getFullYear()}
            </div>
            
            <div class="calendar-body"></div></div>`;
                },
                plotDayNames: function() {
                    for (let i = 0; i < calendarControl.calWeekDays.length; i++) {
                        document.querySelector(
                            ".calendar .calendar-body"
                        ).innerHTML += `<div>${calendarControl.calWeekDays[i]}</div>`;
                    }
                },
                plotDates: function() {
                    document.querySelector(".calendar .calendar-body").innerHTML = "";
                    calendarControl.plotDayNames();
                    calendarControl.displayMonth();
                    calendarControl.displayYear();
                    let count = 1;
                    let prevDateCount = 0;

                    calendarControl.prevMonthLastDate = calendarControl.getPreviousMonthLastDate();
                    let prevMonthDatesArray = [];
                    let calendarDays = calendarControl.daysInMonth(
                        calendar.getMonth() + 1,
                        calendar.getFullYear()
                    );
                    // dates of current month

                    for (let i = 1; i < calendarDays; i++) {
                        if (i < calendarControl.firstDayNumber()) {
                            prevDateCount += 1;
                            document.querySelector(
                                ".calendar .calendar-body"
                            ).innerHTML += `<div class="prev-dates"></div>`;
                            prevMonthDatesArray.push(calendarControl.prevMonthLastDate--);
                        } else {
                            document.querySelector(
                                    ".calendar .calendar-body"
                                ).innerHTML +=
                                `<div class="number-item" data-num=${count}><a class="dateNumber" href="#">${count++}</a></div>`;
                        }
                    }

                    //remaining dates after month dates
                    for (let j = 0; j < prevDateCount + 1; j++) {
                        document.querySelector(
                                ".calendar .calendar-body"
                            ).innerHTML +=
                            `<div class="number-item" data-num=${count}><a class="dateNumber date" href="#">${count++}</a></div>`;
                    }

                    calendarControl.highlightToday();
                    calendarControl.plotPrevMonthDates(prevMonthDatesArray);
                    calendarControl.plotNextMonthDates();
                },
                attachEvents: function() {
                    let prevBtn = document.querySelector(".calendar .calendar-prev a");
                    let nextBtn = document.querySelector(".calendar .calendar-next a");
                    let todayDate = document.querySelector(".calendar .calendar-today-date");
                    let dateNumber = document.querySelectorAll(".calendar .dateNumber");
                    prevBtn.addEventListener(
                        "click",
                        calendarControl.navigateToPreviousMonth
                    );
                    nextBtn.addEventListener("click", calendarControl.navigateToNextMonth);
                    todayDate.addEventListener(
                        "click",
                        calendarControl.navigateToCurrentMonth
                    );
                    for (var i = 0; i < dateNumber.length; i++) {
                        dateNumber[i].addEventListener(
                            "click",
                            calendarControl.selectDate,
                            false
                        );
                    }
                },

                /*for prsent day classilist calendar -today pink color add*/
                highlightToday: function() {
                    let currentMonth = calendarControl.localDate.getMonth() + 1;
                    let changedMonth = calendar.getMonth() + 1;
                    let currentYear = calendarControl.localDate.getFullYear();
                    let changedYear = calendar.getFullYear();
                    if (
                        currentYear === changedYear &&
                        currentMonth === changedMonth &&
                        document.querySelectorAll(".number-item")
                    ) {
                        document
                            .querySelectorAll(".number-item")[calendar.getDate() - 1].classList.add(
                                "calendar-today");
                    }
                },


                plotPrevMonthDates: function(dates) {
                    dates.reverse();
                    for (let i = 0; i < dates.length; i++) {
                        if (document.querySelectorAll(".prev-dates")) {
                            document.querySelectorAll(".prev-dates")[i].textContent = dates[i];
                        }
                    }
                },
                plotNextMonthDates: function() {
                    let childElemCount = document.querySelector('.calendar-body').childElementCount;
                    //7 lines
                    if (childElemCount > 42) {
                        let diff = 49 - childElemCount;
                        calendarControl.loopThroughNextDays(diff);
                    }

                    //6 lines
                    if (childElemCount > 35 && childElemCount <= 42) {
                        let diff = 42 - childElemCount;
                        calendarControl.loopThroughNextDays(42 - childElemCount);
                    }

                },
                loopThroughNextDays: function(count) {
                    if (count > 0) {
                        for (let i = 1; i <= count; i++) {
                            document.querySelector('.calendar-body').innerHTML +=
                                `<div class="next-dates">${i}</div>`;
                        }
                    }
                },
                attachEventsOnNextPrev: function() {
                    calendarControl.plotDates();
                    calendarControl.attachEvents();
                },
                init: function() {
                    calendarControl.plotSelectors();
                    calendarControl.plotDates();
                    calendarControl.attachEvents();
                    getCalenderData(calendar.getMonth(), calendar.getFullYear());
                }
            };
            calendarControl.init();
        }

        const calendarControl = new CalendarControl();
    </script>
    <script>
        $(document).ready(function() {
            // getCalenderData(calendar.getMonth(), calendar.getFullYear());
        })
    </script>
@endsection
