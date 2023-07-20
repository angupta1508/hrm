<?php

namespace App\Http\Controllers\Backend;

use PDF;
use App\Models\User;
use App\Models\Salary;
use App\Models\Attendance;
use App\Models\UserPolicy;
use App\Exports\ExportUser;
use App\Models\SalarySetup;
use Illuminate\Http\Request;
use App\CustomClass\RazorpayApi;
use App\Exports\BasicSalaryReport;
use Illuminate\Support\Facades\DB;
use App\Console\Commands\MyCommand;
use App\Http\Controllers\Controller;
use App\Models\UserBanker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;

class SalariesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        $limit =  config('constants.default_page_limit');
        $filter = $request->query();
        // $thismodel = Attendances::sortable(['created_at' => 'DESC']);  


        $thismodel = Salary::sortable(['created_at' => 'DESC'])
            ->leftJoin('users', function ($join) {
                $join->on('salaries.user_id', '=', 'users.id');
            })
            ->leftJoin('employees', function ($join) {
                $join->on('salaries.user_id', '=', 'employees.user_id');
            })
            ->select([
                'salaries.*', 'users.name', 'employees.employee_code'
            ]);
        // $thismodel->where('is_manual_attendance', '1');
        //  dd(getQuery($thismodel));

        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('users.name', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (isset($filter['user_id']) && $filter['user_id'] != "") {
            $thismodel->where('users.id', $filter['user_id']);
        }
        if (isset($filter['salary_name']) && $filter['salary_name'] != "") {
            $date = date('Y-m', strtotime($filter['salary_name']));
            $thismodel->where('salaries.salary_name', 'Like', '%' . $date . '%');
        }

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('salaries.status', $filter['status']);
        }

        $thismodel->where('users.admin_id', Config::get('auth_detail')['admin_id']);
        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Emp Code",
                "Employee Name",
                "Month",
                "Present",
                "Week Off",
                "Holiday",
                "Extra Present",
                "Apply Pl",
                "Apply Cl",
                "Auto Leave",
                "Paid Days",
                "BASIC SALARY",
                "Dearness ALLOWANCE",
                "WASHING ALLOWANCE",
                "HOUSE RENT ALLOWANCE",
                "CONVEYANCE ALLOWANCE",
                "MEDICAL ALLOWANCE",
                "OTHER ALLOWANCE",
                "DEDUCTION",
                "WELFARE FUND",
                "FIX INCENTIVE",
                'VARIABLE INCENTIVE',
                "PF AMOUNT",
                "ESI AMOUNT",
                "Additional Settlement AMOUNT",
                "DEDUCTION Settlement AMOUNT",
                "NET SALARY",
                "PAYMENT STATUS"
            ];
            $thismodel->select([
                'employees.employee_code',
                'users.name',
                'salaries.salary_name',
                'salaries.present',
                'salaries.applicable_week_off',
                'salaries.total_holidays',
                'salaries.extrapresent',
                'salaries.apply_pl',
                'salaries.apply_cl',
                'salaries.auto_leave',
                'salaries.paydays',
                'salaries.basic_salary',
                'salaries.dearness_allowance',
                'salaries.washing_allowance',
                'salaries.house_rant_allowance',
                'salaries.conveyance_allowance',
                'salaries.medical_allowance',
                'salaries.other_allowance',
                'salaries.deductions',
                'salaries.welfare_fund',
                'salaries.fix_incentive',
                'salaries.variable_incentive',
                'salaries.pf_amount',
                'salaries.esi_amount',
                'salaries.additional_salary_settlement_amount',
                'salaries.deduction_salary_settlement_amount',
                'salaries.total_salary',
                'salaries.payment_status'
            ]);

            $records = $thismodel->get();
            $total = [];
            $other = [];
            foreach ($records as $key => $val) {
                $total['total_basic_salary'][]    =   $val->basic_salary;
                $total['total_net_salary'][]      =   $val->total_salary;
            }
            $other['total_basic_salary']    =   array_sum($total['total_basic_salary']);
            $other['total_net_salary']      =   array_sum($total['total_net_salary']);


            $salaryName = !empty($filter['salary_name']) ? $filter['salary_name'] : date('M, Y', strtotime(date("Y-m")));
            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'          =>  'Salary List',
                'Salary YTD'    =>  'Statement For The Period ' . $salaryName,
                'Currency'      =>  'Default',
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new BasicSalaryReport($records, $headings, $header, $other), 'Salary.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }
                $variabls = [
                    'top_heading' => 'Salary List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,
                ];

                $file = 'Salary.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }
        // pr(getQuery($thismodel));die;
        $salary = $thismodel->paginate($limit);
        // dd($salary);
        return view('backend.salary.index', compact('salary', 'filter', 'user_list'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user_list = getUserList($data = []);
        $salaries = Salary::where('status', '1')->get();
        // dd($salary_setup);
        return view('backend.salary.create', compact('user_list', 'salaries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = request()->validate([
            'salary_name' => ['required'],
        ]);

        $responee   =   Salary::SalaryGenrate($request);
        // if (!empty($responee)) {
        //     return redirect()->route('admin.payroll.salary.index')
        //         ->with('error', __('Salary is not created this user.' . implode(', ', $responee)));
        // }
        return redirect()->route('admin.payroll.salary.index')
            ->with('success', __('Salary created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Salary $salary)
    {
        $amountinword = MyCommand::converNumber(intval(str_replace(',', '', $salary->total_salary)));
        $setup = SalarySetup::where('id', $salary->salary_setup_id)->first();
        $userData = User::getUserDetails($salary->user_id, 'emp');
        $userData->month   =  $salary->salary_name;
        $userPolicy =   UserPolicy::getEmployeePolicy($userData->policy_id);
        // dd($userattendance);
        return view('backend.salary.slip', compact('salary', 'userData', 'setup', 'amountinword', 'userPolicy'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Salary $salary)
    {
        $user_list = getUserList($data = []);

        // dd($attendanceReason);
        return view('backend.salary.edit', compact('user_list', 'salary'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salary $salary)
    {
        $attributes = request()->validate([
            'user_id' => ['required'],
            'salary_name' => ['required'],
            'total_salary' => ['required'],
        ]);

        $salary->update($attributes);
        return redirect()->route('admin.payroll.salary.index')->with('success', __('Salary Updated successfully.'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Salary $salary)
    {
        $salary->delete();
        return redirect()->route('admin.payroll.salary.index')
            ->with('success', __('Slip deleted successfully.'));
    }

    public function slipGenrate(Request $request)
    {
        $url = Salary::slipPdfGenrate($request);
        if ($url) {
            $result = array(
                "status" => 1,
                "url" => $url,
                "msg" => 'Success',
            );
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'No Record Found',
            );
        }
        return response()->json($result);
    }

    public function SalaryPay(Request $request)
    {
        return view('backend.salary.pay');
    }

    public function SalaryPayout(Request $request)
    {
        // $salariesList = Salary::where('admin_id', Config::get('auth_detail')['admin_id'])->where('salary_name', $request->pay_month)->where('payment_status', 'unpaid')->where('id', '15')->get();

        $salariesList = Salary::where('admin_id', Config::get('auth_detail')['admin_id'])->where('salary_name', $request->pay_month)->where('payment_status', 'unpaid')->get();
        foreach ($salariesList as $key => $val) {
            $userData = User::where('id', $val->user_id)->where('admin_id', Config::get('auth_detail')['admin_id'])->where('status', 1)->first();
            $userBankDetail = UserBanker::where('user_id', $val->user_id)->where('admin_id', Config::get('auth_detail')['admin_id'])->where('status', 1)->first();
            $RazorpayApi = new RazorpayApi();
            if (empty($userData->gateway_id)) {
                $response = $RazorpayApi->createContact($userData);
                if (!empty($response['data']['id']) && $response['status'] == 1) {
                    $userData->update(['gateway_id' => $response['data']['id']]);
                } else {
                    return back()->with('error', $response['msg']);
                }
            }
            // dd($userBankDetail);
            if (!empty($userData->gateway_id)) {
                if (empty($userBankDetail->gateway_fund_id)) {

                    $bankarray = array(
                        'contact_id'    => $userData->gateway_id,
                        'account_type'  => 'bank_account',
                        'account_name'  => $userBankDetail->account_name,
                        'ifsc_code'     => $userBankDetail->ifsc_code,
                        'account_no'    => $userBankDetail->account_no,
                        'amount'        => $val->total_salary
                    );

                    $response = $RazorpayApi->createFundAccount($bankarray);
                    if (!empty($response['data']['id']) && $response['status'] == 1) {
                        $userBankDetail->update(['gateway_fund_id' => $response['data']['id']]);
                    } else {
                        return back()->with('error', $response['msg']);
                    }
                }

                if (!empty($userBankDetail->gateway_fund_id)) {

                    $bankarray = array(
                        'gateway_fund_id'   => $userBankDetail->gateway_fund_id,
                        'amount'            => $val->total_salary,
                        'currency'          => 'INR',
                        'purpose'           => 'payout',
                        'narration'         => ''
                    );

                    $response = $RazorpayApi->createPayout($bankarray);
                    // dd($response);
                    if ($response['status'] == 1) {
                        $PaymentData = array(
                            'gateway_payment_id' => $response['data']['id'],
                        );
                        Salary::where('id', $val->id)->update($PaymentData);
                    } else {
                        return back()->with('error', $response['msg']);
                    }
                } else {
                    return back()->with('error', "Gateway Fund Id blank, !Please check.");
                }
            } else {
                return back()->with('error', 'Gateway Employee Id blank.!Please check.');
            }
        }
        return redirect()->route('admin.payroll.salary.index')
            ->with('success', 'Success.');
    }
}
