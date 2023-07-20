<?php

use Illuminate\Support\Str;

?>

@extends('layouts.admin.app')
@section('content')
    <div class="accordion card filter_card mb-4" id="accordionFilter">
        <div class="accordion-item mb-3">
            <h5 class="accordion-header card-header p-3" id="headingFilter">
                <button class="accordion-button p-0 font-weight-bold collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                    <i class="fa fa-filter"></i> {{ __('Filter') }}
                    <i class="collapse-close fa fa-plus pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
                    <i class="collapse-open fa fa-minus pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
                </button>
            </h5>
            <div id="collapseFilter" class="accordion-collapse collapse @if (!empty($filter)) show @endif"
                aria-labelledby="headingFilter" data-bs-parent="#accordionFilter">
                <div class="accordion-body card-body p-3 text-sm opacity-8">
                    <form
                        action="{{ Request::routeIs('admin.' . $routeSlug . '.index') ? route('admin.' . $routeSlug . '.index') : route('admin.users.index') }}"
                        method="GET">
                        @csrf
                        <div class="border">
                            <div class="d-flex flex-row align-content-between flex-wrap">

                                <div class="p-2 flex-fill w-100">
                                    <input type="text" id="search" name="search" class="form-control"
                                        value="{{ !empty($filter['search']) ? $filter['search'] : '' }}" autocomplete="off"
                                        placeholder="{{ __('Search By Name , Username , Employee Code , Email , Mobile') }}">
                                </div>
                                <div class="p-2 flex-fill">
                                    {{ Form::select('user_id', ['' => __('Select Employee')] + $user_list, old('user_id', !empty($filter['user_id']) ? $filter['user_id'] : ''), ['class' => 'form-select select2']) }}
                                    @error('user_id')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="p-2 flex-fill">
                                    {{ Form::select('company_id', ['' => __('Select Company')] + $companies, old('company_id', !empty($filter['company_id']) ? $filter['company_id'] : ''), ['class' => 'form-select select2']) }}
                                    @error('company_id')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="p-2 flex-fill">
                                    {{ Form::select('department_id', ['' => __('Select Department')] + $departments, old('department_id', !empty($filter['department_id']) ? $filter['department_id'] : ''), ['class' => 'form-select select2']) }}
                                    @error('department_id')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="p-2 flex-fill">
                                    {{ Form::select('location_id', ['' => __('Select location')] + $locations, old('location_id', !empty($filter['location_id']) ? $filter['location_id'] : ''), ['class' => 'form-select select2']) }}
                                    @error('location_id')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="p-2 flex-fill">
                                    {{ Form::select('designation_id', ['' => __('Select Designation')] + $designation, old('designation_id', !empty($filter['designation_id']) ? $filter['designation_id'] : ''), ['class' => 'form-select select2']) }}
                                    @error('designation_id')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="p-2 flex-fill">
                                    {{ Form::select('shift_id', ['' => __('Select Shift')] + $shifts, old('shift_id', !empty($filter['shift_id']) ? $filter['shift_id'] : ''), ['class' => 'form-select select2']) }}


                                    @error('shift_id')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="p-2 flex-fill">
                                    <input type="text" name="start_date" class="form-control datepicker"
                                        autocomplete="off"
                                        value="{{ !empty($filter['start_date']) ? $filter['start_date'] : '' }}"
                                        placeholder="{{ __('Start Date') }}" />
                                </div>
                                <div class="p-2 flex-fill ">
                                    <input type="text" name="end_date" class="form-control datepicker" autocomplete="off"
                                        value="{{ !empty($filter['end_date']) ? $filter['end_date'] : '' }}"
                                        placeholder="{{ __('End Date') }}" />
                                </div>
                                <div class="p-2 flex-fill">
                                    {{ Form::select('status', ['' => __('Select Status')] + getListTranslate(config('constants.default_status')), isset($filter['status']) ? $filter['status'] : '', ['class' => 'form-select']) }}
                                </div>
                                <div class="p-2">
                                    <button type="submit" name="submit"
                                        class="btn btn-primary shadow-primary mb-0 button">
                                        {{ __('Filter') }}
                                    </button>
                                    <button type="submit" name="excel_export"
                                        class="btn btn-primary excel_export shadow-primary mb-0 button"
                                        value="{{ __('Export') }}">
                                        <i class="fas fa-file-excel"></i>
                                        {{ __('Export') }}
                                    </button>
                                    <button type="submit" name="pdf_export" value="{{ __('PDF') }}"
                                        class="btn btn-primary pdf_export shadow-primary mb-0 button">
                                        <i class="fas fa-file-pdf"></i> {{ __('PDF') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header p-3">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">{{ __('All') }} {{ __('Users') }}</h5>
                </div>
                <div>

                    <a href="{{ Request::routeIs('admin.' . $routeSlug . '.index') ? route('admin.' . $routeSlug . '.importView') : route('admin.users.importView') }}"
                        class="btn btn-outline-info btn-sm mb-0" data-toggle="tooltip" data-placement="top"
                        title="Import User Data">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        {{ __('Import') }}
                    </a>
                    <a href="{{ Request::routeIs('admin.' . $routeSlug . '.index') ? route('admin.' . $routeSlug . '.create') : route('admin.users.create') }}"
                        class="btn bg-gradient-primary btn-sm mb-0" type="button">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        {{ __('Add New User') }}
                    </a>
                </div>


            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('id', __('S. No.'))
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('name', __('Name'))
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('username', __('Username'))
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('email', __('Email'))
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('mobile', __('Mobile'))
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('role.name', __('Role'))
                            </th>
                            @if($routeId == config('constants.employee_role_id'))
                            @if (Config::get('auth_detail')['role_id']  ==  config('constants.admin_role_id'))
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('employees.employee_code', __('employee code'))
                            </th>
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('users.device_id', __('Device Id'))
                            </th>
                            @endif
                            @endif
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('users.created_at', __('Created at'))
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('status', __('Status'))
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                {{ __('Action') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="text-center" style="width:50px;">
                                    <p class="text-xs font-weight-bold mb-0">{{ ++$i }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0 user_info"
                                        data-user_uni_id="{{ $user->user_uni_id }}">{{ $user->name }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $user->username }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $user->email }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $user->mobile }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">
                                        {{ $user->role->name ?? '' }}</p>
                                </td>
                                @if($routeId == config('constants.employee_role_id'))
                                @if (Config::get('auth_detail')['role_id']  ==  config('constants.admin_role_id'))
                                <td class="text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $user->employee_code }}</p>
                                </td>
                                <td class="text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $user->device_id }}</span>
                                </td>
                                @endif
                                @endif
                                <td class="text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $user->created_at }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input updateStatus" type="checkbox" role="switch"
                                            {{ $user->status ? 'checked' : '' }} data-id="{{ $user->id }}">
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="dropdown ">
                                        <a href="javascript:;" class="btn bg-gradient-dark dropdown-toggle "
                                            data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                                            {{ __('Action') }}
                                        </a>
                                        <ul class="dropdown-menu " aria-labelledby="navbarDropdownMenuLink2">
                                            {{-- <li>
                                                            <a class="dropdown-item"
                                                                href="{{ Request::routeIs('admin.'.$routeSlug.'.index') ? route('admin.'.$routeSlug.'.show', $user->id) : route('admin.users.show', $user->id) }}"><i
                                        class="fas fa-edit"></i>show </a>
                                    </li> --}}
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ Request::routeIs('admin.' . $routeSlug . '.index') ? route('admin.' . $routeSlug . '.edit', $user->id) : route('admin.users.edit', $user->id) }}">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.userLocation', $user->id) }}">
                                                    <i class="fas fa-map"></i> Location
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.resetdeviceid', $user->id) }}">
                                                    <i class="fas fa-map"></i> Reset Device Id
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.user-update-password', $user->id) }}">
                                                    <i class="fas fa-edit"></i> Change Password
                                                </a>
                                            </li>

                                            <li>

                                                <form
                                                    action="{{ Request::routeIs('admin.' . $routeSlug . '.index') ? route('admin.' . $routeSlug . '.trash', $user->id) : route('admin.users.trash', $user->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="dropdown-item text-danger delete_confirm" title="Trash"
                                                        data-toggle="tooltip"><i
                                                            class="cursor-pointer text-danger fas fa-trash"></i>
                                                        <span style="position: relative;left:5px">Trash</span>
                                                    </button>
                                                </form>


                                                {{-- <form
                                                    action="{{ Request::routeIs('admin.' . $routeSlug . '.index') ? route('admin.' . $routeSlug . '.destroy', $user->id) : route('admin.users.destroy', $user->id) }}"
                                                    method="Post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                        class="dropdown-item text-danger delete_confirm" title="Detele">
                                                        <i class="cursor-pointer fas fa-trash"></i>
                                                        Delete
                                                    </button>
                                                </form> --}}
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->appends($filter)->links('pagination::bootstrap-5') }}

        </div>
    </div>
@endsection

@push('dashboard')
    <script>
        $(document).ready(function() {
            $('.updateStatus').on('change', function() {
                let ele = $(this);
                let _token = $('meta[name="csrf-token"]').attr('content');
                var status = $(this).prop('checked') == true ? 1 : 0;
                var id = $(this).data('id');
                //
                $.ajax({
                    url: "{{ route('admin.users.changeStatus') }}",
                    type: 'post',
                    data: {
                        _token: _token,
                        id: id,
                        status: status,
                    },
                    success: function(result) {
                        if (result.success) {
                            Swal.fire({
                                title: "{{ __('Success!') }}",
                                text: result.success,
                                icon: 'success',
                            })
                        } else {
                            if (result.error) {
                                ele.prop('checked', !status);
                                Swal.fire({
                                    title: "{{ __('Oops!') }}",
                                    text: result.error,
                                    icon: 'error',
                                })
                            }
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        ele.prop('checked', !status);
                        Swal.fire({
                            title: "{{ __('Oops!') }}",
                            text: "{{ __('Something went wrong. Please try again.') }}",
                            icon: 'error',
                        })
                    }

                });
            })
        })
    </script>
@endpush
