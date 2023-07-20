@php
    
    use Illuminate\Support\Str;
    use App\Models\Role;
    $loggedUser = Auth::user();
    $roleTypeData = getRoleTypeData($loggedUser->role_id);
    $adminRoles = Role::where('status', '1');
    if ($roleTypeData->role_type == config('constants.role_type_superadmin')) {
        $adminRoles->where('slug', config('constants.role_slug_superadmin-staff'))->orwhere('slug', getListTranslate(config('constants.role_slug_admin')));
    } else {
        $adminRoles->where('slug', getListTranslate(config('constants.role_slug_admin-staff')));
    }
    // pr(getQuery($adminRoles));die;
    $adminRoles = $adminRoles->get();
    // pr(($adminRoles->toArray()));die;
    $userRoles = Role::where('status', '1')
        ->where('role_type', config('constants.User'))
        ->get();
    $adminStatus = false;
    $userStatus = false;
    
    foreach ($adminRoles as $routeRole) {
        if (Request::routeIs('admin.' . Str::slug($routeRole->name) . '.index')) {
            $adminStatus = true;
        }
    }
    
    foreach ($userRoles as $routeRole) {
        if (Request::routeIs('admin.' . Str::slug($routeRole->name) . '.index')) {
            $userStatus = true;
        }
    }
    
