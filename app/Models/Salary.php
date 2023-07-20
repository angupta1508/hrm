<?php

namespace App\Models;

use App\Models\User;
use PDF;
use App\Models\Attendance;
use App\Models\UserPolicy;
use App\Models\SalarySetup;
use App\Models\LeaveApplication;
use App\Console\Commands\MyCommand;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salary extends Model
{
    use HasFactory, Sortable;
    protected $fillable = [
        'id',
        'admin_id',
        'user_id',
        'salary_setup_id',
        'salary_based_on',
        'salary_name',
        'total_salary',
        'present',
        'paydays',
        'total_working_minutes',
        'working_period',
        'payment_status',
        'gateway_payment_id',
        'payment_date',
        'payment_type',
        'user_bank_id',
        'paid_by',
        'apply_pl',
        'apply_cl',
        'auto_leave',
        'total_holidays',
        'applicable_week_off',
        'extrapresent',
        'extra_amount',
        'basic_salary',
        'dearness_allowance',
        'washing_allowance',
        'house_rant_allowance',
        'conveyance_allowance',
        'additional_salary_settlement_amount',
        'deduction_salary_settlement_amount',
        'medical_allowance',
        'other_allowance',
        'fix_incentive',
        'deductions',
        'welfare_fund',
        'variable_incentive',
        'gross_salary',
        'pf_amount',
        'esi_amount',
        'total_addition',
        'total_deduction',
        'loan_installment_id',
        'loan_installment_amount',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public static function salaryDayCalculation($salaryMonth, $userData)
    {
        $userData->month =  $salaryMonth;
        $userSalaryData = Salary::where('user_id', $userData->user_id)->where('salary_name', $userData->month)->first();
        $userattendance = Attendance::getUserAttendanceList($userData);

        $userSalary = SalarySetup::getUserSalarySetUp($userData);
        $userPolicy =   UserPolicy::getEmployeePolicy($userData->policy_id);
        $y = date('Y', strtotime($salaryMonth));
        $m = date('m', strtotime($salaryMonth));
        $actualMonthDays = cal_days_in_month(CAL_GREGORIAN, $m, $y);

        if ($userPolicy->autual_month_day == 0) {
            $monthDays = 30;
        } elseif ($userPolicy->autual_month_day == 1) {
            $monthDays = $actualMonthDays;
        }

        $ary = (object)array(
            'user_id'       =>   $userData->id,
            'admin_id'      =>   $userData->admin_id,
            'date'          =>   $salaryMonth,
            'leave_type_id' =>   1
        );

        $userLeaveBalance = LeaveApplication::checkLeaveBalance($ary);
        // pr($userLeaveBalance); die;

        $hd = ($userattendance->halfday) / 2;
        $hl = ($userattendance->haifleave) / 2;

        $totalpresents = $userattendance->present + $hd - $userattendance->overday;

        $applicable_week_off = count(gettWeekOffDates($userData, $salaryMonth));

        $applyHoliday =  $applicable_week_off + $userattendance->hoildays;

        $workedDays =  $totalpresents + $userattendance->leaveday + $userattendance->overday + $applyHoliday;

        $removeWeekOff = 0;


        $autoLeave = $aplicable_auto_leave = 0;

        $lopDays = $userattendance->absent + $hd;

        $leaveDay = $userattendance->leaveday + $hl;


        if ($userLeaveBalance->available_leave_blance > $leaveDay) {
            $aplicable_auto_leave = $userLeaveBalance->available_leave_blance - $userattendance->leaveday;
        }
        // if (now()->day > config('constants.autoLeaveApplyday')) {
            if ($lopDays > 0 && $aplicable_auto_leave > 0 && $lopDays >= $aplicable_auto_leave) {
                $lopDays = $lopDays - $aplicable_auto_leave;
                $autoLeave = $aplicable_auto_leave;
            } elseif ($lopDays > 0 && $aplicable_auto_leave > 0 && $aplicable_auto_leave > $lopDays) {
                $autoLeave = $lopDays;
                $lopDays = 0;
            }
            // elseif ($lopDays > 0 && $aplicable_auto_leave > 0 && $lopDays >= ($aplicable_auto_leave / 2)) {
            //     $lopDays = $lopDays - $aplicable_auto_leave;
            //     $autoLeave = $aplicable_auto_leave;
            // }
        // }
        // dd($auto_leave);

        if ($userPolicy->eneble_weekday_for_weekend == 1) {
            if ($lopDays > $userPolicy->weekday_for_weekend) {
                $removeWeekOff = floor($lopDays / $userPolicy->weekday_for_weekend);
                $applicable_week_off = $applicable_week_off - $removeWeekOff;
            }
        }

        $workingDays = $workedDays - $removeWeekOff + $autoLeave;

        if (!empty($userSalaryData)) {
            $autoLeave = $userSalaryData->auto_leave;
            $workingDays += $userSalaryData->auto_leave;
        }

        $array = (object)array(
            'workingDays'           => $workingDays,
            'autoLeave'             => $autoLeave,
            'monthDays'             => $monthDays,
            'actualMonthDays'       => $actualMonthDays,
            'overtime'              => $userattendance->overtime,
            'totalpresents'         => $totalpresents,
            'lopDays'               => $lopDays,
            'hoildays'              => $userattendance->hoildays,
            'weekOff'               => $userattendance->weekOff,
            'overday'               => $userattendance->overday,
            'workinghours'          => $userattendance->workinghours,
            'applicable_week_off'   => $applicable_week_off,
            'leaveday'              => $userattendance->leaveday,
        );
        return $array;
    }


    public static function SalaryGenrate($request)
    {
        // $users = User::where('admin_id', Config::get('auth_detail')['id'])->where('role_id', config('constants.employee_role_id'))->where('id', 33)->where('status', 1)->where('trash', 0)->get();

        $users = User::where('admin_id', Config::get('auth_detail')['id'])->where('role_id', config('constants.employee_role_id'))->where('status', 1)->where('trash', 0)->get();
        $userData = '';
        $res    =   [];
        foreach ($users as $key => $value) {
            $userData = User::getUserDetails($value->id, 'emp');
            $userSalaryData = Salary::where('user_id', $userData->user_id)->where('salary_name', $request->salary_name)->first();
            if (!empty($userSalaryData)) {
                $userSalaryData->delete();
            }
            $userData->month =  $request->salary_name;

            $userSalary = SalarySetup::getUserSalarySetUp($userData);

            // dd($value);
            if (!empty($userSalary)) {
                $data =  self::salaryDayCalculation($request->salary_name, $userData);
                $userPolicy =   UserPolicy::getEmployeePolicy($userData->policy_id);
                if ($userSalary->salary_based_on == 0) {

                    $workingDays = $data->workingDays;

                    $oneDaySalary =  $userSalary->basic_salary / $data->monthDays;

                    $plusDaySalary = $minusDaySalary = 0;
                    if ($userPolicy->autual_month_day == 0) {
                        if ($data->actualMonthDays > $workingDays) {
                            $minusDay = $data->actualMonthDays - $workingDays;
                            $minusDaySalary = round($oneDaySalary * $minusDay, 2);
                        } elseif ($data->actualMonthDays < $workingDays) {
                            $plusDay = $workingDays - $data->actualMonthDays;
                            $plusDaySalary = round($oneDaySalary * $plusDay, 2);
                        }
                    } elseif ($userPolicy->autual_month_day == 1) {
                        $minusDay = $data->actualMonthDays - $workingDays;
                        $minusDaySalary = round($oneDaySalary * $minusDay, 2);
                    }

                    $gross_salary = $userSalary->basic_salary + $plusDaySalary - $minusDaySalary;
                    //   dd($gross_salary);

                    $dearness_allowance = $washing_allowance = $house_rant_allowance = $conveyance_allowance = $medical_allowance = $other_allowance = $overtimeAmount  =   0;

                    if (!empty($userSalary->dearness_allowance)) {
                        $dearness_allowance = floor(($userSalary->dearness_allowance / $data->monthDays) * $workingDays);
                    }

                    if (!empty($userSalary->washing_allowance)) {
                        $washing_allowance = floor(($userSalary->washing_allowance / $data->monthDays) * $workingDays);
                    }

                    if (!empty($userSalary->house_rant_allowance)) {
                        $house_rant_allowance = floor(($userSalary->house_rant_allowance / $data->monthDays) * $workingDays);
                    }

                    if (!empty($userSalary->conveyance_allowance)) {
                        $conveyance_allowance = floor(($userSalary->conveyance_allowance / $data->monthDays) * $workingDays);
                    }

                    if (!empty($userSalary->medical_allowance)) {
                        $medical_allowance = floor(($userSalary->medical_allowance / $data->monthDays) * $workingDays);
                    }

                    if (!empty($userSalary->other_allowance)) {
                        $other_allowance = floor(($userSalary->other_allowance / $data->monthDays) * $workingDays);
                    }

                    if (!empty($userSalary->other_allowance)) {
                        $other_allowance = floor(($userSalary->other_allowance / $data->monthDays) * $workingDays);
                    }

                    /// overtime hours pay
                    if ($userPolicy->eneble_overtime_working_day == 1) {
                        $overtimeAmount =  $userSalary->per_hour_overtime_amount * decimalHours($data->overtime);
                    }


                    $total = floatval($gross_salary) + floatval($dearness_allowance) + floatval($washing_allowance) + floatval($house_rant_allowance) + floatval($conveyance_allowance) + floatval($medical_allowance) + floatval($other_allowance) + floatval($overtimeAmount);

                    $pf_amount = $esi_amount = 0;
                    if ($userData->pf_status == 1) {
                        $pf_amount = round($gross_salary * config('constants.pf_month') / 100);
                    }

                    if ($userData->esic_status == 1) {
                        $esi_amount = round($total * config('constants.esic_persent') / 100);
                    }
                    //   $netAmount = $total - $pf_amount - $esi_amount;
                    $settlementAmount = SalarySettlement::getUserSettlementData($userData->month, $userData->id);

                    $totalAddition = floatval($total) + floatval($userSalary->fix_incentive) + floatval($userSalary->variable_incentive) + $settlementAmount->cr;


                    $totalDeduction = floatval($userSalary->deductions) - floatval($userSalary->welfare_fund) - floatval($pf_amount) - floatval($esi_amount) + $settlementAmount->dr;

                    $payableSalary  =   round($totalAddition - $totalDeduction);
                    // dd($workedDays);
                    $paymentData = array(
                        'admin_id'                              =>      $userData->admin_id,
                        'user_id'                               =>      $userData->user_id,
                        'salary_setup_id'                       =>      $userSalary->id,
                        'salary_name'                           =>      $request->salary_name,
                        'total_salary'                          =>      $payableSalary,
                        'present'                               =>      $data->totalpresents,
                        'paydays'                               =>      $workingDays,
                        'apply_pl'                              =>      $data->leaveday,
                        'apply_cl'                              =>      $data->lopDays,
                        'auto_leave'                            =>      $data->autoLeave,
                        'extrapresent'                          =>      $data->overday,
                        'total_working_minutes'                 =>      $data->workinghours,
                        'payment_status'                        =>      'unpaid',
                        'basic_salary'                          =>      $userSalary->basic_salary,
                        'dearness_allowance'                    =>      $dearness_allowance,
                        'washing_allowance'                     =>      $washing_allowance,
                        'house_rant_allowance'                  =>      $house_rant_allowance,
                        'conveyance_allowance'                  =>      $conveyance_allowance,
                        'medical_allowance'                     =>      $medical_allowance,
                        'other_allowance'                       =>      $other_allowance,
                        'fix_incentive'                         =>      $userSalary->fix_incentive,
                        'additional_salary_settlement_amount'   =>      $settlementAmount->cr,
                        'deduction_salary_settlement_amount'    =>      $settlementAmount->dr,
                        'deductions'                            =>      $userSalary->deductions,
                        'welfare_fund'                          =>      $userSalary->welfare_fund,
                        'variable_incentive'                    =>      $userSalary->variable_incentive,
                        'gross_salary'                          =>      $gross_salary,
                        'total_addition'                        =>      $totalAddition,
                        'total_deduction'                       =>      $totalDeduction,
                        'applicable_week_off'                   =>      $data->applicable_week_off,
                        'pf_amount'                             =>      $pf_amount,
                        'esi_amount'                            =>      $esi_amount,
                        'status'                                =>      1
                    );
                    //   dd($paymentData);

                    Salary::create($paymentData);
                } else {
                    $workingHours = $data->workinghours;
                    if ($userPolicy->eneble_overtime_working_day == 1) {
                        $workingHours =  strtotime($workingHours) + strtotime($data->overtime);
                    }
                    $payableSalary =  $userSalary->basic_salary * decimalHours($workingHours);
                    // dd($payableSalary);
                    $paymentData = array(
                        'admin_id'                      =>      $userData->admin_id,
                        'user_id'                       =>      $userData->user_id,
                        'salary_setup_id'               =>      $userSalary->id,
                        'salary_name'                   =>      $request->salary_name,
                        'total_salary'                  =>      $payableSalary,
                        'total_addition'                =>      $payableSalary,
                        'total_deduction'               =>      0,
                        'total_working_minutes'         =>      $workingHours,
                        'payment_status'                =>      'unpaid',
                        'basic_salary'                  =>      $userSalary->basic_salary,
                        'salary_based_on'               =>      1
                    );
                    //   dd($paymentData);
                    Salary::create($paymentData);
                }
            } else {
                $res[] = $userData->full_info;
            }
        }
        return $res;
    }

    public static function slipPdfGenrate($request)
    {
        $salarey = Salary::where('admin_id', $request->admin_id)->where('user_id', $request->user_id);
        $salarey->where('salary_name', $request->month);
        $salary = $salarey->first();
        // dd($salary);
        $amountinword = MyCommand::converNumber(intval(str_replace(',', '', $salary->total_salary)));
        $setup = SalarySetup::where('id', $salary->salary_setup_id)->first();
        $userData = User::getUserDetails($salary->user_id, 'emp');
        $userData->month   =  $salary->salary_name;
        $userPolicy =   UserPolicy::getEmployeePolicy($userData->policy_id);
        $imgPath = config('constants.setting_image_path');
        $imgDefaultPath = config('constants.default_image_path');
        $companyLogo = getSettingData('logo', $request->admin_id, 'val');
        $logo = ImageShow($imgPath, $companyLogo, 'icon', $imgDefaultPath);
        $variabls = [
            'salary' => $salary,
            'userData' => $userData,
            'setup'     => $setup,
            'amountinword' => $amountinword,
            'userPolicy' => $userPolicy,
            'logo' => $logo,
        ];
        // dd($variabls);
        $filename = $userData->name . '_slip.pdf';
        $pdf =  PDF::loadview('slippdf', $variabls);

        $pdf->setPaper('a4', 'portrait');
        $filePath = public_path('uploads/pdf');
        if (!File::exists($filePath)) {

            File::makeDirectory($filePath, $mode = 0755, true, true);
        }
        $output = $pdf->output();
        $pdfDownload = file_put_contents($filePath . '/' . $filename, $output);

        $url = asset('public/uploads/pdf/' . $filename);
        return $url;
    }
}
