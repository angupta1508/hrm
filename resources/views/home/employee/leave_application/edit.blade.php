@extends('layouts.front-user.app')
@section('content')

<div class="maindashatten">
    <div class="modal-content">

        <div class="card-body">
            <form action="{{ route('employe-leave.update',$leave_application) }}" method="POST" enctype='multipart/form-data'>
            @method('PUT')
            @csrf
                <div class="row">

                    <div class="row gap-3 flex-wrap my-5">
                        <select class="col bgthemelight rounded-5 border py-3 px-3 mx-auto secondclr fw-600" id="leave_type_id" name="leave_type_id" aria-label="leave_type_id" aria-describedby="leave_type_id">
                            <option value="">Please Select Leave Type</option>
                            @foreach($leave_type as $attend)
                            <option value="{{$attend->id}}" {{ old('leave_type_id', $leave_application->leave_type_id) == $attend->id ? 'selected':'' }}>{{ $attend->leave_type }}</option>
                            @endforeach
                        </select>
                        @error('leave_type_id')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror


                    
                        <input type="file" onchange="previewImage('.filImageInput', '.diplayImage')" class="col bgthemelight rounded-5 border py-3 px-3 mx-auto secondclr fw-600" name="request_hard_copy" id="request_hard_copy">
                        @error('request_hard_copy')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                
                    <div class="mt-3">
                        <div class="avatar">
                            <img class="rounded-circle" style="width: 45px; height: 45px;" src="{{ ImageShow(config('constants.leave_request_hard_copy_image_path'), 
                                 $leave_application->request_hard_copy, 'small') }}">
                        </div>
                    </div>
                   </div>

                   <div class="row gap-3 flex-wrap my-3">
                        <label for="request_start_date" class="form-label mt-2">From Date</label>
                        <input type="date" class="col bgthemelight rounded-5 border secondclr fw-600" placeholder="{{ __('Request Start Date') }}" name="request_start_date" id="request_start_date" value="{{ old('request_start_date',$leave_application->request_start_date) }}">
                        @error('request_start_date')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror



                        <select type="Number" class="col bgthemelight rounded-5 border py-3 px-3 mx-auto secondclr fw-600" id="request_leave_type_out_id" name="request_leave_type_out_id" aria-label="request_leave_type_out_id" aria-describedby="request_leave_type_out_id">
                            <option value="">Please Select Leave Type Out</option>
                            @foreach($leave_in_out as $attend)
                            <option value="{{$attend->id}}" {{ old('request_leave_type_out_id', $leave_application->request_leave_type_out_id) == $attend->id ? 'selected':'' }}>{{ $attend->name  }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="row gap-3 flex-wrap my-3">
                        <label for="request_end_date" class="form-label mt-2">To Date</label>
                        <input type="date" class="col bgthemelight rounded-5 border secondclr fw-600" placeholder="{{ __('Request End Date') }}" name="request_end_date" id="request_end_date" value="{{ old('request_end_date',$leave_application->request_end_date) }}">
                        @error('request_end_date')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror

                        <select type="Number" class="col bgthemelight rounded-5 border py-3 px-3 mx-auto secondclr fw-600" id="request_leave_type_in_id" name="request_leave_type_in_id" aria-label="request_leave_type_in_id" aria-describedby="request_leave_type_in_id">
                            <option value="">Please Select Leave Type In</option>
                            @foreach($leave_in_out as $attend)
                            <option value="{{$attend->id}}" {{ old('request_leave_type_in_id', $leave_application->request_leave_type_in_id) == $attend->id ? 'selected':'' }}>{{ $attend->name  }}</option>
                            @endforeach
                        </select>
                        @error('request_leave_type_in_id')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="row gap-3 flex-wrap my-5">
                        <input type="text" class="col bgthemelight rounded-4 border py-3 px-3 mx-auto secondclr fw-600" placeholder="Request Remark" name="request_remark" id="request_remark" value="{{ old('request_remark',$leave_application->request_remark) }}">
                        @error('request_remark')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror

                        <input type="number" name="request_day" id="sum" class="col bgthemelight rounded-4 border py-3 px-3 mx-auto secondclr fw-600" readonly value="{{ old('request_day',$leave_application->request_day) }}" />
                        @error('request_day')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror

                    </div>

                    <div class="approvemanger my-1">
                        <p class="subhead themeclr fw-semibold my-4">Approval Manager </p>
                        <div class="d-flex flex-row">
                                <img class="rounded-circle" style="width: 45px; height: 45px;" src="{{ ImageShow(config('constants.user_image_path'), $author->profile_image, 'small') }}" alt="user" data-toggle="tooltip" title="" data-original-title="Wildan Ahdian">

                            <div class="d-flex flex-column mx-5">
                                <p class="subhead fw-semibold mb-0">{{ $author->name }}</p>
                                <p class="fs-6">HOD OF Synilogic Tech</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn w-50 p-3 subhead rounded-5 bgtheme mb-5 button text-light mx-auto">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

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


@endsection('content')