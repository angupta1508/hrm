@extends('layouts.front-user.app')
@section('content')

<!-- main screen -->
<main class="maindashatten">
    <section class="d-none d-sm-block">
        <div class="search  mx-auto d-flex mb-0 align-items-center ">
            <!-- <span class="fa fa-search"></span> -->
            <input placeholder="Type your Keywords to search..." class="rounded-pill px-4 py-4  mb-0">

            <div class="d-flex border border-2 bgtheme ms-auto  settingicon fs-2 text-light">
                Leave Approvel

            </div>
        </div>
    </section>
    <p class="fw-semibold fs-1 text-center d-block d-sm-none">Leave Approvel</p>
    <div class="row mx-3">


        <div class="container-fluid py-2 text-sm">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <label class="mt-2 text-sm">
                                <h5>Request</h5>
                            </label>

                            <div class=""><strong class="text-dark">Leave Type :-</strong>&nbsp;
                                {{ !empty($leave_data->leave_type) ? $leave_data->leave_type : '' }}
                            </div>

                            <div class="mt-2"><strong class="text-dark">Approve Date :-</strong>&nbsp;
                                {{ !empty($leave_data->approve_date) ? $leave_data->approve_date : '' }}
                            </div>

                            <div class="mt-2"><strong class="text-dark">From Date :-</strong>&nbsp;
                                {{ !empty($leave_data->request_start_date) ? $leave_data->request_start_date : '' }}
                                @if ($leave_data->request_out_name)
                                [{{ $leave_data->request_out_name }}]
                                @endif
                            </div>

                            <div class="mt-2"><strong class="text-dark">To Date :-</strong>&nbsp;
                                {{ !empty($leave_data->request_end_date) ? $leave_data->request_end_date : '' }}
                                @if ($leave_data->request_in_name)
                                [{{ $leave_data->request_in_name }}]
                                @endif
                            </div>

                            <div class="mt-2"><strong class="text-dark">Request Days :-</strong>&nbsp;
                                {{ !empty($leave_data->request_day) ? $leave_data->request_day : '' }}
                            </div>


                            <div class="mt-2"><strong class="text-dark">Request Hard Copy :-</strong>&nbsp;
                                <div class="mt-2">
                                    <p class="gallery">
                                        <a href="{{ url(config('constants.leave_request_hard_copy_image_path') . $leave_data->request_hard_copy) }}" data-fancybox="group" data-caption="This image has a caption 1">
                                            <img src="{{ url(config('constants.leave_request_hard_copy_image_path') . $leave_data->request_hard_copy) }}" style="height: 50px; width: 50px;" alt="request_hard_copy" title="request_hard_copy" />
                                        </a>
                                    </p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-sm">
                    <div class="card">
                        <div class="card-body text-sm">
                            <label class="mt-2 text-sm">
                                <h5>Approve</h5>
                            </label>
                            @if($leave_data->status == 1)

                            <div class=""><strong class="text-dark">Authorised Person :-</strong>&nbsp;
                                {{ !empty($leave_data->authorise_name) ? $leave_data->authorise_name : '' }}
                            </div>

                            <div class="mt-2"><strong class="text-dark">User Name :-</strong>&nbsp;
                                {{ !empty($leave_data->user_name) ? $leave_data->user_name : '' }}
                            </div>

                            <div class="mt-2"><strong class="text-dark">Approved By :-</strong>&nbsp;
                                {{ !empty($leave_data->authorise_name) ? $leave_data->authorise_name : '' }}
                            </div>

                            <div class="mt-2"><strong class="text-dark">Approved Date :-</strong>&nbsp;
                                {{ !empty($leave_data->approve_date) ? $leave_data->approve_date : '' }}
                            </div>

                            <div class="mt-2"><strong class="text-dark">From Date :-</strong>&nbsp;
                                {{ !empty($leave_data->approve_start_date) ? $leave_data->approve_start_date : '' }}
                                @if ($leave_data->approve_out_name)
                                [{{ $leave_data->approve_out_name }}]
                                @endif
                            </div>

                            <div class="mt-2"><strong class="text-dark">To Date :-</strong>&nbsp;
                                {{ !empty($leave_data->approve_end_date) ? $leave_data->approve_end_date : '' }}
                                @if ($leave_data->approve_in_name)
                                [{{ $leave_data->approve_in_name }}]
                                @endif
                            </div>

                            <div class="mt-2"><strong class="text-dark">Approved Remark :-</strong>&nbsp;
                                {{ !empty($leave_data->approve_remark) ? $leave_data->approve_remark : '' }}
                            </div>

                            <div class="mt-2"><strong class="text-dark">Approved Days :-</strong>&nbsp;
                                {{ !empty($leave_data->request_day) ? $leave_data->approve_day : '' }}
                            </div>

                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="container-fluid py-4 text-sm">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <label class="mt-2" style="font-size: 18px;font-weight: bold">
                                Authorised Person Detail
                            </label>

                            <div class="mt-2"><strong class="text-dark"> Name :-</strong>&nbsp;
                                {{ !empty($leave_data->authorise_name) ? $leave_data->authorise_name : '' }}
                            </div>

                            <div class="mt-2"><strong class="text-dark">Email :-</strong>&nbsp;
                                {{ !empty($leave_data->email) ? $leave_data->email : '' }}
                            </div>

                            <div class="mt-2"><strong class="text-dark">Mobile No :-</strong>&nbsp;
                                {{ !empty($leave_data->mobile) ? $leave_data->mobile : '' }}
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-sm">
                            <label class="mt-2" style="font-size: 18px;font-weight: bold">
                                Employees Detail
                            </label>

                            <div class="mt-2"><strong class="text-dark"> Employee Code :-</strong>&nbsp;
                                {{ !empty($leave_data->user_name) ? $leave_data->employee_code : '' }}
                            </div>

                            <div class="mt-2"><strong class="text-dark">Company Id :-</strong>&nbsp;
                                {{ !empty($leave_data->user_name) ? $leave_data->company_id : '' }}
                            </div>

                            <div class="mt-2"><strong class="text-dark">Machine Code :-</strong>&nbsp;
                                {{ !empty($leave_data->user_name) ? $leave_data->machine_code : '' }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="container-fluid text-sm">
            <div class="row">
                <div class="col-12">
                    <div class="multisteps-form mb-5">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post" action="{{ route('approveLeave', $leave_data) }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group">
                                                <input type="hidden" name="id" value="{{$id}}" class="form-control" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="mt-2">
                                                    <h5>Leave</h5>
                                                </label>
                                                <div class="mt-4" style="font-size: 13px;font-weight: bold">
                                                    <input type="radio" id="pending" name="status" value="0" {{ old('status', $leave_data->status) == 0 ? 'checked' : '' }}> <label for="pending">Pending</label>
                                                    <input type="radio" id="approve" name="status" value="1" {{ old('status',  $leave_data->status) == 1 ? 'checked' : '' }}> <label for="approve">Approved</label>
                                                    <input type="radio" id="cancel" name="status" value="2" {{ old('status', $leave_data->status) == 2 ? 'checked' : '' }}> <label for="cancel">Cancelled</label>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" class="form-control" name="approved_by" id="approved_by" aria-label="approved_by  example input" aria-describedby="approved_by" value="{{$leave_data->admin_id }}">

                                        <div id="approveData" style="display:none;">
                                            <div class="container">
                                                <div class="row">
                                                    <!--for leave request-->
                                                    @if($leave_data->status == 0 )
                                                    <div class="col-md-6">
                                                        <label for="approve_start_date" class="form-label mt-4">{{ __('From Date') }}</label>
                                                        <div class="">
                                                            <input type="date" class="form-control" placeholder="{{ __('Approve Start Date') }}" name="approve_start_date" id="approve_start_date" value="{{ old('approve_start_date',$leave_data->request_start_date) }}">
                                                            @error('approve_start_date')
                                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="approve_leave_type_out_id" class="form-label mt-4">{{ __('Approve Leave Type Out') }}</label>
                                                        <div class="">
                                                            <select class="form-select form-control" id="approve_leave_type_out_id" name="approve_leave_type_out_id" aria-label="approve_leave_type_out_id" aria-describedby="approve_leave_type_out_id">
                                                                <!-- <option value="">Please Select Leave Type</option> -->
                                                                @foreach($LeaveOut as $attend)
                                                                <option value="{{$attend->id}}" {{ old('approve_leave_type_out_id', $leave_data->request_leave_type_out_id) == $attend->id ? 'selected':'' }}>{{ $attend->name  }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('approve_leave_type_out_id')
                                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>


                                                    <div class="col-md-6">
                                                        <label for="approve_end_date" class="form-label mt-4">{{ __('To Date') }}</label>
                                                        <div class="">
                                                            <input type="date" class="form-control" placeholder="{{ __('Approve End Date') }}" name="approve_end_date" id="approve_end_date" value="{{ old('approve_end_date',$leave_data->request_end_date) }}">
                                                            @error('approve_end_date')
                                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="approve_leave_type_in_id" class="mt-4">{{ __('Approve Leave Type In') }}</label>
                                                        <div class="">
                                                            <select class="form-select  form-control" id="approve_leave_type_in_id" name="approve_leave_type_in_id" aria-label="approve_leave_type_in_id" aria-describedby="approve_leave_type_in_id">
                                                                <!-- <option value="">Please Select Leave Type</option> -->
                                                                @foreach($LeaveOut as $leave)
                                                                <option value="{{$leave->id}}" {{ old('approve_leave_type_in_id', $leave_data->request_leave_type_in_id) == $leave->id ? 'selected':'' }}>{{ $leave->name  }}</option>
                                                                @endforeach

                                                            </select>
                                                            @error('approve_leave_type_in_id')
                                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="approve_remark" class="form-label mt-4">{{ __('Approve Remark') }}</label>
                                                        <div class="">
                                                            <input type="text" class="form-control" placeholder="{{ __('Approve Remark') }}" name="approve_remark" id="approve_remark" value="{{ old('approve_remark',$leave_data->approve_remark) }}">
                                                            @error('approve_remark')
                                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label mt-4" for="sum">{{ __('Approve Days') }}</label>
                                                        <div class="">
                                                            <input type="number" name="approve_day" id="sum" class="form-control" readonly value="{{ old('approve_day',$leave_data->request_day) }}" />
                                                        </div>
                                                    </div>

                                                    <!--for approve leave request-->
                                                    @elseif($leave_data->status == 1)

                                                    <div class="col-md-6">
                                                        <label for="approve_start_date" class="form-label mt-4">{{ __('From Date') }}</label>
                                                        <div class="">
                                                            <input type="date" class="form-control" placeholder="{{ __('Approve Start Date') }}" name="approve_start_date" id="approve_start_date" value="{{ old('approve_start_date',$leave_data->request_start_date) }}">
                                                            @error('approve_start_date')
                                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="approve_leave_type_out_id" class="form-label mt-4">{{ __('Approve Leave Type Out') }}</label>
                                                        <div class="">
                                                            <select class="form-select form-control" id="approve_leave_type_out_id" name="approve_leave_type_out_id" aria-label="approve_leave_type_out_id" aria-describedby="approve_leave_type_out_id">
                                                                <!-- <option value="">Please Select Leave Type</option> -->
                                                                @foreach($LeaveOut as $attend)
                                                                <option value="{{$attend->id}}" {{ old('approve_leave_type_out_id', $leave_data->approve_leave_type_out_id) == $attend->id ? 'selected':'' }}>{{ $attend->name  }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('approve_leave_type_out_id')
                                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>


                                                    <div class="col-md-6">
                                                        <label for="approve_end_date" class="form-label mt-4">{{ __('To Date') }}</label>
                                                        <div class="">
                                                            <input type="date" class="form-control" placeholder="{{ __('Approve End Date') }}" name="approve_end_date" id="approve_end_date" value="{{ old('approve_end_date',$leave_data->approve_end_date) }}">
                                                            @error('approve_end_date')
                                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="approve_leave_type_in_id" class="mt-4">{{ __('Approve Leave Type In') }}</label>
                                                        <div class="">
                                                            <select class="form-select  form-control" id="approve_leave_type_in_id" name="approve_leave_type_in_id" aria-label="approve_leave_type_in_id" aria-describedby="approve_leave_type_in_id">
                                                                <!-- <option value="">Please Select Leave Type</option> -->
                                                                @foreach($LeaveOut as $leave)
                                                                <option value="{{$leave->id}}" {{ old('approve_leave_type_in_id', $leave_data->approve_leave_type_in_id) == $leave->id ? 'selected':'' }}>{{ $leave->name  }}</option>
                                                                @endforeach

                                                            </select>
                                                            @error('approve_leave_type_in_id')
                                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="approve_remark" class="form-label mt-4">{{ __('Approve Remark') }}</label>
                                                        <div class="">
                                                            <input type="text" class="form-control" placeholder="{{ __('Approve Remark') }}" name="approve_remark" id="approve_remark" value="{{ old('approve_remark',$leave_data->approve_remark) }}">
                                                            @error('approve_remark')
                                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label mt-4" for="sum">{{ __('Approve Days') }}</label>
                                                        <div class="">
                                                            <input type="number" name="approve_day" id="sum" class="form-control" readonly value="{{ old('approve_day',$leave_data->approve_day) }}" />
                                                        </div>
                                                    </div>

                                                    <!--for cancel leave request-->

                                                    @elseif($leave_data->status == 2)

                                                    <div class="col-md-6">
                                                        <label for="approve_start_date" class="form-label mt-4">{{ __('From Date') }}</label>
                                                        <div class="">
                                                            <input type="date" class="form-control" placeholder="{{ __('Approve Start Date') }}" name="approve_start_date" id="approve_start_date" value="{{ old('approve_start_date',$leave_data->request_start_date) }}">
                                                            @error('approve_start_date')
                                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="approve_leave_type_out_id" class="form-label mt-4">{{ __('Approve Leave Type Out') }}</label>
                                                        <div class="">
                                                            <select class="form-select form-control" id="approve_leave_type_out_id" name="approve_leave_type_out_id" aria-label="approve_leave_type_out_id" aria-describedby="approve_leave_type_out_id">
                                                                <!-- <option value="">Please Select Leave Type</option> -->
                                                                @foreach($LeaveOut as $attend)
                                                                <option value="{{$attend->id}}" {{ old('approve_leave_type_out_id', $leave_data->request_leave_type_out_id) == $attend->id ? 'selected':'' }}>{{ $attend->name  }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('approve_leave_type_out_id')
                                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>


                                                    <div class="col-md-6">
                                                        <label for="approve_end_date" class="form-label mt-4">{{ __('To Date') }}</label>
                                                        <div class="">
                                                            <input type="date" class="form-control" placeholder="{{ __('Approve End Date') }}" name="approve_end_date" id="approve_end_date" value="{{ old('approve_end_date',$leave_data->request_end_date) }}">
                                                            @error('approve_end_date')
                                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="approve_leave_type_in_id" class="mt-4">{{ __('Approve Leave Type In') }}</label>
                                                        <div class="">
                                                            <select class="form-select  form-control" id="approve_leave_type_in_id" name="approve_leave_type_in_id" aria-label="approve_leave_type_in_id" aria-describedby="approve_leave_type_in_id">
                                                                <!-- <option value="">Please Select Leave Type</option> -->
                                                                @foreach($LeaveOut as $leave)
                                                                <option value="{{$leave->id}}" {{ old('approve_leave_type_in_id', $leave_data->request_leave_type_in_id) == $leave->id ? 'selected':'' }}>{{ $leave->name  }}</option>
                                                                @endforeach

                                                            </select>
                                                            @error('approve_leave_type_in_id')
                                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="approve_remark" class="form-label mt-4">{{ __('Approve Remark') }}</label>
                                                        <div class="">
                                                            <input type="text" class="form-control" placeholder="{{ __('Approve Remark') }}" name="approve_remark" id="approve_remark" value="{{ old('approve_remark',$leave_data->approve_remark) }}">
                                                            @error('approve_remark')
                                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label mt-4" for="sum">{{ __('Approve Days') }}</label>
                                                        <div class="">
                                                            <input type="number" name="approve_day" id="sum" class="form-control" readonly value="{{ old('approve_day',$leave_data->request_day) }}" />
                                                        </div>
                                                    </div>

                                                    @endif

                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="submit" class="btn btn-primary w-50 p-3 subhead rounded-5 bgtheme mb-0 button text-light mx-auto submitbtn">
                                                Submit</button>
                                        </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="cancelData" style="display:none;">
        </div>
        <div id="pendingData" style="display:none;">
        </div>



        <script>
            const approveCheckbox = document.getElementById("approve");
            const cancelCheckbox = document.getElementById("cancel");
            const pendingCheckbox = document.getElementById("pending");
            const approveData = document.getElementById("approveData");
            // const cancelData = document.getElementById("cancelData");
            // const pendingData = document.getElementById("pendingData");
            function abc() {
                if (approveCheckbox.checked) {
                    approveData.style.display = "block";
                    // cancelData.style.display = "none";
                    // pendingData.style.display = "none";
                } else {
                    approveData.style.display = "none";
                }
            }
            window.addEventListener('load', abc, false);
            approveCheckbox.addEventListener("change", abc);

            cancelCheckbox.addEventListener("change", function() {
                if (cancelCheckbox.checked) {
                    // cancelData.style.display = "block";
                    approveData.style.display = "none";
                    // pendingData.style.display = "none";
                } else {
                    // cancelData.style.display = "none";
                }
            });

            pendingCheckbox.addEventListener("change", function() {
                if (pendingCheckbox.checked) {
                    // pendingData.style.display = "block";
                    approveData.style.display = "none";
                    // cancelData.style.display = "none";
                } else {
                    // pendingData.style.display = "none";
                }
            });

            // Hide all data elements on page load
            approveData.style.display = "none";

            const fromDateInput = document.getElementById("approve_start_date");
            const toDateInput = document.getElementById("approve_end_date");

            fromDateInput.addEventListener("change", () => {
                const fromDate = fromDateInput.value;
                toDateInput.min = fromDate; // set the min attribute to the selected from date
            });
            // start date from date validatio;

            $(document).ready(function() {

                $('#approve_leave_type_out_id, #approve_leave_type_in_id,#approve_start_date,#approve_end_date').change(function() {

                    var select1Value = parseFloat($('#approve_leave_type_out_id').val());
                    var select2Value = parseFloat($('#approve_leave_type_in_id').val());
                    var debutDate = Date.parse($("#approve_start_date").val());
                    var finDate = Date.parse($("#approve_end_date").val());

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

</main>

@endsection