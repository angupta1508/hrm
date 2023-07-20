@extends('layouts.front-user.app')

@section('content')
<main class="maindash">
    <section id="main" class="main">

        <div class="search  mx-auto">
            <!-- <span class="fa fa-search"></span> -->
            <input placeholder="Type your Keywords to search..." class="rounded-pill px-4 py-4">
        </div>
        <div class="baner">
            <div class="baner-text">
                <h1 class="text-light fw-semibold">Hello, {{ $authdetail->name }}</h1>
                {{-- <p class="text-light mt-0 my-2 d-none d-sm-block">In publishing and graphic design, Lorem ipsum
                        is a placeholder text commonly used</p> --}}
                <p class="text-light subhead fw-semibold">{{ $authdetail->designation_name }} of
                    {{ $authdetail->company_name }}
                </p>
            </div>
            <div class="baner-png">
                <img src="{{ asset('assets/front/img/Group 180.png') }}" alt="">
            </div>
        </div>

        <div class="weekly">

            <div class="row gap-5 mt-4 ">
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-3 ourservicehrm text-center rounded-4 mx-auto">
                    <a href="{{ route('notice') }}"> <img src="{{ asset('assets/front/img/Group 168.png') }}" class="mt-2" alt="" srcset=""></a>
                    <p>Notice Board</p>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-3 ourservicehrm text-center  rounded-4 mx-auto">
                    <a href="{{ route('wish') }}"> <img src="{{ asset('assets/front/img/wishes.png') }}" class="mt-2" alt="" srcset=""></a>
                    <p>Wishes</p>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-3 ourservicehrm text-center  rounded-4 mx-auto">
                    <a href="{{ route('attendance-regularise.index') }}"> <img src="{{ asset('assets/front/img/Group 168.png') }}" class="mt-2" class="" alt="" srcset=""></a>
                    <p>Regularisition</p>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-3 ourservicehrm text-center  rounded-4 mx-auto">
                    <a href="{{ route('employe-leave.index') }}" class="text-decoration-none"> <img src="{{ asset('assets/front/img/Group 169.png') }}" class="mt-2" alt="" srcset=""></a>
                    <p>Leaves</p>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-3  ourservicehrm text-center rounded-4 mx-auto">
                    <a href="{{ route('checkInOut') }}"> <img src="{{ asset('assets/front/img/Group 171.png') }}" class="mt-2" alt="" srcset=""></a>
                    <p>Attendance</p>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-3  ourservicehrm text-center rounded-4 mx-auto">
                    <a href="{{route('calender')}}"> <img src="{{ asset('assets/front/img/Group 178.png') }}" class="mt-2" alt="" srcset=""></a>
                    <p>Calendar</p>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-3  ourservicehrm text-center rounded-4 mx-auto">
                    <a href="{{ route('approveAttendanceList') }}"><img src="{{ asset('assets/front/img/Group 179.png') }}" class="mt-2" alt="" srcset=""></a>
                    <p>Approvel</p>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-3  ourservicehrm text-center rounded-4 mx-auto">
                    <img src="{{ asset('assets/front/img/Group 177.png') }}" class="mt-2" alt="" srcset="">
                    <p>Report</p>
                </div>



                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-3  ourservicehrm text-center rounded-4 mx-auto">
                    <a href="./payslip.html"><img src="{{ asset('assets/front/img/Group 174.png') }}" class="mt-2" alt="" srcset=""></a>
                    <p>Pay slip</p>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-3  ourservicehrm text-center mx-auto rounded-4" data-bs-toggle="modal" data-bs-target="#exampleModalproject">
                    <img src="{{ asset('assets/front/img/Group 168.png') }}" class="mt-2" alt="" srcset="">
                    <p>Projets</p>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-3  ourservicehrm text-center mx-auto rounded-4">
                    <a href="{{ route('userList') }}"> <img src="{{ asset('assets/front/img/Group 172.png') }}" class="mt-2" alt="" srcset=""></a>
                    <p>Team</p>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-3 col-3  ourservicehrm text-center rounded-4 mx-auto">
                    <a href="{{ route('attendanceList') }}"> <img src="{{ asset('assets/front/img/Group 171.png') }}" class="mt-2" alt="" srcset=""></a>
                    <p>Attendance List</p>
                </div>

            </div>
        </div>
    </section>


    <section id="statistics" class="statistics d-md-none d-lg-none d-xl-block d-sm-none">

        <aside class="main-navright">
            <h3 class="text-light fw-600 text-center mt-2 pt-2">PANEL BOARD</h3>
            {{-- <p class="text-light fs-5 mx-3 mt-4">Teams</p>
            <ul class="list-unstyled order-list  d-flex justify-content-evenly align-items-center">
                <li class="team-members team-member-sm">
                    <img class="rounded-circle" src="https://bootdey.com/img/Content/avatar/avatar8.png" style="width: 65px;" alt="user" data-toggle="tooltip" title="" data-original-title="Wildan Ahdian" />
                </li>

                <li class="team-members team-member-sm">
                    <img class="rounded-circle " src="https://bootdey.com/img/Content/avatar/avatar1.png" style="width: 65px;" alt="user" data-toggle="tooltip" title="" data-original-title="Sarah Smith" />
                </li>


                <li class="team-members team-member-sm">
                    <img class="rounded-circle " src="https://bootdey.com/img/Content/avatar/avatar1.png" style="width: 65px;" alt="user" data-toggle="tooltip" title="" data-original-title="Sarah Smith" />
                </li>
                <li class="">
                    <span class="border border-dark py-4 px-3 rounded-circle bg-light themeclr">+150</span>
                </li>
            </ul>

            <p class="text-light fs-5 mx-3 mt-5">Calendar</p>
            <div class="d-flex">
                <div class="mx-2">
                    <div class="calendar"></div>

                </div>

                <div class="colorcode">
                    <div class="d-flex align-items-center">
                        <div class="boxred"></div>
                        <p class="text-light fw-600 mt-3 mx-1">Absent</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="boxblue"></div>
                        <p class="text-light fw-600 mt-3 mx-1">Partial</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="boxgreen"></div>
                        <p class="text-light fw-600 mt-3 mx-1">Present</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="boxweekoff"></div>
                        <p class="text-light fw-600 mt-3 mx-1">Week Off</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="selectedday"></div>
                        <p class="text-light fw-600 mt-3 mx-1">Task Day</p>
                    </div>
                </div>
            </div> --}}

            {{-- <p class="text-light fs-5 mx-3 mt-5">Statistics</p> --}}
            {{-- <img src="./assest/img/hrmgraph.png" class="mx-4" style="width: 90%;" alt="" srcset=""> --}}

            <div class="card mx-3 px-3 mb-3 py-3 rounded-4">
                <p class="fs-5 mt-3 themeclr text-center fw-semibold">TODAY WISHES/CELEBRATION</p>
                @foreach ($todayCelebrationList as $list)
                <div class="row">
                    <div class="col-2">
                        <img class="rounded-circle birthdayimg" src="{{ $list->profile_image }}" alt="user" data-toggle="tooltip" title="" data-original-title="Sarah Smith" />
                    </div>
                    <div class="col-7">
                        <p class="fs-6">Wish <b>{{ $list->name }}</b>
                            @php $type = !empty($list->type) ? $list->type : '' @endphp
                            @if ($type == 'birthday')
                            Happy Birthday
                            @php $bd = !empty($list->bd) ? $list->bd : '' @endphp
                            <b class="text-success fs-6"> {{$bd}}</b>
                            @elseif($type == 'anniversary')
                            Happy Anniversary
                            @php $anniv = !empty($list->anniv) ? $list->anniv : '' @endphp
                            <b class="text-success fs-6"> {{$anniv}}</b>
                            @endif
                        </p>
                    </div>
                    <div class="col-3">
                        <button class="btn-outline-danger px-3 py-2 rounded-2 ">Wish</button>
                    </div>
                </div>
                @endforeach

                <p class="fs-5 mt-3 themeclr text-center fw-semibold">UPCOMING WISHES/CELEBRATION</p>
                @foreach ($celebrationList as $list)
                <div class="row mt-3">
                    <div class="col-2 align-self-center">
                        <img class="rounded-circle birthdayimg" src="{{ $list->profile_image }}" alt="user" data-toggle="tooltip" title="" data-original-title="Sarah Smith" />
                    </div>
                    <div class="col-10 align-self-center">
                        <p class="fs-6 mb-0">Wish <b>{{ $list->name }}</b>
                            {{$list->wish_msg}}
                            <b class="text-success fs-6"> {{prettyDateFormet($list->wish_date,'date')}}</b>
                          </p>
                    </div>
                </div>
                @endforeach



                <p class="fs-5 mt-3 themeclr text-center fw-semibold">How's your Mood Today</p>
                <div class="d-flex justify-content-between">
                    <i class="fa-regular fa-face-smile-beam fs-1  text-warning"></i>
                    <i class="fa-solid fa-face-smile fs-1 themeclr"></i>
                    <i class="fa-regular fa-face-frown-open fs-1 text-secondary"></i>
                    <i class="fa-solid fa-face-frown fs-1"></i>
                    <i class="fa-solid fa-face-sad-cry fs-1 text-danger"></i>
                </div>

            </div>
        </aside>
    </section>

    <div class="modal fade" id="exampleModalmeeting" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content py-3">

                <div class="modal-body row">
                    <div class="form-group  col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="input-group ">
                            <input type="tel" class="form-control rounded-5 p-4 bgthemelight" placeholder="New Project Meeting Title" />
                        </div>
                    </div>
                    <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

                        <div class="input-group ">
                            <select name="" id="" class="w-100 rounded-5 border-0 bgthemelight text-black fw-semibold" style="padding: 14px;">
                                <option value="">Meeting Type</option>
                                <option value="">Quartly Meeting</option>
                                <option value="">Half early Meeting</option>
                                <option value="">Annual Meeting</option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group py-2 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <label class="themeclr fw-semibold">Start Date</label>
                        <div class="input-group ">
                            <input type="Date" class="form-control rounded-5 p-4 bgthemelight text-black fw-semibold" placeholder="Start Date" />
                        </div>
                    </div>

                    <div class="form-group py-2 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <label class="themeclr fw-semibold">End Date</label>
                        <div class="input-group ">
                            <input type="Date" class="form-control rounded-5 p-4 bgthemelight text-black fw-semibold" placeholder="Start Date" />
                        </div>
                    </div>
                    <div class="form-group py-2 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <textarea id="comment-rating" rows="4" cols="55" placeholder="Additional Note" class="border-0 bgthemelight rounded-4 p-2" name="feedback"></textarea>
                    </div>
                    <div class="participatnt">
                        <p class="fw-semibold mx-3">participants</p>
                        <ul class="list-unstyled order-list  d-flex justify-content-evenly align-items-center">
                            <li class="team-members team-member-sm">
                                <img class="rounded-circle" src="https://bootdey.com/img/Content/avatar/avatar8.png" style="width: 45px;" alt="user" data-toggle="tooltip" title="" data-original-title="Wildan Ahdian" />
                            </li>

                            <li class="team-members team-member-sm">
                                <img class="rounded-circle " src="https://bootdey.com/img/Content/avatar/avatar1.png" style="width: 45px;" alt="user" data-toggle="tooltip" title="" data-original-title="Sarah Smith" />
                            </li>

                            <li class="team-members team-member-sm">
                                <img class="rounded-circle" src="https://bootdey.com/img/Content/avatar/avatar8.png" style="width: 45px;" alt="user" data-toggle="tooltip" title="" data-original-title="Wildan Ahdian" />
                            </li>
                            <li class="team-members team-member-sm">
                                <img class="rounded-circle " src="https://bootdey.com/img/Content/avatar/avatar1.png" style="width: 45px;" alt="user" data-toggle="tooltip" title="" data-original-title="Sarah Smith" />
                            </li>
                            <li class="team-members team-member-sm">
                                <img class="rounded-circle" src="https://bootdey.com/img/Content/avatar/avatar8.png" style="width: 45px;" alt="user" data-toggle="tooltip" title="" data-original-title="Wildan Ahdian" />
                            </li>
                            <li class="team-members team-member-sm">
                                <img class="rounded-circle" src="https://bootdey.com/img/Content/avatar/avatar8.png" style="width: 45px;" alt="user" data-toggle="tooltip" title="" data-original-title="Wildan Ahdian" />
                            </li>

                        </ul>
                    </div>

                </div>
                <div class=" mb-3">
                    <!-- <button type="button" class="btn btn-outline-danger mx-4 px-5 py-2 rounded-5 fs-5 fw-semibold" data-bs-dismiss="modal">Cancel</button> -->
                    <a href="" class=""> <button type="button" class="col-11 bgtheme border-0 rounded-5 mx-4 subhead text-light py-2 fw-semibold">Continue</button></a>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="exampleModalproject" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content py-3">

                <div class="modal-body row">

                    <div class="form-group col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

                        <div class="input-group ">
                            <select name="" id="" class="w-100 rounded-5 border-0 bgthemelight text-black fw-semibold" style="padding: 14px;">
                                <option value="">Project Name</option>
                                <option value="">Synilogic HRM</option>
                                <option value="">Astrogiri</option>
                                <option value="">Astro Jyotish</option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group mt-2  col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="input-group ">
                            <input type="tel" class="form-control rounded-5 p-4 bgthemelight" placeholder="Invite People" />
                        </div>
                    </div>

                    <div class="form-group py-2 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <textarea id="comment-rating" rows="4" cols="55" placeholder="Additional Note" class="border-0 bgthemelight rounded-4 p-2" name="feedback"></textarea>
                    </div>
                    <div class="participatnt mx-3">
                        <input type="radio" id="html" name="fav_language" class="" value="HTML">
                        <label for="html">Add Tasks</label>

                    </div>

                </div>
                <div class="">
                    <!-- <button type="button" class="btn btn-outline-danger mx-4 px-5 py-2 rounded-5 fs-5 fw-semibold" data-bs-dismiss="modal">Cancel</button> -->
                    <a href="" class="mb-3"> <button type="button" class="col-11 bgtheme border-0
               rounded-5 mx-4 subhead text-light py-2 fw-semibold mb-3">Confirm</button></a>
                    <a href="" class="mt-3"> <button type="button" class="col-11 bgthemelight themeclr border-0
                rounded-5 mx-4 subhead  py-2 ">Add
                            More <i class="fa-solid fa-plus"></i></button></a>
                </div>
            </div>
        </div>
    </div>
</main>


@if(!empty($notice))
<script>
    $(document).ready(function() {
        $('#welcome-popup').modal('show');
    });
</script>

<div class="modal fade" id="welcome-popup" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="text-primary">Welcome-Message..!</h4>
       
            </div>
            <!-- <div class="modal-body">
                <p>{{ $notice->title }}</p>
            </div> -->
            <div class="modal-body">
                <p>{!! $notice->description !!}</p>
            </div>
           
        </div>
    </div>
</div>
@endif


<script>
    const popup = document.getElementById('welcome-popup');
    const closeButton = document.getElementById('close-popup');

    closeButton.addEventListener('click', () => {
      popup.style.display = 'none';
    });


    // $(document).ready(function() {
    //     setTimeout(function() {
    //         $("#welcome-popup").fadeOut(function() {
    //             // this function will be executed after the fadeOut animation is completed
    //             window.location.href = "employee-dashboard";
    //         });
    //     }, 5000); // change the delay time (in milliseconds) as per your need
    // });
</script>
@endsection