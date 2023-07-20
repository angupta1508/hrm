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
                <form action="{{ route('admin.attendence.attendance.missPunchReport') }}"
                    method="GET">
                    @csrf
                    <div class="border">
                        <div class="d-flex flex-row align-content-between flex-wrap">
                            <div class="p-2 flex-fill">
                                {{ Form::select('company_id', ['' => __('Select Company')] + $companies, old('company_id', !empty($filter['company_id']) ? $filter['company_id'] : ''), ['class' => 'form-select select2']) }}
                                @error('company_id')
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
                                {{ Form::select('shift_id', ['' => __('Select Shift')] + $shifts, old('shift_id', !empty($filter['shift_id']) ? $filter['shift_id'] : ''), ['class' => 'form-select select2']) }}
                                @error('shift_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="p-2 flex-fill">
                                    {{ Form::select('department_id', ['' => __('Select Department')] + $departments, old('department_id',!empty($filter['department_id']) ? $filter['department_id'] : ''),   ['class' => 'form-select select2']) }}
                                    @error('department_id')
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
                                    {{ Form::select('user_id', ['' => __('Select Employee')] + $user_list, old('user_id', !empty($filter['user_id']) ? $filter['user_id'] : ''), ['class' => 'form-select select2']) }}
                                    @error('user_id')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="p-2 flex-fill">
                                <input type="text" name="request_date" class="form-control datepicker" autocomplete="off" value="{{ !empty($filter['request_date']) ? $filter['request_date'] : '' }}" placeholder="{{__('Search From Attendance Date')}}" />
                            </div>
                            <div class="p-2 flex-fill">
                                <button type="submit" name="submit" class="btn btn-primary shadow-primary mb-0 button">
                                    {{ __('Filter') }}
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
                <h5 class="mb-0">{{ __('Miss Punch Report') }}</h5>
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
                                @sortablelink('user_id', __('Miss Punch user'))
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('attendance_date', __('Attendance Date'))
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('from_time', __('in time'))
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('to_time', __('out time'))
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($misspunchreport as $misspunch)
                        <tr>
                            <td class="text-center" style="width:50px;">
                                <p class="text-xs font-weight-bold mb-0">{{ ++$i }}</p>
                            </td>

                            <td class="text-capitalize">
                                <p class="text-xs font-weight-bold mb-0">
                                    {{ $misspunch->name }}
                                    <!-- {{ !empty($misspunch->attendance->name) ? $misspunch->attendance->name : '' }} -->
                                </p>
                            </td>
                            <td class="text-capitalize">
                                <p class="text-xs font-weight-bold mb-0">{{ $misspunch->attendance_date }}</p>
                            </td>
                            <td class="text-capitalize">
                                <p class="text-xs font-weight-bold mb-0">{{ $misspunch->from_time }}</p>
                            </td>

                            <td class="text-capitalize">
                                <p class="text-xs font-weight-bold mb-0">{{ $misspunch->to_time }}</p>
                            </td>



                        </tr>
                    @endforeach
                </tbody>


            </table>
        </div>
        {{ $misspunchreport->appends($filter)->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

