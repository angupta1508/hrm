<nav class="navbardash">
    <div class="logo">

        <a href="{{ route('employeedashboard') }}" class="logo text-decoration-none">
            @php
                $imgPath = config('constants.setting_image_path');
                $imgDefaultPath = config('constants.default_image_path');
                $logo = ImageShow($imgPath, config()->get('logo'), 'icon', $imgDefaultPath);
            @endphp
            <img src="{{ $logo }}" class="" alt="">
        </a> 
    </div>
    <ul class="navbar-nav">
        <li><a href="{{ route('employeedashboard') }}"
                class=" text-decoration-none d-flex flex-column align-items-center py-2">
                <i class="fa-solid fa-house fs-4 mt-4 mb-2"></i>Home
            </a></li>
        <li><a href="{{route('userList')}}" class=" text-decoration-none d-flex flex-column align-items-center  py-2">
                <i class="fa-solid fa-users fs-4 mt-4 mb-2"></i>Teams
            </a></li>
        <li><a href="{{route('profile')}}" class=" text-decoration-none d-flex flex-column align-items-center  py-2">
                <i class="fa-solid fa-user fs-4 mt-4 mb-2"></i>Profile
            </a></li>
        <li><a href="{{route('calender')}}" class=" text-decoration-none d-flex flex-column align-items-center  py-2">
                <i class="fa-solid fa-calendar-days  fs-4 mt-4 mb-2"></i>Calendar
            </a></li>
        <li class="notificationmobile "><a href="{{route('userNotification')}}"
                class="text-decoration-none d-flex flex-column align-items-center  py-2">
                <i class="fa-solid fa-bell  fs-4 mt-4 mb-2"></i>Notification
            </a></li>
        <li><a href="#" class="text-decoration-none d-flex flex-column align-items-center py-2"
                data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fa-solid fa-right-from-bracket fs-4 mt-4 mb-2"></i>Sign Out
            </a></li>

    </ul>
</nav>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content py-3">

            <div class="modal-body">
                <div class="text-center mt-3">
                    <p class="fs-4 fw-semibold w-75 mx-auto">Are you sure want to sign out?</p>
                </div>
            </div>
            <div class="d-flex mx-auto mb-3">
                <button type="button" class="btn btn-outline-danger mx-4 px-5 py-2 rounded-5 fs-5 fw-semibold"
                    data-bs-dismiss="modal">Cancel</button>
                <a href="{{ route('employeeLogout') }}" class="bgtheme rounded-5"> <button type="button"
                        class="btn mx-4 fs-5 text-light px-5 py-2 fw-semibold">Sure</button></a>
            </div>
        </div>
    </div>
</div>
<!-- for smaller screen -->
<div class="hrmlogo bgtheme w-100  mb-4 py-2">
    <a href="#" class="text-decoration-none"><img src="./assest/img/hrm LOGO WHITWE 1.png" class=""
            alt=""></a>
    <a href="./notification.html"> <i class="fa-solid fa-bell  fs-1 float-right me-4 mt-3 text-light position-relative">
            <span
                class="position-absolute notifictionsign top-0 start-100 translate-middle bg-success border border-success rounded-circle">
                <span class="visually-hidden">New alerts</span></i></a>
</div>
<!-- main screen -->
