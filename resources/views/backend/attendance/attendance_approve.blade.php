@extends('layouts.admin.app')
@section('content')

<div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body text-sm">
          <label class="mt-2">
            <h5>{{__('Request')}}</h5>
          </label>
          <div class="mt-3"><strong class="text-dark">{{__('Authorised Person')}} :-</strong>&nbsp;
            {{ !empty($attend_data->author_name) ? $attend_data->author_name : '' }}
          </div>

          <div class="mt-2"><strong class="text-dark">{{__('User Name')}} :-</strong>&nbsp;
            {{ !empty($attend_data->user_name) ? $attend_data->user_name : '' }}
          </div>

          <div class="mt-2"><strong class="text-dark">{{__('Attendance Reason')}} :-</strong>&nbsp;
            {{ !empty($attend_data->name) ? $attend_data->name : '' }}
          </div>

          <div class="mt-2"><strong class="text-dark">{{__('Shift Name')}} :-</strong>&nbsp;
            {{ !empty($attend_data->shift_name) ? $attend_data->shift_name : '' }}
          </div>

          <div class="mt-2"><strong class="text-dark">{{__('Approve Date')}} :-</strong>&nbsp;
            {{ !empty($attend_data->created_at) ? $attend_data->attendance_date : '' }}
          </div>

          <div class="mt-2"><strong class="text-dark">{{__('Time In')}} :-</strong>&nbsp;
            {{ !empty($attend_data->from_time) ? $attend_data->from_time : '' }}
          </div>

          <div class="mt-2"><strong class="text-dark">{{__('Time Out')}} :-</strong>&nbsp;
            {{ !empty($attend_data->to_time) ? $attend_data->to_time : '' }}
          </div>

          <div class="mt-2"><strong class="text-dark">{{__('Request Hard Copy')}} :-</strong>&nbsp;</div>
          <div class="mt-2">
            <p class="gallery">
              <a href="{{ url(config('constants.request_hard_copy_image_path') . $attend_data->reqest_hard_copy) }}" data-fancybox="group" data-caption="This image has a caption 1">
                <img src="{{ url(config('constants.request_hard_copy_image_path') . $attend_data->request_hard_copy) }}" style="height: 50px; width: 50px;" alt="{{__('request_hard_copy')}}" title="request_hard_copy" />
              </a>
            </p>
          </div>

        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-body text-sm">
          <label class="mt-2">
            <h5>{{__('Approve')}}</h5>
          </label>
          @if($attend_data->status == 1)

          <div class="mt-3"><strong class="text-dark">Authorised Person :-</strong>&nbsp;
            {{ !empty($attend_data->author_name) ? $attend_data->author_name : '' }}
          </div>

          <div class="mt-2"><strong class="text-dark">User Name :-</strong>&nbsp;
            {{ !empty($attend_data->user_name) ? $attend_data->user_name : '' }}
          </div>
          <div class="mt-2"><strong class="text-dark">Approved By :-</strong>&nbsp;
            {{ !empty($attend_data->approve_name) ? $attend_data->approve_name : '' }}
          </div>

          <div class="mt-2"><strong class="text-dark">Shift Name :-</strong>&nbsp;
            {{ !empty($attend_data->shift_name) ? $attend_data->shift_name : '' }}
          </div>

          <div class="mt-2"><strong class="text-dark">Approved Date :-</strong>&nbsp;
            {{ !empty($attend_data->approve_date) ? $attend_data->approve_date : '' }}
          </div>

          @endif
        </div>
      </div>
    </div>

  </div>
</div>

<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="multisteps-form mb-5">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body text-sm">
              <form method="post" action="{{ route('admin.attendence.attendance_approve.approveStatus') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <input type="hidden" name="id" value="{{$id}}" class="form-control" />
                </div>
                <div class="col-md-12 mt-4">
                  <label class="mt-2">
                    <h5>{{__('Attendance')}}</h5>
                  </label>
                  <div class="mt-4" style="font-size: 13px;font-weight: bold">
                    <input type="radio" id="pending" name="status" value="1" {{ old('status', $attend_data->status) == 1 ? 'checked' : '' }}> <label for="pending">{{__('Approved')}}</label>
                    <input type="radio" id="approve" name="status" value="0" {{ old('status',  $attend_data->status) == 0 ? 'checked' : '' }}> <label for="approve">{{__('Pending')}}</label>
                    <input type="radio" id="cancel" name="status" value="2" {{ old('status', $attend_data->status) == 2 ? 'checked' : '' }}> <label for="cancel">{{__('Cancelled')}}</label>
                  </div>
                </div>

                <input type="hidden" class="form-control" name="approved_by" id="approved_by" aria-label="approved_by  example input" aria-describedby="approved_by" value="{{$attend_data->admin_id }}">

                <div class="col-md-6">
                  <label for="approve_remark" class="form-label mt-4">{{ __('Approve Remark') }}</label>
                  <div class="">
                    <input type="text" class="form-control" placeholder="{{ __('Approve Remark') }}" name="approve_remark" id="approve_remark" value="{{ old('approve_remark') }}">
                    @error('approve_remark')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                  </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                  <a href="{{ route('admin.attendence.attendance.index') }}" type="button" name="button" class="btn btn-light m-0">{{ __('BACK TO LIST') }}</a>
                  <button type="submit" name="button" class="btn bg-gradient-primary m-0 ms-2">
                    {{ __('Submit') }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('dashboard')
 
 @endpush
  @endsection