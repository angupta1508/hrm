@extends('layouts.admin.app')
@section('content')
<div class="card card-default">
    <div class="card-header pb-0">
        <div class="d-flex flex-row justify-content-between">
            <div>
                <h5 class="mb-0">
                    {{__('Edit Salary Setup')}}
                </h5>
            </div>
        </div>
    </div>

    <div class="card-body">

        <form action="{{ route('admin.payroll.salary-setup.update', $salary_setup) }}" method="POST" enctype='multipart/form-data'>
            @method('PUT')
            @csrf
            <div class="row">

                <div class="col-md-4">
                    <label for="salary_based_on" class="form-label mt-4">{{ __('Salary Base On') }}</label>
                    <div class="">
                        {{ Form::select('salary_based_on', ['' => __('Select Salary Based On')] + getListTranslate(config('constants.salary_based_on')), old('salary_based_on' , $salary_setup->salary_based_on), ['class' => 'form-select','id' => 'salary_based_on' ,'onchange' => "disableInput()"]) }}
                        @error('salary_based_on')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <div class="col-md-4">
                    <label for="state_id" class="form-label mt-4">{{__('User')}}</label>
                    <div>
                        {{ Form::select('user_id', ['name' => __('Select User')] + $user_list, old('user_id', $salary_setup->user_id), ['class' => 'form-select select2 User_dropdown']) }}
                        @error('user_id')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="salary_type_id" class="form-label mt-4">{{ __('Salary Type') }}</label>
                    <div class="">
                        <select type="number" class="form-select  select2" id="salary_type_id" name="salary_type_id" aria-label="salary_type_id" aria-describedby="salary_type_id">
                            <option value="">{{__('Please Select Salary Type')}}</option>
                            @foreach($salary_type_list as $type)
                            <option value="{{$type->id}}" {{ old('salary_type_id', $salary_setup->salary_type_id) == $type->id ? 'selected':'' }}>{{ $type->salary_type }}</option>
                            @endforeach
                        </select>
                        @error('salary_type_id')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="basic_salary" id="basic_salary1" style="display: none;" class="form-label mt-4">{{ __('Basic Salary/Per Hours') }}</label>
                    <label for="basic_salary" id="basic_salary2" class="form-label mt-4">{{ __('Basic Salary/Per Month') }}</label>
                    <div class="">
                        <input type="number" class="form-control date" placeholder="{{ __('Basic Salary') }}" name="basic_salary" id="basic_salary" value="{{ old('basic_salary',$salary_setup->basic_salary) }}">
                        @error('basic_salary')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <div class="col-md-4">
                    <label for="dearness_allowance" class="form-label mt-4">{{ __('Dearness Allowance') }}</label>
                    <div class="">
                        <input type="number" class="form-control date" placeholder="{{ __('Dearness Allowance') }}" name="dearness_allowance" id="dearness_allowance" value="{{ old('dearness_allowance',$salary_setup->dearness_allowance) }}">
                        @error('dearness_allowance')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="per_hour_overtime_amount" class="form-label mt-4">{{ __('Per Hour Overtime Amount') }}</label>
                    <div class="">
                        <input type="number" class="form-control date" placeholder="{{ __('Per Hour Overtime Amount') }}" name="per_hour_overtime_amount" id="per_hour_overtime_amount" value="{{ old('per_hour_overtime_amount',$salary_setup->per_hour_overtime_amount) }}">
                        @error('per_hour_overtime_amount')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>



                <div class="col-md-4">
                    <label for="washing_allowance" class="form-label mt-4">{{ __('Washing Allowance') }}</label>
                    <div class="">
                        <input type="number" class="form-control" placeholder="{{ __('Washing Allowance') }}" name="washing_allowance" id="washing_allowance" value="{{ old('washing_allowance',$salary_setup->washing_allowance) }}">
                        @error('washing_allowance')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="house_rant_allowance" class="form-label mt-4">{{ __('House Rent Allowance') }}</label>
                    <div class="">
                        <input type="number" class="form-control" placeholder="{{ __('House Rent Allowance') }}" name="house_rant_allowance" id="house_rant_allowance" value="{{ old('house_rant_allowance',$salary_setup->washing_allowance) }}">
                        @error('house_rant_allowance')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="conveyance_allowance" class="form-label mt-4">{{ __('Conveyance Allowance') }}</label>
                    <div class="">
                        <input type="number" class="form-control" placeholder="{{ __('Conveyance Allowance') }}" name="conveyance_allowance" id="conveyance_allowance" value="{{ old('conveyance_allowance',$salary_setup->conveyance_allowance) }}">
                        @error('conveyance_allowance')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="medical_allowance" class="form-label mt-4">{{ __('Medical Allowance') }}</label>
                    <div class="">
                        <input type="number" class="form-control" placeholder="{{ __('Medical Allowance') }}" name="medical_allowance" id="medical_allowance" value="{{ old('medical_allowance',$salary_setup->medical_allowance) }}">
                        @error('medical_allowance')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="other_allowance" class="form-label mt-4">{{ __('Other Allowance') }}</label>
                    <div class="">
                        <input type="number" class="form-control" placeholder="{{ __('Other Allowance') }}" name="other_allowance" id="other_allowance" value="{{ old('other_allowance',$salary_setup->other_allowance) }}">
                        @error('other_allowance')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="fix_incentive" class="form-label mt-4">{{ __('Fix Incentive') }}</label>
                    <div class="">
                        <input type="number" class="form-control" placeholder="{{ __('Fix Incentive') }}" name="fix_incentive" id="fix_incentive" value="{{ old('fix_incentive',$salary_setup->fix_incentive) }}">
                        @error('fix_incentive')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="variable_incentive" class="form-label mt-4">{{ __('Variable Incentive') }}</label>
                    <div class="">
                        <input type="number" class="form-control" placeholder="{{ __('Variable Incentive') }}" name="variable_incentive" id="variable_incentive" value="{{ old('variable_incentive',$salary_setup->variable_incentive) }}">
                        @error('variable_incentive')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="deductions" class="form-label mt-4">{{ __('Deductions') }}</label>
                    <div class="">
                        <input type="number" class="form-control" placeholder="{{ __('Deductions') }}" name="deductions" id="deductions" value="{{ old('deductions',$salary_setup->deductions) }}">
                        @error('deductions')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="welfare_fund" class="form-label mt-4">{{ __('Welfare Fund') }}</label>
                    <div class="">
                        <input type="number" class="form-control" placeholder="{{ __('Welfare Fund') }}" name="welfare_fund" id="welfare_fund" value="{{ old('welfare_fund',$salary_setup->welfare_fund,0) }}">
                        @error('welfare_fund')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="affected_date" class="form-label mt-4">{{ __('Affected Date') }}</label>
                    <div class="">
                        <input type="date" class="form-control" placeholder="{{ __('Affected Date') }}" name="affected_date" id="affected_date" value="{{ old('affected_date',$salary_setup->affected_date) }}">
                        @error('affected_date')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.payroll.salary-setup.index') }}" type="button" name="button" class="btn btn-light m-0">{{ __('BACK TO LIST') }}</a>
                    <button type="submit" name="button" class="btn bg-gradient-primary m-0 ms-2">
                        {{__('update')}}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('dashboard')

