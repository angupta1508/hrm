@extends('layouts.admin.app')
@section('content')

<div class="card card-default">
    <div class="card-header pb-0">
        <div class="d-flex flex-row justify-content-between">
            <div>
                <h5 class="mb-0">
                    {{ __('Edit Leave Application') }}
                </h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.leave.leaves.update', $leave_application) }}" method="POST" enctype='multipart/form-data'>
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <label for="state_id" class="form-label mt-4">{{__('User')}}</label>
                    <div>
                        {{ Form::select('user_id', ['name' => 'Select User'] + $user_list, old('user_id', $leave_application->user_id), ['class' => 'form-select select2 User_dropdown']) }}
                        @error('user_id')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="leave_type_id" class="form-label mt-4">{{ __('Leave Type') }}</label>
                    <div class="">
                        <select class="form-select  select2" id="leave_type_id" name="leave_type_id" aria-label="leave_type_id" aria-describedby="leave_type_id">
                            <option value="">{{__('Please Select Leave Type')}}</option>
                            @foreach($leave_type as $attend)
                            <option value="{{$attend->id}}" {{ old('leave_type_id', $leave_application->leave_type_id) == $attend->id ? 'selected':'' }}>{{ $attend->leave_type }}</option>
                            @endforeach
                        </select>
                        @error('leave_type_id')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="request_start_date" class="form-label mt-4">{{ __('From Date') }}</label>
                    <div class="">
                        <input type="date" class="form-control" placeholder="{{ __('Request Start Date') }}" name="request_start_date" id="request_start_date" value="{{ old('request_start_date',$leave_application->request_start_date) }}">
                        @error('request_start_date')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="request_leave_type_out_id" class="form-label mt-4">{{ __('Request Leave Type Out') }}</label>
                    <div class="">
                        <select class="form-select  select2" id="request_leave_type_out_id" name="request_leave_type_out_id" aria-label="request_leave_type_out_id" aria-describedby="request_leave_type_out_id">
                            <option value="">{{__('Please Select Leave Type')}}</option>
                            @foreach($leave_in_out as $attend)
                            <option value="{{$attend->id}}" {{ old('request_leave_type_out_id', $leave_application->request_leave_type_out_id) == $attend->id ? 'selected':'' }}>{{ $attend->name  }}</option>
                            @endforeach
                        </select>
                        @error('request_leave_type_out_id')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <div class="col-md-6">
                    <label for="request_end_date" class="form-label mt-4">{{ __('To Date') }}</label>
                    <div class="">
                        <input type="date" class="form-control" placeholder="{{ __('Request End Date') }}" name="request_end_date" id="request_end_date" value="{{ old('request_end_date',$leave_application->request_end_date) }}">
                        @error('request_end_date')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="request_leave_type_in_id" class="mt-4">{{ __('Request Leave Type In') }}</label>
                    <div class="">
                        <select class="form-select  select2" id="request_leave_type_in_id" name="request_leave_type_in_id" aria-label="request_leave_type_in_id" aria-describedby="request_leave_type_in_id">
                            <option value="">{{__('Please Select Leave Type')}}</option>
                            @foreach($leave_in_out as $leave)
                            <option value="{{$leave->id}}" {{ old('request_leave_type_in_id', $leave_application->request_leave_type_in_id) == $leave->id ? 'selected':'' }}>{{ $leave->name  }}</option>
                            @endforeach
                        </select>
                        @error('request_leave_type_in_id')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <div class="col-md-6">
                    <label for="request_remark" class="form-label mt-4">{{ __('Request Remark') }}</label>
                    <div class="">
                        <input type="text" class="form-control" placeholder="{{ __('Request Remark') }}" name="request_remark" id="request_remark" value="{{ old('request_remark',$leave_application->request_remark) }}">
                        @error('request_remark')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label mt-4" for="sum">{{ __('Request Day') }}</label>
                    <div class="">
                        <input type="number" name="request_day" id="sum" class="form-control" readonly value="{{ old('request_day',$leave_application->request_day) }}" />
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
                            <img class="w-100 border-radius-sn shadow-sm diplayImage" src="{{ ImageShow(config('constants.leave_request_hard_copy_image_path'), 
                                 $leave_application->request_hard_copy, 'small') }}">
                        </div>
                    </div>
                </div>


            </div>
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.leave.leaves.index') }}" type="button" name="button" class="btn btn-light m-0">{{ __('BACK TO LIST') }}</a>
                <button type="submit" name="button" class="btn bg-gradient-primary m-0 ms-2">
                    {{ __('Edit') }}
                </button>
            </div>
        </form>
    </div>
</div>
@push('dashboard')

<script>
    const fromDateInput = document.getElementById("request_start_date");
    const toDateInput = document.getElementById("request_end_date");

    fromDateInput.addEventListener("change", () => {
        const fromDate = fromDateInput.value;
        toDateInput.min = fromDate; // set the min attribute to the selected from date
    });

    $(document).ready(function() {

        $('#request_leave_type_out_id, #request_leave_type_in_id,#request_start_date,#request_end_date').change(function() {

            var select1Value = parseFloat($('#request_leave_type_out_id').val());
            var select2Value = parseFloat($('#request_leave_type_in_id').val());
            var debutDate = Date.parse($("#request_start_date").val());
            var finDate = Date.parse($("#request_end_date").val());


            if (select1Value === 1) {
                var leaveOne = 0;

            } else if (select1Value === 2) {
                var leaveOne = -.5;

            } else if (select1Value === 3) {
                var leaveOne = -0.5;
            }

            if (select2Value === 1) {
                var leaveTwo = 0;
            } else if (select2Value === 2) {
                var leaveTwo = -.5;
            } else if (select2Value === 3) {
                var leaveTwo = -0.5;
            }

            var sum = leaveOne + leaveTwo + 1 + Math.abs(finDate - debutDate) / (1000 * 60 * 60 * 24);
            $('#sum').html('Leave Days : ').val(sum);

        });
    });
</script>
@endpush
@endsection