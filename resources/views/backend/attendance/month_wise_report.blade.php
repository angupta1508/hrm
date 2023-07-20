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
                    <form action="{{ route('admin.attendence.attendance.monthWiseReport') }}" method="GET">
                        @csrf
                        <div class="border">
                            <div class="d-flex flex-row align-content-between flex-wrap">
                                <div class="p-2 flex-fill">
                                    <input type="text" id="search" name="search" class="form-control"
                                        value="{{ !empty($filter['search']) ? $filter['search'] : '' }}" autocomplete="off"
                                        placeholder="{{ __('Search By User Name, Employee Code,Requet Remark') }}">
                                </div>
                                <div class="p-2 flex-fill">
                            {{ Form::select('user_id', ['' => __('Select By Employee')] + $user_list, old('user_id',!empty($filter['user_id']) ? $filter['user_id'] : ''),  ['class' => 'form-select select2']) }}
                            @error('user_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                                <div class="p-2 flex-fill">
                                    <input type="month" name="month" class="form-control" autocomplete="off"
                                        value="{{ !empty($filter['month']) ? $filter['month'] : '' }}"
                                        placeholder="{{__('Select Month')}}" />
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
                <table class="table align-items-center mb-0 table-hover">
                    <thead>

                        @if (!empty($attendanceArray))
                            <tr>
                                @foreach ($attendanceArray[0] as $key => $value)
                                    @if (!is_array($value))
                                        <th rowspan="2"
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                            @sortablelink($key, __(ucwords(str_replace('_', ' ', $key))))
                                        </th>
                                    @else
                                        @foreach ($value as $k => $val)
                                            <th colspan="4"
                                                class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7 ">
                                                {{ $k }}
                                            </th>
                                        @endforeach

                            <tr>
                                @php
                                $i = 0; @endphp
                                @foreach ($value as $k => $val)
                                    @php
                                        $i++;
                                        $hh = $i % 2;
                                        if ($hh == 0) {
                                            $class = 'table-success';
                                        } else {
                                            $class = 'table-danger';
                                        }
                                    @endphp
                                    <td
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 {{ $class }}">
                                        {{ __('Time In') }}</td>
                                    <td
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 {{ $class }}">
                                        {{ __('Time Out') }}</td>
                                    <td
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 {{ $class }}">
                                        {{ __('Working Hours') }}</td>
                                    <td
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 {{ $class }}">
                                        {{ __('Status') }}</td>
                                @endforeach
                            </tr>
                        @endif
                        @endforeach
                        @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($attendanceArray))
                        @foreach ($attendanceArray as $key => $atten)
                            <tr>
                                @foreach ($atten as $k => $value)
                                    @if (is_array($value))
                                        @php $i = 0; @endphp
                                        @foreach ($value as $k1 => $val)
                                            @php
                                                $i++;
                                                $hh = $i % 2;
                                                if ($hh == 0) {
                                                    $class = 'table-success';
                                                } else {
                                                    $class = 'table-danger';
                                                }
                                            @endphp
                                            <td class="text-capitalize {{ $class }}">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ !empty($val['from_time']) ? $val['from_time'] : '' }}
                                                </p>
                                            </td>
                                            <td class="text-capitalize {{ $class }}">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ !empty($val['to_time']) ? $val['to_time'] : '' }}
                                                </p>
                                            </td>
                                            <td class="text-capitalize {{ $class }}">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ !empty($val['working_hours']) ? $val['working_hours'] : '' }}
                                                </p>
                                            </td>
                                            <td class="text-capitalize {{ $class }}">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ !empty($val['attendance_status']) ? $val['attendance_status'] : '' }}
                                                </p>
                                            </td>
                                        @endforeach
                                    @else
                                        <td class="text-center" style="width:50px;">
                                            <p class="text-xs font-weight-bold mb-0">{{ $value }}</p>
                                        </td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            {{ $users->appends($filter)->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
