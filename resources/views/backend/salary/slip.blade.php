@extends('layouts.admin.app')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-bd">
                <div class="panel title text-right">
                    <button class="btn btn-warning" id="pdf"><span class="fa fa-print"></span></button>
                </div>
                <div>
                    <div class="panel-body" id="payslip">
                        <div class="row">
                            <div class="col-sm-12">
                                <table style="width:100%;">
                                    <tr>
                                        @php
                                            $imgPath = config('constants.setting_image_path');
                                            $imgDefaultPath = config('constants.default_image_path');
                                            $logo = ImageShow($imgPath, config()->get('logo'), 'icon', $imgDefaultPath);
                                        @endphp
                                        <td><img src="{{ $logo }}" width="160px;" alt=""></td>
                                        <td class="text-center">
                                            <address>
                                                <strong>{{ config()->get('company_name') }}</strong><br>
                                                {{ config()->get('address') }}<br>
                                                <span>
                                                    Salary Slip For The Month Of
                                                    {{ !empty($salary->salary_name) ? date('M Y', strtotime($salary->salary_name)) : '' }}
                                                </span>
                                            </address>
                                        </td>
                                        <td></td>
                                    </tr>
                                </table>
                                <table style="width:100%;border:1px solid #1e1c1c;">
                                    <tr>
                                        <td>
                                            <table style="width:100%;margin: 10px;">
                                                <tr>
                                                    <td> Employee Name
                                                    </td>
                                                    <th> :
                                                        {{ !empty($userData->name) ? $userData->name : '' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Father Name
                                                    </td>
                                                    <th> :
                                                        {{ !empty($userData->father_name) ? $userData->father_name : '' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>DOB
                                                    </td>
                                                    <th> : {{ !empty($userData->dob) ? $userData->dob : '' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Pan No.
                                                    </td>
                                                    <th> : {{ !empty($userData->pan_no) ? $userData->pan_no : '' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Gender
                                                    </td>
                                                    <th> :
                                                        {{ !empty($userData->gender) ? $userData->gender : '' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Designation
                                                    </td>
                                                    <th> :
                                                        {{ !empty($userData->designation_name) ? $userData->designation_name : '' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Salary Date
                                                    </td>
                                                    <th> : {{ !empty($salary->payment_date) ? $salary->payment_date : '' }}
                                                    </th>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table style="width:100%;margin: 10px;">
                                                <tr>
                                                    <td>Employee Id</td>
                                                    <th>:
                                                        {{ !empty($userData->employee_code) ? $userData->employee_code : '' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Hire Date
                                                    </td>
                                                    <th> : {{ !empty($userData->hire_date) ? $userData->hire_date : '' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Original Hire Date
                                                    </td>
                                                    <th> :
                                                        {{ !empty($userData->original_hire_date) ? $userData->original_hire_date : '' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>PF No
                                                    </td>
                                                    <th> : {{ !empty($userData->pf_no) ? $userData->pf_no : '' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>UAN No
                                                    </td>
                                                    <th> : {{ !empty($userData->uan_no) ? $userData->uan_no : '' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>ESIC No
                                                    </td>
                                                    <th> : {{ !empty($userData->esic_no) ? $userData->esic_no : '' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Bank Name
                                                    </td>
                                                    <th> :

                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>Account No
                                                    </td>
                                                    <th> : {{ !empty($userData->account_no) ? $userData->account_no : '' }}
                                                    </th>
                                                </tr>

                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <table style="width:100%;border:1px solid #1e1c1c;">
                                    <tr>
                                        <td>
                                            <table style="width:100%;margin: 10px;">

                                                <tr>
                                                    <td>
                                                        Month Days
                                                    </td>
                                                    @php
                                                        $y = date('Y', strtotime('last day of ' . $salary->salary_name));
                                                        $m = date('m', strtotime('last day of ' . $salary->salary_name));
                                                        if ($userPolicy->autual_month_day == 0) {
                                                            $monthDays = 30;
                                                        } elseif ($userPolicy->autual_month_day == 1) {
                                                            $monthDays = cal_days_in_month(CAL_GREGORIAN, $m, $y);
                                                        }
                                                    @endphp
                                                    <th> : {{ $monthDays }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td> Present Days
                                                    </td>
                                                    <th> :
                                                        {{ !empty($salary->present) ? $salary->present : '' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td> Applicable Week Off
                                                    </td>
                                                    <th> :
                                                        {{ $salary->applicable_week_off }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Payable Leave Days
                                                    </td>
                                                    <th> : {{ !empty($salary->apply_pl) ? $salary->apply_pl : '' }}
                                                    </th>
                                                </tr>
                                            </table>
                                        </td>
                                        <td>
                                            <table style="width:100%;margin: 10px;">

                                                <tr>
                                                    <td> Auto Leave Days
                                                    </td>
                                                    <th> :
                                                        {{ !empty($salary->auto_leave) ? $salary->auto_leave : '' }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Extra Days
                                                    </td>
                                                    <th> : {{ !empty($salary->extrapresent) ? $salary->extrapresent : '' }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td> LOP Days
                                                    </td>
                                                    <th> :
                                                        {{ !empty($salary->apply_cl) ? $salary->apply_cl : '' }}
                                                    </th>
                                                </tr>

                                                <tr>
                                                    <td> Pay Days
                                                    </td>
                                                    <th> :
                                                        {{ !empty($salary->paydays) ? $salary->paydays : '' }}
                                                    </th>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div class="col-sm-12">
                                <table class="table bordered_table" style="border:1px solid #1e1c1c;">
                                    <tr>
                                        <td class="left-panel" style="border-right:1px solid #1e1c1c;">
                                            <table class="table" width="100%">
                                                <thead>
                                                    <tr class="employee">
                                                        <th class="name text-left">Earnings</th>
                                                        <th class="name text-left">Salary Rate
                                                        </th>
                                                        <th class="name text-right">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="">
                                                    @if (!empty($salary->basic_salary) && $salary->basic_salary > 0)
                                                        <tr class="entry">
                                                            <td class="value">
                                                                Basic Salary
                                                                {{ $salary->salary_based_on == 0 ? '(Per Month)' : '(Per Hour)' }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ !empty($salary->basic_salary) ? $salary->basic_salary : '' }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ !empty($salary->gross_salary) ? $salary->gross_salary : '' }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if (!empty($salary->extra_amount) && $salary->extra_amount > 0)
                                                        <tr class="entry">
                                                            <td class="value">
                                                                Extra Amount
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $setup->extra_amount }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $salary->extra_amount }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if (!empty($salary->dearness_allowance) && $salary->dearness_allowance > 0)
                                                        <tr class="entry">
                                                            <td class="value">
                                                                Dearness Allowance
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $setup->dearness_allowance }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $salary->dearness_allowance }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if (!empty($salary->house_rant_allowance) && $salary->house_rant_allowance > 0)
                                                        <tr class="entry">
                                                            <td class="value">
                                                                House Rent Allowance
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $setup->house_rant_allowance }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $salary->house_rant_allowance }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if (!empty($salary->conveyance_allowance) && $salary->conveyance_allowance > 0)
                                                        <tr class="entry">
                                                            <td class="value">
                                                                Conveyance Allowance
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $setup->conveyance_allowance }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $salary->conveyance_allowance }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if (!empty($salary->medical_allowance) && $salary->medical_allowance > 0)
                                                        <tr class="entry">
                                                            <td class="value">
                                                                Medical Allowance
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $setup->medical_allowance }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $salary->medical_allowance }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if (!empty($salary->other_allowance) && $salary->other_allowance > 0)
                                                        <tr class="entry">
                                                            <td class="value">
                                                                Other Allowance
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $setup->other_allowance }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $salary->other_allowance }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if (!empty($salary->washing_allowance) && $salary->washing_allowance > 0)
                                                        <tr class="entry">
                                                            <td class="value">
                                                                Washing Allowance
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $setup->washing_allowance }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $salary->washing_allowance }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if (!empty($salary->fix_incentive) && $salary->fix_incentive > 0)
                                                        <tr class="entry">
                                                            <td class="value">
                                                                Fix Incentive
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $setup->fix_incentive }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $salary->fix_incentive }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if (!empty($salary->variable_incentive) && $salary->variable_incentive > 0)
                                                        <tr class="entry">
                                                            <td class="value">
                                                                Variable Incentive
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $setup->variable_incentive }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $salary->variable_incentive }}
                                                            </td>

                                                        </tr>
                                                    @endif
                                                    @if (!empty($salary->additional_salary_settlement_amount) && $salary->additional_salary_settlement_amount > 0)
                                                        <tr class="entry">
                                                            <td class="value">
                                                                Additional Settlement Amount
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $salary->additional_salary_settlement_amount }}
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $salary->additional_salary_settlement_amount }}
                                                            </td>

                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </td>
                                        <td class="right-panel">
                                            <table class="table" width="100%">
                                                <thead>
                                                    <tr class="employee">
                                                        <th class="name text-left">Deduction</th>
                                                        <th class="name text-right">Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="">
                                                    @if (!empty($salary->pf_amount))
                                                        <tr class="entry">
                                                            <td class="value">PF Amount</td>
                                                            <td class="text-right">
                                                                <div>
                                                                    {{ $salary->pf_amount }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if (!empty($salary->esi_amount))
                                                        <tr class="entry">
                                                            <td class="value">ESI Amount</td>
                                                            <td class="text-right">
                                                                <div>
                                                                    {{ $salary->esi_amount }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if (!empty($salary->welfare_fund))
                                                        <tr class="entry">
                                                            <td class="value">Welfare Fund</td>
                                                            <td class="text-right">
                                                                <div>
                                                                    {{ $salary->welfare_fund }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if (!empty($salary->deductions))
                                                        <tr class="entry">
                                                            <td class="value">Deductions</td>
                                                            <td class="text-right">
                                                                <div>
                                                                    {{ $salary->deductions }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if (!empty($salary->ins_amount))
                                                        <tr class="entry">
                                                            <td class="value">INS Amount</td>
                                                            <td class="text-right">
                                                                <div>
                                                                    {{ $salary->ins_amount }}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if (!empty($salary->deduction_salary_settlement_amount))
                                                        <tr class="entry">
                                                            <td class="value">
                                                                Deduction Settlement Amount
                                                            </td>
                                                            <td class="text-right">
                                                                {{ $salary->deduction_salary_settlement_amount }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>

                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="left-panel" style="border:1px solid #1e1c1c;">
                                            <table class="table" width="100%">
                                                <tbody class="details">
                                                    <tr class="entry">
                                                        <td class="value">
                                                            Total Addition
                                                        </td>
                                                        <td class="text-right">
                                                            <b>{{ $salary->total_addition }}</b>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td class="right-panel" style="border:1px solid #1e1c1c;">
                                            <table class="table" width="100%">
                                                <tbody class="details">
                                                    <tr class="entry">
                                                        <td class="value">
                                                            Total Deduction
                                                        </td>
                                                        <td class="text-right">
                                                            <b>
                                                                {{ $salary->total_deduction }}
                                                            </b>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>

                                </table>

                            </div>
                        </div>


                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table" style="border:1px solid #1e1c1c;">
                                    <tbody class="nti">
                                        <tr class="details">
                                            <th class="value" style="border:1px solid #1e1c1c;">
                                                Net Salary : {{ $salary->total_salary }} </th>
                                        </tr>
                                        <tr class="details">
                                            <th class="value" style="border:1px solid #1e1c1c;">
                                                Net Salary :
                                                In Word : {{ $amountinword }}</th>
                                        </tr>
                                        <tr class="details">
                                            <th class="value" style="border:1px solid #1e1c1c;">*This is a system
                                                generated
                                                payslip and does not require signature</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('dashboard')
        <script>
            $(document).ready(function() {
                $('#pdf').on('click', function() {
                    var admin_id = "{{ Config::get('auth_detail')['admin_id'] }}"
                    var user_id = "{{ $salary->user_id }}"
                    var month = "{{ $salary->salary_name }}"
                    $.ajax({
                        url: "{{ route('admin.payroll.salaryslip') }}",
                        type: "POST",
                        data: {
                            admin_id: admin_id,
                            user_id: user_id,
                            month: month,
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            toastr.warning("Wait for seconds....");
                        },
                        success: function(result) {
                            if (result.status == 1) {
                                toastr.success(result.msg);
                                window.open(result.url, '_blank');
                            } else {
                                toastr.error(result.msg);
                            }
                        }
                    });
                });
            })
        </script>
    @endpush
@endsection
