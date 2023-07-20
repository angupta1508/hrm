@extends('layouts.admin.app')
@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        {{ __('Edit User Policy') }}
                    </h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.administration.user-policy.update', $userPolicy->id) }}" method="POST"
                enctype='multipart/form-data'>
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <label for="policy_name" class="form-label">{{ __('Policy Name') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Policy Name') }}"
                                name="policy_name" id="policy_name"
                                value="{{ old('policy_name', $userPolicy->policy_name) }}">
                            @error('policy_name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <p class="mt-4 fw-bold">{{ __('Working Hours Policy') }}</p>
                    <div class="col-md-4">
                        <label for="eneble_working_hours_relaxation"
                            class="form-label">{{ __('Enable Working Hours Relaxation') }}</label>
                        <div class="">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input mt-2" type="checkbox" role="switch"
                                    name="eneble_working_hours_relaxation" value="1"
                                    {{ old('eneble_working_hours_relaxation', $userPolicy->eneble_working_hours_relaxation) == '1' ? 'checked' : '' }}>
                            </div>
                            @error('eneble_working_hours_relaxation')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="fullday_relaxation" class="form-label">{{ __('Fullday Relaxation') }}</label>
                        <div class="">
                            <input type="number" class="form-control" placeholder="{{ __('Fullday Relaxation') }}"
                                name="fullday_relaxation" id="fullday_relaxation"
                                value="{{ old('fullday_relaxation', $userPolicy->fullday_relaxation) }}">
                            <small class="fw-bold">(Time in Minutes)</small>
                            @error('fullday_relaxation')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="halfday_relaxation" class="form-label">{{ __('Halfday Relaxation') }}</label>
                        <div class="">
                            <input type="number" class="form-control" placeholder="{{ __('Halfday Relaxation') }}"
                                name="halfday_relaxation" id="halfday_relaxation"
                                value="{{ old('halfday_relaxation', $userPolicy->halfday_relaxation) }}">
                            <small class="fw-bold">(Time in Minutes)</small>
                            @error('halfday_relaxation')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <p class="mt-4 fw-bold">{{ __('Late Coming Policy') }}</p>
                    <div class="col-md-4">
                        <label for="eneble_late_coming"
                            class="form-label">{{ __('Enable Late Coming Relaxation') }}</label>
                        <div class="">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input mt-2" type="checkbox" role="switch"
                                    name="eneble_late_coming" value="1"
                                    {{ old('eneble_late_coming', $userPolicy->eneble_late_coming) == '1' ? 'checked' : '' }}>
                            </div>
                            @error('eneble_late_coming')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="late_coming_relaxation" class="form-label">{{ __('Late Coming Relaxation') }}</label>
                        <div class="">
                            <input type="number" class="form-control" placeholder="{{ __('Late Coming Relaxation') }}"
                                name="late_coming_relaxation" id="late_coming_relaxation"
                                value="{{ old('late_coming_relaxation', $userPolicy->late_coming_relaxation) }}">
                            <small class="fw-bold">(Time in Minutes)</small>
                            @error('late_coming_relaxation')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="late_coming_deduction_repeate"
                            class="form-label">{{ __('Late Coming Deduction Repeate') }}</label>
                        <div class="">
                            <input type="number" class="form-control"
                                placeholder="{{ __('Late Coming Deduction Repeate') }}"
                                name="late_coming_deduction_repeate" id="late_coming_deduction_repeate"
                                value="{{ old('late_coming_deduction_repeate', $userPolicy->late_coming_deduction_repeate) }}">
                            @error('late_coming_deduction_repeate')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="row">
                    <p class="mt-4 fw-bold">{{ __('Early Going Policy') }}</p>
                    <div class="col-md-4">
                        <label for="eneble_early_going" class="form-label">{{ __('Enable Early Going') }}</label>
                        <div class="">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input mt-2" type="checkbox" role="switch"
                                    name="eneble_early_going" value="1"
                                    {{ old('eneble_early_going', $userPolicy->eneble_early_going) == '1' ? 'checked' : '' }}>
                            </div>
                            @error('eneble_early_going')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="early_going_relaxation" class="form-label">{{ __('Early Going Relaxation') }}</label>
                        <div class="">
                            <input type="number" class="form-control"
                                placeholder="{{ __('Early Going Relaxation   ') }}" name="early_going_relaxation"
                                id="early_going_relaxation"
                                value="{{ old('early_going_relaxation', $userPolicy->early_going_relaxation) }}">
                            <small class="fw-bold">(Time in Minutes)</small>
                            @error('early_going_relaxation')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="early_going_deduction_repeate"
                            class="form-label">{{ __('Early Going Deduction Repeate') }}</label>
                        <div class="">
                            <input type="number" class="form-control"
                                placeholder="{{ __('Early Going Deduction Repeate') }}"
                                name="early_going_deduction_repeate" id="early_going_deduction_repeate"
                                value="{{ old('early_going_deduction_repeate', $userPolicy->early_going_deduction_repeate) }}">
                            @error('early_going_deduction_repeate')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>


                {{-- Holiday --}}
                <div class="row">
                    <p class="mt-4 fw-bold">{{ __('Holiday Working Hours Policy') }}</p>
                    <div class="col-md-4">
                        <label for="eneble_holiday_working_hours"
                            class="form-label">{{ __('Enable Holiday Working Hours') }}</label>
                        <div class="">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input mt-2" type="checkbox" role="switch"
                                    name="eneble_holiday_working_hours" value="1"
                                    {{ old('eneble_holiday_working_hours', $userPolicy->eneble_holiday_working_hours) == '1' ? 'checked' : '' }}>
                            </div>
                            @error('eneble_holiday_working_hours')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="holiday_working_hours" class="form-label">{{ __('Holiday Working Hours') }}</label>
                        <div class="">
                            <input type="number" class="form-control" placeholder="{{ __('Holiday Working Hours') }}"
                                name="holiday_working_hours" id="holiday_working_hours"
                                value="{{ old('holiday_working_hours', $userPolicy->holiday_working_hours) }}">
                            <small class="fw-bold">(Time in Minutes)</small>
                            @error('holiday_working_hours')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>zz
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- weekoff --}}
                <div class="row">
                    <p class="mt-4 fw-bold">{{ __('WeekOff Working Hours Policy') }}</p>
                    <div class="col-md-4">
                        <label for="eneble_weekoff_working_hours"
                            class="form-label">{{ __('Enable WeekOff Working Hours') }}</label>
                        <div class="">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input mt-2" type="checkbox" role="switch"
                                    name="eneble_weekoff_working_hours" value="1"
                                    {{ old('eneble_weekoff_working_hours', $userPolicy->eneble_weekoff_working_hours) == '1' ? 'checked' : '' }}>
                            </div>
                            @error('eneble_weekoff_working_hours')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="weekoff_working_hours" class="form-label">{{ __('WeekOff Working Hours') }}</label>
                        <div class="">
                            <input type="number" class="form-control" placeholder="{{ __('WeekOff Working Hours') }}"
                                name="weekoff_working_hours" id="weekoff_working_hours"
                                value="{{ old('weekoff_working_hours', $userPolicy->weekoff_working_hours) }}">
                            <small class="fw-bold">(Time in Minutes)</small>
                            @error('weekoff_working_hours')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>zz
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Weekend --}}
                <div class="row">
                    <p class="mt-4 fw-bold">{{ __('Working Week Day for Weekend Policy') }}</p>
                    <div class="col-md-4">
                        <label for="eneble_weekday_for_weekend"
                            class="form-label">{{ __('Enable Week Day for Weekend') }}</label>
                        <div class="">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input mt-2" type="checkbox" role="switch"
                                    name="eneble_weekday_for_weekend" value="1"
                                    {{ old('eneble_weekday_for_weekend', $userPolicy->eneble_weekday_for_weekend) == '1' ? 'checked' : '' }}>
                            </div>
                            @error('eneble_weekday_for_weekend')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="weekday_for_weekend" class="form-label">{{ __('Week Day for Weekend') }}</label>
                        <div class="">
                            {{ Form::select('weekday_for_weekend', ['' => __('Select Week Day')] + getListTranslate(config('constants.weekend')), old('weekday_for_weekend', $userPolicy->weekday_for_weekend), ['class' => 'form-control']) }}

                            @error('weekday_for_weekend')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>zz
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="row">
                    <p class="mt-4 fw-bold">{{ __('Salary Setup Policy') }}</p>
                    <div class="col-md-12">
                        <label for="autual_month_day" class="form-label">{{ __('Actual Working Day') }}</label>
                        <div class="" style="font-size: 12px;font-weight: bold">
                            <input type="radio" id="0" name="autual_month_day" value="0"
                                {{ old('autual_month_day', $userPolicy->autual_month_day) == '0' ? 'checked' : '' }}>
                            <label for="0">Salary Month of 30 Days</label>
                            <input type="radio" id="1" name="autual_month_day" value="1"
                                {{ old('autual_month_day', $userPolicy->autual_month_day) == '1' ? 'checked' : '' }}>
                            <label for="1">Salary Month of Actual Days</label>
                            <input type="radio" id="2" name="autual_month_day" value="2"
                                {{ old('autual_month_day', $userPolicy->autual_month_day) == '2' ? 'checked' : '' }}>
                            <label for="2">Salary Working Hours</label>
                            @error('autual_month_day')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <p class="mt-4 fw-bold">{{ __('Overtime Policy') }}</p>
                    <div class="col-md-4">
                        <label for="eneble_overtime_working_day"
                            class="form-label">{{ __('Enable Overtime Working Day (Enable Overtime Calculation for working hours)') }}</label>
                        <div class="">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input mt-2" type="checkbox" role="switch"
                                    name="eneble_overtime_working_day" value="1"
                                    {{ old('eneble_overtime_working_day', $userPolicy->eneble_overtime_working_day) == '1' ? 'checked' : '' }}>
                            </div>
                            @error('eneble_overtime_working_day')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="overtime_apply_time" class="form-label">{{ __('Overtime Apply Time') }}</label>
                        <div class="">
                            <input type="number" class="form-control" placeholder="{{ __('Overtime Apply Time') }}"
                                name="overtime_apply_time"
                                value=" {{ old('overtime_apply_time', $userPolicy->overtime_apply_time) }}">
                            <small class="fw-bold">(Time in Minutes)</small>
                            @error('overtime_apply_time')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <p class="mt-4 fw-bold">{{ __('Sandwich Policy') }}</p>
                    <div class="col-md-6">
                        <label for="eneble_sandwich" class="form-label">{{ __('Enable Sandwich') }}</label>
                        <div class="">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input mt-2" type="checkbox" role="switch"
                                    name="eneble_sandwich" value="1"
                                    {{ old('eneble_sandwich', $userPolicy->eneble_sandwich) == '1' ? 'checked' : '' }}>
                            </div>
                            @error('eneble_sandwich')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <p class="mt-4 fw-bold">{{ __('Leave Policy') }}</p>
                    <div class="col-md-4 mt-3">
                        <label for="cl" class="form-label">{{ __('CL') }}</label>
                        <div class="">
                            <input type="number" class="form-control" placeholder="{{ __('CL') }}" name="cl"
                                id="cl" value="{{ old('cl', $userPolicy->cl) }}">
                            @error('cl')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="pl" class="form-label">{{ __('PL') }}</label>
                        <div class="">
                            <input type="number" class="form-control" placeholder="{{ __('PL') }}" name="pl"
                                id="pl" value="{{ old('pl', $userPolicy->pl) }}">
                            @error('pl')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="medical_leave" class="form-label">{{ __('Medical Leave') }}</label>
                        <div class="">
                            <input type="number" class="form-control" placeholder="{{ __('Medical Leave') }}"
                                name="medical_leave" id="medical_leave"
                                value="{{ old('medical_leave', $userPolicy->medical_leave) }}">
                            @error('medical_leave')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="paternity_leave" class="form-label">{{ __('Paternity Leave') }}</label>
                        <div class="">
                            <input type="number" class="form-control" placeholder="{{ __('Paternity Leave') }}"
                                name="paternity_leave" id="paternity_leave"
                                value="{{ old('paternity_leave', $userPolicy->paternity_leave) }}">
                            @error('paternity_leave')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="maternity_leave" class="form-label">{{ __('Maternity Leave') }}</label>
                        <div class="">
                            <input type="number" class="form-control" placeholder="{{ __('Maternity Leave') }}"
                                name="maternity_leave" id="maternity_leave"
                                value="{{ old('maternity_leave', $userPolicy->maternity_leave) }}">
                            @error('maternity_leave')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="every_month_paid_leave"
                            class="form-label">{{ __('Every Month Paid Leave') }}</label>
                        <div class="">
                            <input type="number" class="form-control" placeholder="{{ __('Every Month Paid Leave') }}"
                                name="every_month_paid_leave" id="every_month_paid_leave"
                                value="{{ old('every_month_paid_leave', $userPolicy->every_month_paid_leave) }}">
                            @error('every_month_paid_leave')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mt-3">
                        <label for="carry_forward_month"
                            class="form-label">{{ __('Carry Forward For Next Month') }}</label>
                        <div class="">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input mt-2" type="checkbox" role="switch"
                                    name="carry_forward_month" value="1"
                                    {{ old('carry_forward_month', $userPolicy->carry_forward_month) == '1' ? 'checked' : '' }}>
                            </div>
                            @error('carry_forward_month')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="carry_forward_year" class="form-label">{{ __('Carry Forward For Year') }}</label>
                        <div class="">
                            <div class="form-check form-switch d-inline-block">
                                <input class="form-check-input mt-2" type="checkbox" role="switch"
                                    name="carry_forward_year" value="1"
                                    {{ old('carry_forward_year', $userPolicy->carry_forward_year) == '1' ? 'checked' : '' }}>
                            </div>
                            @error('carry_forward_year')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 mt-3">
                        <label for="carry_forward_till_month"
                            class="form-label">{{ __('Carry Forward Till Month') }}</label>
                        <div class="">
                            {{ Form::select('carry_forward_till_month', ['' => __('Select Month')] + getListTranslate(config('constants.month_name')), old('carry_forward_till_month', $userPolicy->carry_forward_till_month), ['class' => 'form-control']) }}
                            @error('carry_forward_till_month')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.administration.user-policy.index') }}" type="button" name="button"
                        class="btn btn-light m-0">{{ __('BACK TO LIST') }}</a>
                    <button type="submit" name="button" class="btn bg-gradient-primary m-0 ms-2">
                        {{ __('Submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('dashboard')
        <script></script>
    @endpush
@endsection
