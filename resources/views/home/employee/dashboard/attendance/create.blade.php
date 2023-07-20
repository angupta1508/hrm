@extends('layouts.front-user.app')
@section('content')
<!-- main screen -->
<main class="maindashatten">

    <div class="card col-11 mx-auto my-3 py-3 px-5">
        <p class="text-center subhead themeclr">Manual Attendance Request</p>

        <form action="{{ route('attendance-regularise.store') }}" method="POST" enctype='multipart/form-data'>
            @csrf
            <div class="row">

                <div class="row  flex-wrap my-3">
                    <label for="" class="fw-semibold">Attendance date</label>
                    <input type="date" class="col py-3 bgthemelight rounded-5 border secondclr fw-600" placeholder="{{ __('Request Date') }}" name="attendance_date" id="attendance_date" value="{{ old('attendance_date') }}">
                    @error('attendance_date')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <div class="row  flex-wrap my-3">
                    <label for="" class="fw-semibold">Punch In Time</label>
                    <input type="datetime-local" class="col py-3 bgthemelight rounded-5 border secondclr fw-600" placeholder="{{ __('From Time') }}" name="from_time" id="from_time" value="{{ old('from_time') }}" >
                    @error('from_time')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <div class="row  flex-wrap my-3">
                    <label for="" class="fw-semibold">Punch Out Time</label>
                    <input type="datetime-local" class="col py-3 bgthemelight rounded-5 border secondclr fw-600" placeholder="{{ __('To Time') }}" name="to_time" id="to_time" value="{{ old('to_time') }}" >
                    @error('to_time')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="row  flex-wrap my-3">
                    <label for="" class="fw-semibold">Attendance Reason</label>
                    <select class="col py-3 bgthemelight rounded-5 border secondclr fw-600" id="attendance_reason_id" name="attendance_reason_id" aria-label="attendance_reason_id" aria-describedby="attendance_reason_id">
                        <option value="">Please Select Attendance Reason</option>
                        @foreach ($reason as $attend)
                        <option value="{{ $attend->id }}" {{ collect(old('attendance_reason_id'))->contains($attend->id) ? 'selected' : '' }}>
                            {{ $attend->name }}
                        </option>
                        @endforeach
                        @error('attendance_reason_id')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror

                    </select>

                </div>

                <div class="row  flex-wrap my-3">
                    <label for="" class="fw-semibold">Remark</label>
                    <input type="text" class="col bgthemelight rounded-4 border py-3 px-3 mx-auto secondclr fw-600" placeholder="{{ __('Request Remark') }}" name="request_remark" id="request_remark" value="{{ old('request_remark') }}">
                    @error('request_remark')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>


                <div class="row  flex-wrap my-3">
                    <label for="" class="fw-semibold">Request Hard Copy</label>
                    <input type="file" onchange="previewImage('.filImageInput', '.diplayImage')" class="col bgthemelight rounded-4 border py-3 px-3 mx-auto secondclr fw-600 filImageInput" name="request_hard_copy" id="request_hard_copy">
                    @php
                    $imagUrl = url(config('constants.default_image_path'));
                    @endphp
                    @error('request_hard_copy')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                    <div class="mt-3">
                        <div class="avatar">
                            <img src="{{ $imagUrl }}" class="rounded-circle diplayImage" style="width: 45px; height: 45px;" id="wizardPicturePreview">
                        </div>
                    </div>
                </div>


                <button type="submit" class="btn btn-primary w-50 p-3 subhead rounded-5 bgtheme mb-0 button text-light mx-auto submitbtn">
                    Submit</button>

            </div>
        </form>

    </div>
</main>


<script>
const dateInput = document.getElementById('attendance_date');
const dateTimeInput1 = document.getElementById('from_time');
const dateTimeInput2 = document.getElementById('to_time');

dateInput.addEventListener('change', function() {

  const selectedDate = dateInput.value;
  const currentTime = new Date().toLocaleTimeString('en-CA', { hour12: false, hour: '2-digit', minute: '2-digit' });
  const selectedDateTime = selectedDate + 'T' + currentTime;

  dateTimeInput1.value = selectedDateTime;
  dateTimeInput2.value = selectedDateTime;
});
  
</script>


@endsection