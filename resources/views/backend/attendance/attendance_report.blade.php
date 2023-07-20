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
                    <form action="{{ route('admin.attendence.attendance.attendanceReport') }}" method="GET">
                        @csrf
                        <div class="border">
                            <div class="d-flex flex-row align-content-between flex-wrap">
                                <!-- <div class="p-2 flex-fill w-100">
                                    <input type="text" id="search" name="search" class="form-control"
                                        value="{{ !empty($filter['search']) ? $filter['search'] : '' }}" autocomplete="off"
                                        placeholder="{{ __('Search By User Name, Employee Code,Requet Remark') }}">
                                </div> -->
                                <div class="p-2 flex-fill">
                            {{ Form::select('user_id', ['' => __('Select By Employee')] + $user_list, old('user_id',!empty($filter['user_id']) ? $filter['user_id'] : ''),  ['class' => 'form-select select2']) }}
                            @error('user_id')
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
                                    {{ Form::select('company_id', ['' => __('Select Company')] + $companies, old('company_id', !empty($filter['company_id']) ? $filter['company_id'] : ''), ['class' => 'form-select select2']) }}
                                    @error('company_id')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="p-2 flex-fill">
                                    <input type="text" name="start_date" class="form-control datepicker"
                                        autocomplete="off"
                                        value="{{ !empty($filter['start_date']) ? $filter['start_date'] : '' }}"
                                        placeholder="{{__('From Time')}}" />
                                </div>
                                <div class="p-2 flex-fill ">
                                    <input type="text" name="end_date" class="form-control datepicker" autocomplete="off"
                                        value="{{ !empty($filter['end_date']) ? $filter['end_date'] : '' }}"
                                        placeholder="{{__('To Time')}}" />
                                </div>
                                <div class="p-2 flex-fill">
                                    {{ Form::select('status', ['' => __('Select Attendance Status')] + getListTranslate(config('constants.default_attendance_status')), isset($filter['status']) ? $filter['status'] : '', ['class' => 'form-select']) }}
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
                    <h5 class="mb-0">{{ __('Attendance Report') }}</h5>
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
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('user_id', __('user'))
                            </th>

                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('from_time', __('from time'))
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('to_time', __('to time'))
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('working_hours', __('working hours'))
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('overtime', __('overtime'))
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('early_in', __('early in'))
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('late_out', __('late out'))
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('late_in', __('late in'))
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('early_out', __('early out'))
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('request_remark', __('request remark'))
                            </th>

                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('description', __('description'))
                            </th>

                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('attendance_status', __('Attendance Status'))
                            </th>

                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('created_at', __('Date'))
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendance as $attend)
                            <tr>
                                <td class="text-center" style="width:50px;">
                                    <p class="text-xs font-weight-bold mb-0">{{ ++$i }}</p>
                                </td>

                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">
                                        {{ $attend->name }}
                                        <!-- {{ !empty($attend->attendance->name) ? $attend->attendance->name : '' }} -->
                                    </p>
                                </td>

                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $attend->from_time }}</p>
                                </td>

                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $attend->to_time }}</p>
                                </td>

                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $attend->working_hours }}</p>
                                </td>

                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $attend->overtime }}</p>
                                </td>

                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $attend->early_in }}</p>
                                </td>

                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $attend->late_out }}</p>
                                </td>

                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $attend->late_in }}</p>
                                </td>

                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $attend->early_out }}</p>
                                </td>

                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $attend->request_remark }}</p>
                                </td>

                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $attend->description }}</p>
                                </td>

                                <td class="text-capitalize text-center">
                                    <p class="text-xs font-weight-bold mb-0">{{ $attend->attendance_status }}</p>
                                </td>

                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $attend->created_at }}</p>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>


                </table>
            </div>
            {{ $attendance->appends($filter)->links('pagination::bootstrap-5') }}
        </div>
    </div>

@endsection