<script>
    function disableInput() {
        var salaryBaseOnSelect = document.getElementById("salary_based_on");
        var selectedValue = salaryBaseOnSelect.options[salaryBaseOnSelect.selectedIndex].value;
        var dearnessAllowanceInput = document.getElementById("dearness_allowance");
        var perHourOverTimeInput = document.getElementById("per_hour_overtime_amount");
        var washingAllowanceInput = document.getElementById("washing_allowance");
        var houseRantAllowanceInput = document.getElementById("house_rant_allowance");
        var conveyanceAllowanceInput = document.getElementById("conveyance_allowance");
        var medicalAllowanceInput = document.getElementById("medical_allowance");
        var otherAllowanceInput = document.getElementById("other_allowance");
        var fixIncentiveInput = document.getElementById("fix_incentive");
        var variableIncentiveInput = document.getElementById("variable_incentive");
        var deductionsInput = document.getElementById("deductions");
        var welfareFundInput = document.getElementById("welfare_fund");

        var label1 = document.getElementById("basic_salary1");
        var label2 = document.getElementById("basic_salary2");
   

        if (selectedValue == 1) {
            dearnessAllowanceInput.disabled = true;
            perHourOverTimeInput.disabled = true;
            washingAllowanceInput.disabled = true;
            houseRantAllowanceInput.disabled = true;
            conveyanceAllowanceInput.disabled = true;
            medicalAllowanceInput.disabled = true;
            otherAllowanceInput.disabled = true;
            fixIncentiveInput.disabled = true;
            variableIncentiveInput.disabled = true;
            deductionsInput.disabled = true;
            welfareFundInput.disabled = true;
            label2.style.display = "none";
            label1.style.display = "block";
        } else {
            dearnessAllowanceInput.disabled = false;
            perHourOverTimeInput.disabled = false;
            washingAllowanceInput.disabled = false;
            houseRantAllowanceInput.disabled = false;
            conveyanceAllowanceInput.disabled = false;
            medicalAllowanceInput.disabled = false;
            otherAllowanceInput.disabled = false;
            fixIncentiveInput.disabled = false;
            variableIncentiveInput.disabled = false;
            deductionsInput.disabled = false;
            welfareFundInput.disabled = false;
            label1.style.display = "none";
            label2.style.display = "block";

        }

    }
</script>

@endpush
@endsection