@endphp
<aside
    class="sidenav collapse navbar-collapse navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 "
    id="sidenav-main">

    <div class="sidenav-header">

        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>

        <a class="align-items-center  d-flex m-0 navbar-brand text-wrap" href="{{ route('admin.dashboard') }}">
            @php
                $imgPath = config('constants.setting_image_path');
                $imgDefaultPath = config('constants.default_image_path');
                $logo = ImageShow($imgPath, config()->get('logo'), 'icon', $imgDefaultPath);
            @endphp
            <img src="{{ $logo }}" style="height: 50px; width: auto;">

        </a>

    </div>

    <hr class="horizontal dark mt-0">

    <div class=" w-auto" id="sidenav-collapse-main">

        <ul class="navbar-nav">

            <!--dashboard-->

            <li class="nav-item">

                <a class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.dashboard') }}">

                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                        <svg width="12px" height="12px" viewBox="0 0 45 40" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">

                            <title>shop </title>

                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">

                                <g transform="translate(-1716.000000, -439.000000)" fill="#FFFFFF" fill-rule="nonzero">

                                    <g transform="translate(1716.000000, 291.000000)">

                                        <g transform="translate(0.000000, 148.000000)">

                                            <path class="color-background opacity-6"
                                                d="M46.7199583,10.7414583 L40.8449583,0.949791667 C40.4909749,0.360605034 39.8540131,0 39.1666667,0 L7.83333333,0 C7.1459869,0 6.50902508,0.360605034 6.15504167,0.949791667 L0.280041667,10.7414583 C0.0969176761,11.0460037 -1.23209662e-05,11.3946378 -1.23209662e-05,11.75 C-0.00758042603,16.0663731 3.48367543,19.5725301 7.80004167,19.5833333 L7.81570833,19.5833333 C9.75003686,19.5882688 11.6168794,18.8726691 13.0522917,17.5760417 C16.0171492,20.2556967 20.5292675,20.2556967 23.494125,17.5760417 C26.4604562,20.2616016 30.9794188,20.2616016 33.94575,17.5760417 C36.2421905,19.6477597 39.5441143,20.1708521 42.3684437,18.9103691 C45.1927731,17.649886 47.0084685,14.8428276 47.0000295,11.75 C47.0000295,11.3946378 46.9030823,11.0460037 46.7199583,10.7414583 Z">

                                            </path>

                                            <path class="color-background"
                                                d="M39.198,22.4912623 C37.3776246,22.4928106 35.5817531,22.0149171 33.951625,21.0951667 L33.92225,21.1107282 C31.1430221,22.6838032 27.9255001,22.9318916 24.9844167,21.7998837 C24.4750389,21.605469 23.9777983,21.3722567 23.4960833,21.1018359 L23.4745417,21.1129513 C20.6961809,22.6871153 17.4786145,22.9344611 14.5386667,21.7998837 C14.029926,21.6054643 13.533337,21.3722507 13.0522917,21.1018359 C11.4250962,22.0190609 9.63246555,22.4947009 7.81570833,22.4912623 C7.16510551,22.4842162 6.51607673,22.4173045 5.875,22.2911849 L5.875,44.7220845 C5.875,45.9498589 6.7517757,46.9451667 7.83333333,46.9451667 L19.5833333,46.9451667 L19.5833333,33.6066734 L27.4166667,33.6066734 L27.4166667,46.9451667 L39.1666667,46.9451667 C40.2482243,46.9451667 41.125,45.9498589 41.125,44.7220845 L41.125,22.2822926 C40.4887822,22.4116582 39.8442868,22.4815492 39.198,22.4912623 Z">

                                            </path>

                                        </g>

                                    </g>

                                </g>

                            </g>

                        </svg>

                    </div>

                    <span class="nav-link-text ms-1">{{ __('Dashboard') }}</span>

                </a>

            </li>

            @if (checkPackageModulePermission('roles') == true)
                <li class="nav-item">

                    <a data-bs-toggle="collapse" href="#sidebarRoles"
                        class="nav-link {{ Request::routeIs('admin.superadmin-staff-roles.index') || Request::routeIs('admin.admin-roles.index') || Request::routeIs('admin.user-roles.index') ? 'active' : '' }}"
                        aria-controls="applicationsExamples" role="button" aria-expanded="true">

                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-person-plus-fill" viewBox="0 0 16 16">

                                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />

                                <path fill-rule="evenodd"
                                    d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z" />

                            </svg>

                        </div>

                        <span class="nav-link-text ms-1">{{ __('Roles') }}</span>

                    </a>

                    <div class="collapse {{ Request::routeIs('admin.superadmin-staff-roles.index') || Request::routeIs('admin.admin-roles.index') || Request::routeIs('admin.user-roles.index') ? 'show' : '' }}"
                        id="sidebarRoles">

                        <ul class="nav ms-4 ps-3">

                            @if (in_array($roleTypeData->role_type, [config('constants.role_type_superadmin')]))
                                <li class="nav-item ">

                                    <a class="nav-link {{ Request::routeIs('admin.superadmin-staff-roles.index') ? 'active' : '' }} "
                                        href="{{ route('admin.superadmin-staff-roles.index') }}">

                                        <span class="sidenav-mini-icon"> U </span>

                                        <span class="sidenav-normal">{{ __('Staff') }}</span>

                                    </a>

                                </li>
                            @endif
                            @if (in_array($roleTypeData->role_type, [config('constants.role_type_admin')]))
                                <li class="nav-item ">
                                    <a class="nav-link {{ Request::routeIs('admin.admin-roles.index') ? 'active' : '' }} "
                                        href="{{ route('admin.admin-roles.index') }}">

                                        <span class="sidenav-mini-icon"> U </span>

                                        <span class="sidenav-normal">{{ __('Admins') }}</span>

                                    </a>
                                </li>
                                <li class="nav-item ">

                                    <a class="nav-link {{ Request::routeIs('admin.user-roles.index') ? 'active' : '' }} "
                                        href="{{ route('admin.user-roles.index') }}">

                                        <span class="sidenav-mini-icon"> U </span>

                                        <span class="sidenav-normal">{{ __('Users') }} </span>

                                    </a>

                                </li>
                            @endif

                            {{-- @endif --}}


                        </ul>

                    </div>

                </li>
            @endif

            <li class="nav-item">

                <a data-bs-toggle="collapse" href="#sidebarAdminAccounts"
                    class="nav-link {{ $adminStatus ? 'active' : '' }} " aria-controls="applicationsExamples"
                    role="button" aria-expanded="true">

                    <div
                        class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">

                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-person-plus-fill" viewBox="0 0 16 16">

                            <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />

                            <path fill-rule="evenodd"
                                d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z" />

                        </svg>

                    </div>

                    <span class="nav-link-text ms-1">{{ __('Admin Accounts') }}</span>

                </a>

                <div class="collapse {{ $adminStatus ? 'show' : '' }}" id="sidebarAdminAccounts">

                    <ul class="nav ms-4 ps-3">
                        @foreach ($adminRoles as $routeRole)
                            <li class="nav-item ">

                                <a class="nav-link {{ Request::routeIs('admin.' . Str::slug($routeRole->name) . '.index') ? 'active' : '' }} "
                                    href="{{ route('admin.' . Str::slug($routeRole->name) . '.index') }}">

                                    <span class="sidenav-mini-icon"> U </span>
                                    <span class="sidenav-normal"> {{ __($routeRole->name) }} </span>

                                </a>

                            </li>
                        @endforeach


                    </ul>

                </div>

            </li>

            @if (checkPackageModulePermission('users') == true &&
                    in_array($roleTypeData->role_type, [config('constants.role_type_admin')]))
                <li class="nav-item">

                    <a data-bs-toggle="collapse" href="#sidebarUserAccounts"
                        class="nav-link {{ $userStatus || Request::routeIs('admin.users.index') || Request::routeIs('admin.user-bankers.index') || Request::routeIs('admin.allUserLocation') ? 'active' : '' }} "
                        aria-controls="applicationsExamples" role="button" aria-expanded="true">

                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">

                                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />

                                <path fill-rule="evenodd"
                                    d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z" />

                            </svg>

                        </div>

                        <span class="nav-link-text ms-1">{{ __('User Accounts') }}</span>

                    </a>

                    <div class="collapse {{ $userStatus || Request::routeIs('admin.users.index') || Request::routeIs('admin.user-bankers.index') || Request::routeIs('admin.allUserLocation') ? 'show' : '' }}"
                        id="sidebarUserAccounts">

                        <ul class="nav ms-4 ps-3">

                            {{-- <li class="nav-item ">

                                <a class="nav-link {{ Request::routeIs('admin.users.index') ? 'active' : '' }} "
                        href="{{ route('admin.users.index') }}">

                        <span class="sidenav-mini-icon"> U </span>

                        <span class="sidenav-normal">{{ __('All User') }} </span>

                        </a>

            </li> --}}

                            @foreach ($userRoles as $routeRole)
                                <li class="nav-item ">

                                    <a class="nav-link {{ Request::routeIs('admin.' . Str::slug($routeRole->name) . '.index') ? 'active' : '' }} "
                                        href="{{ route('admin.' . Str::slug($routeRole->name) . '.index') }}">

                                        <span class="sidenav-mini-icon"> U </span>

                                        <span class="sidenav-normal">{{ __('All') }} {{ __($routeRole->name) }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach

                            <li class="nav-item ">

                                <a class="nav-link {{ Request::routeIs('admin.user-bankers.index') ? 'active' : '' }} "
                                    href="{{ route('admin.user-bankers.index') }}">

                                    <span class="sidenav-mini-icon"> U </span>

                                    <span class="sidenav-normal">{{ __('Bankers') }} </span>

                                </a>

                            </li>

                            <li class="nav-item ">

                                <a class="nav-link {{ Request::routeIs('admin.allUserLocation') ? 'active' : '' }} "
                                    href="{{ route('admin.allUserLocation') }}">

                                    <span class="sidenav-mini-icon"> U </span>

                                    <span class="sidenav-normal">{{ __('Location Tracking') }} </span>

                                </a>

                            </li>

                            {{-- @endif --}}

                        </ul>

                    </div>

                </li>
            @endif


            {{-- attendence --}}
            @if (checkPackageModulePermission('attendence') == true &&
                    in_array($roleTypeData->role_type, [config('constants.role_type_admin')]))
                <li class="nav-item">

                    <a data-bs-toggle="collapse" href="#sidebarAttendance"
                        class="nav-link {{ Request::routeIs('admin.attendence.manualAttendance.index') ||
                        Request::routeIs('admin.attendence.attendance.attendanceReport') ||
                        Request::routeIs('admin.attendence.attendance.attendanceLog') ||
                        Request::routeIs('admin.attendence.attendance.missPunchReport') ||
                        Request::routeIs('admin.attendence.attendance-reasons.index') ||
                        Request::routeIs('admin.attendence.attendance.monthWiseReport') ||
                        Request::routeIs('admin.attendence.attendance.presentregister')
                            ? 'active'
                            : '' }}"
                        aria-controls="setting" role="button" aria-expanded="true">

                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="fa fa-address-card" aria-hidden="true"
                                viewBox="0 0 16 16">

                                <path
                                    d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />

                            </svg>

                        </div>

                        <span class="nav-link-text ms-1">{{ __('Attendance') }}</span>

                    </a>

                    <div class="collapse {{ Request::routeIs('admin.attendence.manualAttendance.index') ||
                    Request::routeIs('admin.attendence.attendance.attendanceReport') ||
                    Request::routeIs('admin.attendence.attendance.attendanceLog') ||
                    Request::routeIs('admin.attendence.attendance.missPunchReport') ||
                    Request::routeIs('admin.attendence.attendance-reasons.index') ||
                    Request::routeIs('admin.attendence.attendance.monthWiseReport') ||
                    Request::routeIs('admin.attendence.attendance.presentregister')
                        ? 'show'
                        : '' }}"
                        id="sidebarAttendance">

                        <ul class="nav ms-4 ps-3">

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.attendence.manualAttendance.index') ? 'active' : '' }} "
                                    href="{{ route('admin.attendence.manualAttendance.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Manual Attendance') }} </span>

                                </a>

                            </li>
                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.attendence.attendance.attendanceReport') ? 'active' : '' }} "
                                    href="{{ route('admin.attendence.attendance.attendanceReport') }}">

                                    <span class="nav-link-text ms-1">{{ __('Attendance Report') }}</span>

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.attendence.attendance.attendanceLog') ? 'active' : '' }} "
                                    href="{{ route('admin.attendence.attendance.attendanceLog') }}">

                                    <span class="nav-link-text ms-1">{{ __('Attendance Logs') }} </span>

                                </a>

                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('admin.attendence.attendance.presentregister') ? 'active' : '' }} "
                                    href="{{ route('admin.attendence.attendance.presentregister') }}">

                                    <span class="nav-link-text ms-1">{{ __('Present Register') }}</span>

                                </a>

                            </li>


                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('admin.attendence.attendance.monthWiseReport') ? 'active' : '' }} "
                                    href="{{ route('admin.attendence.attendance.monthWiseReport') }}">

                                    <span class="nav-link-text ms-1">{{ __('Month Wise Report') }}</span>

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.attendence.attendance-reasons.index') ? 'active' : '' }} "
                                    href="{{ route('admin.attendence.attendance-reasons.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Attendance Reasons') }}</span>

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.attendence.attendance.missPunchReport') ? 'active' : '' }} "
                                    href="{{ route('admin.attendence.attendance.missPunchReport') }}">

                                    <span class="nav-link-text ms-1">{{ __('Miss Punch Report') }}</span>

                                </a>

                            </li>


                        </ul>

                    </div>

                </li>
            @endif

            {{-- leave --}}
            @if (checkPackageModulePermission('leave') == true &&
                    in_array($roleTypeData->role_type, [config('constants.role_type_admin')]))
                <li class="nav-item">

                    <a data-bs-toggle="collapse" href="#sidebarLeave"
                        class="nav-link {{ Request::routeIs('admin.leave.leaves.index') ||
                        Request::routeIs('admin.leave.leave-types.index') ||
                        Request::routeIs('admin.leave.holidays.index')
                            ? 'active'
                            : '' }}"
                        aria-controls="setting" role="button" aria-expanded="true">

                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="fa fa-address-card" aria-hidden="true"
                                viewBox="0 0 16 16">

                                <path
                                    d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />

                            </svg>

                        </div>

                        <span class="nav-link-text ms-1">{{ __('Leave') }}</span>

                    </a>

                    <div class="collapse {{ Request::routeIs('admin.leave.leaves.index') ||
                    Request::routeIs('admin.leave.leave-types.index') ||
                    Request::routeIs('admin.leave.holidays.index')
                        ? 'show'
                        : '' }}"
                        id="sidebarLeave">

                        <ul class="nav ms-4 ps-3">

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.leave.leaves.index') ? 'active' : '' }} "
                                    href="{{ route('admin.leave.leaves.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Leave Application') }} </span>

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.leave.leave-types.index') ? 'active' : '' }} "
                                    href="{{ route('admin.leave.leave-types.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Leave Type') }} </span>

                                </a>

                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('admin.leave.holidays.index') ? 'active' : '' }} "
                                    href="{{ route('admin.leave.holidays.index') }}">


                                    <span class="nav-link-text ms-1">{{ __('Holiday') }}</span>

                                </a>

                            </li>



                        </ul>

                    </div>

                </li>
            @endif



            {{-- Payroll --}}
            @if (checkPackageModulePermission('payroll') == true &&
                    in_array($roleTypeData->role_type, [config('constants.role_type_admin')]))
                <li class="nav-item">

                    <a data-bs-toggle="collapse" href="#sidebarPayroll"
                        class="nav-link {{ Request::routeIs('admin.payroll.salary-types.index') ||
                        Request::routeIs('admin.payroll.salary-setup.index') ||
                        Request::routeIs('admin.payroll.salary-settlement.index') ||
                        Request::routeIs('admin.payroll.salary.index')
                            ? 'active'
                            : '' }}"
                        aria-controls="setting" role="button" aria-expanded="true">

                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="fa fa-address-card" aria-hidden="true"
                                viewBox="0 0 16 16">

                                <path
                                    d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />

                            </svg>

                        </div>

                        <span class="nav-link-text ms-1">{{ __('Payroll') }}</span>

                    </a>


                    <div class="collapse {{ Request::routeIs('admin.payroll.salary-types.index') ||
                    Request::routeIs('admin.payroll.salary-setup.index') ||
                    Request::routeIs('admin.payroll.salary-settlement.index') ||
                    Request::routeIs('admin.payroll.salary.index')
                        ? 'show'
                        : '' }}"
                        id="sidebarPayroll">

                        <ul class="nav ms-4 ps-3">

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.payroll.salary-types.index') ? 'active' : '' }} "
                                    href="{{ route('admin.payroll.salary-types.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Salary Types') }} </span>

                                </a>

                            </li>
                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.payroll.salary-setup.index') ? 'active' : '' }} "
                                    href="{{ route('admin.payroll.salary-setup.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Salary Setup') }} </span>

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.payroll.salary-settlement.index') ? 'active' : '' }} "
                                    href="{{ route('admin.payroll.salary-settlement.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Salary Settlement') }} </span>

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.payroll.salary.index') ? 'active' : '' }}"
                                    href="{{ route('admin.payroll.salary.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Salary') }} </span>

                                </a>

                            </li>

                        </ul>

                    </div>

                </li>
            @endif


            <!--setting start-->
            @if (checkPackageModulePermission('settings') == true &&
                    in_array($roleTypeData->role_type, [
                        config('constants.role_type_superadmin'),
                        config('constants.role_type_admin'),
                    ]))
                <li class="nav-item">

                    <a data-bs-toggle="collapse" href="#sidebarSetting"
                        class="nav-link {{ Request::routeIs('admin.setting.company') || Request::routeIs('admin.setting.email') || Request::routeIs('admin.setting.sms') || Request::routeIs('admin.setting.withdrawal') || Request::routeIs('admin.setting.payment') || Request::routeIs('admin.setting.social') ? 'active' : '' }} "
                        aria-controls="setting" role="button" aria-expanded="true">

                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">

                                <path
                                    d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />

                            </svg>

                        </div>

                        <span class="nav-link-text ms-1">{{ __('Setting') }}</span>

                    </a>

                    <div class="collapse {{ Request::routeIs('admin.setting.company') || Request::routeIs('admin.setting.email') || Request::routeIs('admin.setting.sms') || Request::routeIs('admin.setting.withdrawal') || Request::routeIs('admin.setting.payment') || Request::routeIs('admin.setting.social') ? 'show' : '' }}"
                        id="sidebarSetting">

                        <ul class="nav ms-4 ps-3">
                            @if (in_array($roleTypeData->role_type, [config('constants.role_type_superadmin'), config('constants.role_type_admin')]))
                                <li class="nav-item">

                                    <a class="nav-link {{ Request::routeIs('admin.setting.company') ? 'active' : '' }} "
                                        href="{{ route('admin.setting.company') }}">

                                        <span class="nav-link-text ms-1">{{ __('Company') }}</span>

                                    </a>

                                </li>
                            @endif
                            @if (in_array($roleTypeData->role_type, [config('constants.role_type_superadmin'), config('constants.role_type_admin')]))
                                <li class="nav-item">

                                    <a class="nav-link {{ Request::routeIs('admin.setting.email') ? 'active' : '' }} "
                                        href="{{ route('admin.setting.email') }}">

                                        <span class="nav-link-text ms-1">{{ __('Email') }}</span>

                                    </a>

                                </li>
                            @endif
                            @if (in_array($roleTypeData->role_type, [config('constants.role_type_superadmin'), config('constants.role_type_admin')]))
                                <li class="nav-item">

                                    <a class="nav-link {{ Request::routeIs('admin.setting.sms') ? 'active' : '' }} "
                                        href="{{ route('admin.setting.sms') }}">

                                        <span class="nav-link-text ms-1">{{ __('SMS') }}</span>

                                    </a>

                                </li>
                            @endif
                            @if (in_array($roleTypeData->role_type, [config('constants.role_type_superadmin'), config('constants.role_type_admin')]))
                                <li class="nav-item">

                                    <a class="nav-link {{ Request::routeIs('admin.setting.social') ? 'active' : '' }} "
                                        href="{{ route('admin.setting.social') }}">

                                        <span class="nav-link-text ms-1">{{ __('Social') }}</span>

                                    </a>

                                </li>
                            @endif
                            @if (in_array($roleTypeData->role_type, [config('constants.role_type_superadmin'), config('constants.role_type_admin')]))
                                <li class="nav-item">

                                    <a class="nav-link {{ Request::routeIs('admin.setting.payment') ? 'active' : '' }} "
                                        href="{{ route('admin.setting.payment') }}">

                                        <span class="nav-link-text ms-1">{{ __('Payments') }}</span>

                                    </a>

                                </li>
                            @endif

                        </ul>

                    </div>

                    </a>



                </li>
            @endif




            {{-- Administration --}}

            @if (checkPackageModulePermission('administration') == true &&
                    in_array($roleTypeData->role_type, [config('constants.role_type_admin')]))
                <li class="nav-item">

                    <a data-bs-toggle="collapse" href="#sidebarAdministration"
                        class="nav-link {{ Request::routeIs('admin.administration.languages.index') || Request::routeIs('admin.administration.banks.index') || Request::routeIs('admin.administration.companies.index') || Request::routeIs('admin.administration.locations.index') || Request::routeIs('admin.administration.departments.index') || Request::routeIs('admin.administration.designations.index') || Request::routeIs('admin.administration.shifts.index') || Request::routeIs('admin.administration.user-policy.index') || Request::routeIs('admin.tongue-languages.index') ? 'active' : '' }}"
                        aria-controls="setting" role="button" aria-expanded="true">

                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="fa fa-user-secret" aria-hidden="true"
                                viewBox="0 0 16 16">

                                <path
                                    d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />

                            </svg>

                        </div>

                        <span class="nav-link-text ms-1">{{ __('Administration') }}</span>

                    </a>

                    <div class="collapse {{ Request::routeIs('admin.administration.languages.index') || Request::routeIs('admin.administration.banks.index') || Request::routeIs('admin.administration.companies.index') || Request::routeIs('admin.administration.locations.index') || Request::routeIs('admin.administration.departments.index') || Request::routeIs('admin.administration.designations.index') || Request::routeIs('admin.administration.shifts.index') || Request::routeIs('admin.tongue-languages.index') || Request::routeIs('admin.administration.user-policy.index') ? 'show' : '' }}"
                        id="sidebarAdministration">

                        <ul class="nav ms-4 ps-3">

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.administration.languages.index') ? 'active' : '' }} "
                                    href="{{ route('admin.administration.languages.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Languages') }} </span>

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.administration.banks.index') ? 'active' : '' }} "
                                    href="{{ route('admin.administration.banks.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Banks') }}</span>

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.administration.companies.index') ? 'active' : '' }} "
                                    href="{{ route('admin.administration.companies.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Company') }}</span>

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.administration.locations.index') ? 'active' : '' }} "
                                    href="{{ route('admin.administration.locations.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Locations') }}</span>

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.administration.departments.index') ? 'active' : '' }} "
                                    href="{{ route('admin.administration.departments.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Departments') }}</span>

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.administration.designations.index') ? 'active' : '' }} "
                                    href="{{ route('admin.administration.designations.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Designation (Position)') }} </span>

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.administration.shifts.index') ? 'active' : '' }} "
                                    href="{{ route('admin.administration.shifts.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Shifts') }}</span>

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.administration.user-policy.index') ? 'active' : '' }} "
                                    href="{{ route('admin.administration.user-policy.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('User Policy') }}</span>

                                </a>

                            </li>




                        </ul>
                </li>
            @endif





            {{-- Moods --}}
            @if (checkPackageModulePermission('moods') == true &&
                    in_array($roleTypeData->role_type, [config('constants.role_type_admin')]))
                <li class="nav-item">

                    <a data-bs-toggle="collapse" href="#sidebarMood"
                        class="nav-link {{ Request::routeIs('admin.moods.moods.index') || Request::routeIs('admin.moods.Wishes.index') ? 'active' : '' }}"
                        aria-controls="setting" role="button" aria-expanded="true">

                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="fa fa-meh" aria-hidden="true" viewBox="0 0 16 16">

                                <path
                                    d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />

                            </svg>

                        </div>

                        <span class="nav-link-text ms-1">{{ __('Moods / Wishes') }}</span>

                    </a>

                    <div class="collapse {{ Request::routeIs('admin.moods.moods.index') || Request::routeIs('admin.moods.Wishes.index') ? 'show' : '' }}"
                        id="sidebarMood">

                        <ul class="nav ms-4 ps-3">

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.moods.moods.index') ? 'active' : '' }} "
                                    href="{{ route('admin.moods.moods.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Moods') }}</span>

                                </a>

                            </li>
                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.moods.Wishes.index') ? 'active' : '' }} "
                                    href="{{ route('admin.moods.Wishes.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Wishes') }}</span>

                                </a>

                            </li>
                        </ul>

                    </div>

                </li>
            @endif

            {{-- cms --}}
            @if (checkPackageModulePermission('cms') == true &&
                    in_array($roleTypeData->role_type, [
                        config('constants.role_type_superadmin'),
                        config('constants.role_type_admin'),
                    ]))
                <li class="nav-item">

                    <a data-bs-toggle="collapse" href="#sidebarWebsite"
                        class="nav-link {{ Request::routeIs('admin.cms.pages.index') || Request::routeIs('admin.cms.enquires.index') || Request::routeIs('admin.cms.notices.index') || Request::routeIs('admin.cms.notifications.index') ? 'active' : '' }}"
                        aria-controls="setting" role="button" aria-expanded="true">

                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="fa fa-life-ring" viewBox="0 0 16 16">

                                <path
                                    d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />

                            </svg>

                        </div>

                        <span class="nav-link-text ms-1">{{ __('CMS') }}</span>

                    </a>

                    <div class="collapse {{ Request::routeIs('admin.cms.pages.index') || Request::routeIs('admin.cms.enquires.index') || Request::routeIs('admin.cms.notices.index') || Request::routeIs('admin.cms.notifications.index') ? 'show' : '' }}"
                        id="sidebarWebsite">

                        <ul class="nav ms-4 ps-3">
                            @if (in_array($roleTypeData->role_type, [config('constants.role_type_superadmin')]))
                                <li class="nav-item">

                                    <a class="nav-link {{ Request::routeIs('admin.cms.pages.index') ? 'active' : '' }} "
                                        href="{{ route('admin.cms.pages.index') }}">

                                        <span class="nav-link-text ms-1">{{ __('Pages') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">

                                    <a class="nav-link {{ Request::routeIs('admin.cms.enquires.index') ? 'active' : '' }} "
                                        href="{{ route('admin.cms.enquires.index') }}">

                                        <span class="nav-link-text ms-1">{{ __('Contact Enquires') }}</span>
                                    </a>
                                </li>
                            @endif
                            @if (in_array($roleTypeData->role_type, [config('constants.role_type_admin')]))
                                <li class="nav-item">

                                    <a class="nav-link {{ Request::routeIs('admin.cms.notices.index') ? 'active' : '' }} "
                                        href="{{ route('admin.cms.notices.index') }}">

                                        <span class="nav-link-text ms-1">{{ __('Notice') }}</span>

                                    </a>

                                </li>

                                <li class="nav-item">

                                    <a class="nav-link {{ Request::routeIs('admin.cms.notifications.index') ? 'active' : '' }} "
                                        href="{{ route('admin.cms.notifications.index') }}">

                                        <span class="nav-link-text ms-1">{{ __('Notification') }}</span>

                                    </a>

                                </li>
                            @endif
                        </ul>

                    </div>

                </li>
            @endif


            {{-- master --}}
            @if (checkPackageModulePermission('master') == true &&
                    in_array($roleTypeData->role_type, [
                        config('constants.role_type_superadmin'),
                        config('constants.role_type_admin'),
                    ]))
                <li class="nav-item">

                    <a data-bs-toggle="collapse" href="#sidebarmaster"
                        class="nav-link {{ Request::routeIs('admin.master.duty-type.index') || Request::routeIs('admin.master.duty-types.index') || Request::routeIs('admin.master.leave-type-in-outs.index') ? 'active' : '' }}"
                        aria-controls="setting" role="button" aria-expanded="true">

                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">


                                <path
                                    d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />

                            </svg>

                        </div>

                        <span class="nav-link-text ms-1">{{ __('Master') }}</span>

                    </a>

                    <div class="collapse {{ Request::routeIs('admin.master.duty-type.index') || Request::routeIs('admin.master.duty-types.index') || Request::routeIs('admin.master.leave-type-in-outs.index') ? 'show' : '' }}"
                        id="sidebarmaster">

                        <ul class="nav ms-4 ps-3">

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.master.duty-types.index') ? 'active' : '' }} "
                                    href="{{ route('admin.master.duty-types.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Duty Type') }}</span>

                                </a>

                            </li>
                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.master.leave-type-in-outs.index') ? 'active' : '' }} "
                                    href="{{ route('admin.master.leave-type-in-outs.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Leave Type In Out') }}</span>

                                </a>

                            </li>



                        </ul>

                    </div>

                </li>
            @endif


            {{-- <li class="nav-item">

                <a class="nav-link {{ Request::routeIs('duty-types.index') ? 'active' : '' }}"
    href="{{ route('admin.duty-types.index') }}">

    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
        <i class="fa fa-snowflake-o" aria-hidden="true"></i>

    </div>

    <span class="nav-link-text ms-1">{{ __('Duty types') }}</span>

    </a>

    </li> --}}
            {{-- <li class="nav-item">

                <a class="nav-link {{ Request::routeIs('leave-type-in-outs.index') ? 'active' : '' }}"
    href="{{ route('admin.leave-type-in-outs.index') }}">

    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
        <i class="fa fa-snowflake-o" aria-hidden="true"></i>

    </div>

    <span class="nav-link-text ms-1">{{ __('Leave type in out') }}</span>

    </a>

    </li> --}}



            @if (checkPackageModulePermission('template') == true &&
                    in_array($roleTypeData->role_type, [config('constants.role_type_superadmin')]))
                <li class="nav-item">

                    <a data-bs-toggle="collapse" href="#sidebarTemplates"
                        class="nav-link {{ Request::routeIs('admin.email-template.-template..index') || Request::routeIs('admin.email-template.index') || Request::routeIs('admin.sms-template.index') ? 'active' : '' }}"
                        aria-controls="setting" role="button" aria-expanded="true">

                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">

                                <path
                                    d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />

                            </svg>

                        </div>

                        <span class="nav-link-text ms-1">{{ __('Templates') }}</span>

                    </a>

                    <div class="collapse {{ Request::routeIs('admin.email-template.index') || Request::routeIs('admin.sms-template.index') ? 'show' : '' }}"
                        id="sidebarTemplates">

                        <ul class="nav ms-4 ps-3">

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.email-template.index') ? 'active' : '' }} "
                                    href="{{ route('admin.email-template.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('Email') }}</span>

                                </a>

                            </li>

                            <li class="nav-item">

                                <a class="nav-link {{ Request::routeIs('admin.sms-template.index') ? 'active' : '' }} "
                                    href="{{ route('admin.sms-template.index') }}">

                                    <span class="nav-link-text ms-1">{{ __('SMS') }}</span>

                                </a>

                            </li>


                        </ul>

                    </div>

                </li>
            @endif
            {{-- <li class="nav-item">

                <a data-bs-toggle="collapse" href="#sidebarLocations"
                    class="nav-link {{ Request::routeIs('admin.countries.index') || Request::routeIs('admin.states.index') || Request::routeIs('admin.cities.index') ? 'active' : '' }}"
    aria-controls="setting" role="button" aria-expanded="true">

    <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">

        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">

            <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />

        </svg>

    </div>

    <span class="nav-link-text ms-1">{{ __('Leave') }}</span>

    </a>
    <div class="collapse" id="sidebarLeave">

        <ul class="nav ms-4 ps-3">

            <li class="nav-item ">

                <a class="nav-link" href="#">
                    <span class="sidenav-mini-icon"> U </span>
                    <span class="sidenav-normal">{{ __('Leave Type') }} </span>
                </a>
            </li>
            <li class="nav-item ">

                <a class="nav-link" href="#">
                    <span class="sidenav-mini-icon"> U </span>
                    <span class="sidenav-normal">{{ __('Holiday') }} </span>
                </a>
            </li>
            <li class="nav-item ">

                <a class="nav-link" href="#">
                    <span class="sidenav-mini-icon"> U </span>
                    <span class="sidenav-normal">{{ __('Leave') }} </span>
                </a>
            </li>
        </ul>
    </div>
    </li>
    @endif


    <!--setting start-->
    @if (checkPackageModulePermission('settings') == true && in_array($roleTypeData->role_type, [config('constants.role_type_superadmin'), config('constants.role_type_admin')]))
    <l-0i class="nav-item">

        <a data-bs-toggle="collapse" href="#sidebarSetting" class="nav-link {{ Request::routeIs('admin.setting.company') || Request::routeIs('admin.setting.email') || Request::routeIs('admin.setting.sms') || Request::routeIs('admin.setting.withdrawal') || Request::routeIs('admin.setting.payment') || Request::routeIs('admin.setting.social') ? 'active' : '' }} " aria-controls="setting" role="button" aria-expanded="true">

            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">

                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">

                    <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />

                </svg>

            </div>

            <span class="nav-link-text ms-1">{{ __('Setting') }}</span>

        </a>

        <div class="collapse {{ Request::routeIs('admin.setting.company') || Request::routeIs('admin.setting.email') || Request::routeIs('admin.setting.sms') || Request::routeIs('admin.setting.withdrawal') || Request::routeIs('admin.setting.payment') || Request::routeIs('admin.setting.social') ? 'show' : '' }}" id="sidebarSetting">

            <ul class="nav ms-4 ps-3">
                @if (in_array($roleTypeData->role_type, [config('constants.role_type_superadmin'), config('constants.role_type_admin')]))
                <li class="nav-item">

                    <a class="nav-link {{ Request::routeIs('admin.setting.company') ? 'active' : '' }} " href="{{ route('admin.setting.company') }}">

                        <span class="nav-link-text ms-1">{{ __('Company') }}</span>

                    </a>

                </li>
                @endif
                @if (in_array($roleTypeData->role_type, [config('constants.role_type_superadmin')]))
                <li class="nav-item">

                    <a class="nav-link {{ Request::routeIs('admin.setting.email') ? 'active' : '' }} " href="{{ route('admin.setting.email') }}">

                        <span class="nav-link-text ms-1">{{ __('Email') }}</span>

                    </a>

                </li>
                @endif
                @if (in_array($roleTypeData->role_type, [config('constants.role_type_superadmin')]))
                <li class="nav-item">

                    <a class="nav-link {{ Request::routeIs('admin.setting.sms') ? 'active' : '' }} " href="{{ route('admin.setting.sms') }}">

                        <span class="nav-link-text ms-1">{{ __('SMS') }}</span>

                    </a>

                </li>
                @endif
                @if (in_array($roleTypeData->role_type, [config('constants.role_type_superadmin'), config('constants.role_type_admin')]))
                <li class="nav-item">

                    <a class="nav-link {{ Request::routeIs('admin.setting.social') ? 'active' : '' }} " href="{{ route('admin.setting.social') }}">

                        <span class="nav-link-text ms-1">{{ __('Social') }}</span>
                    </a>
                </li>
                @endif

            </ul>

        </div>

        </li>
        @endif


        @if (checkPackageModulePermission('template') == true && in_array($roleTypeData->role_type, [config('constants.role_type_superadmin')]))
        <li class="nav-item">

            <a data-bs-toggle="collapse" href="#sidebarTemplates" class="nav-link {{ Request::routeIs('admin.email-template.-template..index') || Request::routeIs('admin.sms-template.index') ? 'active' : '' }}" aria-controls="setting" role="button" aria-expanded="true">

                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">

                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">

                        <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />

                    </svg>

                </div>

                <span class="nav-link-text ms-1">{{ __('Templates') }}</span>

            </a>

            <div class="collapse {{ Request::routeIs('admin.email-template.index') || Request::routeIs('admin.sms-template.index') ? 'show' : '' }}" id="sidebarTemplates">

                <ul class="nav ms-4 ps-3">

                    <li class="nav-item">

                        <a class="nav-link {{ Request::routeIs('admin.email-template.index') ? 'active' : '' }} " href="{{ route('admin.email-template.index') }}">

                            <span class="nav-link-text ms-1">{{ __('Email') }}</span>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link {{ Request::routeIs('admin.sms-template.index') ? 'active' : '' }} " href="{{ route('admin.sms-template.index') }}">

                            <span class="nav-link-text ms-1">{{ __('SMS') }}</span>

                        </a>

                    </li>


                </ul>

            </div>

        </li>
        @endif
        {{-- <li class="nav-item">

                <a data-bs-toggle="collapse" href="#sidebarLocations"
                    class="nav-link {{ Request::routeIs('admin.countries.index') || Request::routeIs('admin.states.index') || Request::routeIs('admin.cities.index') ? 'active' : '' }}"
        aria-controls="setting" role="button" aria-expanded="true">

        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">

            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">

                <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z" />

            </svg>

        </div>

        <span class="nav-link-text ms-1">{{ __('Locations') }}</span>

        </a>

        <div class="collapse {{ Request::routeIs('admin.countries.index') || Request::routeIs('admin.states.index') || Request::routeIs('admin.cities.index') ? 'show' : '' }}" id="sidebarLocations">

            <ul class="nav ms-4 ps-3">

                <li class="nav-item">

                    <a class="nav-link {{ Request::routeIs('admin.countries.index') ? 'active' : '' }} " href="{{ route('admin.countries.index') }}">

                        <span class="nav-link-text ms-1">{{ __('Countries') }}</span>

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link {{ Request::routeIs('admin.states.index') ? 'active' : '' }} " href="{{ route('admin.states.index') }}">

                        <span class="nav-link-text ms-1">{{ __('States/Provinces') }}</span>

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link {{ Request::routeIs('admin.cities.index') ? 'active' : '' }} " href="{{ route('admin.cities.index') }}">

                        <span class="nav-link-text ms-1">{{ __('Cities') }}</span>

                    </a>

                </li>


            </ul>

        </div>

        </li> --}}

            {{-- performace --}}
            @if (checkPackageModulePermission('performace') == true &&
                    in_array($roleTypeData->role_type, [config('constants.role_type_admin')]))
                <li class="nav-item">

                    <a data-bs-toggle="collapse" href="#packageDropdown"
                        class="nav-link {{ Request::routeIs('admin.performance.performance-types.index') ? 'active' : '' }}"
                        aria-controls="setting" role="button" aria-expanded="true">

                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">
                            <i class="fa fa-database" aria-hidden="true"></i>
                        </div>

                        <span class="nav-link-text ms-1">{{ __('Performance') }}</span>

                    </a>
                    <div class="collapse {{ Request::routeIs('admin.performance.performance-types.index') ? 'show' : '' }}"
                        id="packageDropdown">

                        <ul class="nav ms-4 ps-3">
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('admin.performance.performance-types.index') ? 'active' : '' }}"
                                    href="{{ route('admin.performance.performance-types.index') }}">
                                    <span class="nav-link-text ms-1">Performance</span>
                                </a>
                            </li>
                        </ul>

                    </div>

                </li>
            @endif




            @if (in_array($roleTypeData->role_type, [config('constants.role_type_superadmin')]))
                <li class="nav-item">

                    <a data-bs-toggle="collapse" href="#packageDropdown"
                        class="nav-link {{ Request::routeIs('admin.package.index') || Request::routeIs('admin.recharge.index') ? 'active' : '' }}"
                        aria-controls="setting" role="button" aria-expanded="true">

                        <div
                            class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center d-flex align-items-center justify-content-center  me-2">
                            <i class="fa fa-database" aria-hidden="true"></i>
                        </div>

                        <span class="nav-link-text ms-1">{{ __('Package') }}</span>

                    </a>
                    <div class="collapse {{ Request::routeIs('admin.package.index') || Request::routeIs('admin.recharge.index') ? 'show' : '' }}"
                        id="packageDropdown">

                        <ul class="nav ms-4 ps-3">
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('admin.package.index') ? 'active' : '' }}"
                                    href="{{ route('admin.package.index') }}">
                                    <span class="nav-link-text ms-1">{{ __('Package') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('admin.recharge.index') ? 'active' : '' }}"
                                    href="{{ route('admin.recharge.index') }}">
                                    <span class="nav-link-text ms-1">{{ __('Recharge History') }}</span>
                                </a>
                            </li>
                        </ul>

                    </div>

                </li>
            @endif


        </ul>

    </div>

</aside>
