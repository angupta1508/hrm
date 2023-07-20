@extends('layouts.admin.app')
@section('content')
<div class="card card-default">
    <div class="card-header pb-0">
        <div class="d-flex flex-row justify-content-between">
            <div>
                <h5 class="mb-0">
                    {{ __('Add Manual Attendance') }}
                </h5>
            </div>
        </div>
    </div> 
    <div class="card-body">
        <form action="{{ route('admin.attendence.manualAttendance.store') }}" method="POST" enctype='multipart/form-data'>
            @csrf
            <div class="row">
              <div class="col-md-6">
                    <label for="state_id" class="form-label mt-4">{{__('User')}}</label>
                    <div>
                        {{ Form::select('user_id', ['name' => __('Select User')] + $user_list, old('user_id'), ['class' => 'form-select select2 User_dropdown']) }}
                        @error('user_id')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="attendance_reason_id" class="form-label mt-4">{{ __('Attendance Reason') }}</label>
                    <div class="">
                        <select class="form-select  select2" id="attendance_reason_id" name="attendance_reason_id" aria-label="attendance_reason_id" aria-describedby="attendance_reason_id">
                            <option value="">{{__('Please Select Attendance Reason')}}</option>
                            @foreach($attendance as $attend)
                            <option value="{{$attend->id}}" {{ (collect(old('attendance_reason_id'))->contains($attend->id)) ? 'selected':'' }}>{{ $attend->name }}</option>
                            @endforeach
                        </select>
                        @error('attendance_reason_id')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="attendance_date" class="form-label mt-4">{{ __('Request Date') }}</label>
                    <div class="">
                        <input type="date" class="form-control" placeholder="{{ __('Request Date') }}" name="attendance_date" id="attendance_date" value="{{ old('attendance_date') }}">
                        @error('attendance_date')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="from_time" class="form-label mt-4">{{ __('Time In') }}</label>
                    <div class="">
                        <input type="datetime-local" class="form-control" placeholder="{{ __('From Time') }}" name="from_time" id="from_time" value="{{ old('from_time') }}">
                        @error('from_time')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="to_time" class="form-label mt-4">{{ __('Time Out') }}</label>
                    <div class="">
                        <input type="datetime-local" class="form-control" placeholder="{{ __('To Time') }}" name="to_time" id="to_time" value="{{ old('to_time') }}">
                        @error('to_time')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="request_remark" class="form-label mt-4">{{ __('Request Remark') }}</label>
                    <div class="">
                        <input type="text" class="form-control" placeholder="{{ __('Request Remark') }}" name="request_remark" id="request_remark" value="{{ old('request_remark') }}">
                        @error('request_remark')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="request_hard_copy" class="form-label mt-4">{{ __('Request Hard Copy') }}</label>

                    <div>
                        <input type="file" onchange="previewImage('.filImageInput', '.diplayImage')" class="form-control filImageInput" name="request_hard_copy" id="request_hard_copy">
                        @error('request_hard_copy')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-3">
                        <div class="avatar">
                            <img class="w-100 border-radius-sn shadow-sm diplayImage" src="{{ ImageShow(config('constants.request_hard_copy_image_path'), '') }}">
                        </div>
                    </div>
                </div>


                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.attendence.manualAttendance.index') }}" type="button" name="button" class="btn btn-light m-0">{{ __('BACK TO LIST') }}</a>
                    <button type="submit" name="button" class="btn bg-gradient-primary m-0 ms-2">
                        {{ __('Create') }}
                    </button>
                </div>
        </form>
    </div>
</div>
@push('dashboard')



@endpush
@endsection