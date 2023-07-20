<?php

namespace App\Http\Controllers\Backend;

use PDF;
use App\Models\User;
use App\Models\SalaryType;
use App\Exports\ExportUser;
use App\Models\SalarySetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;

class SalaySetupsController extends Controller
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
        // dd($thismodel); s

        $thismodel = SalarySetup::sortable(['created_at' => 'DESC'])
            ->leftJoin('users', function ($join) {
                $join->on('salary_setups.user_id', '=', 'users.id');
            });
            $thismodel->leftJoin('salary_types', function ($join) {
                $join->on('salary_setups.salary_type_id', '=', 'salary_types.id');
            });
            $thismodel->where('salary_setups.admin_id',Config::get('auth_detail')['admin_id']);
            $thismodel->select([
                'salary_setups.*', 'users.name','salary_types.salary_type'
            ]);
            // $thismodel->groupBy('salary_setups.id')->orderBy('id','asc');
            

        // dd(getQuery())

        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('users.name', 'LIKE', '%' . $keyword . '%')->orwhere('salary_setups.basic_salary', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (isset($filter['user_id']) && $filter['user_id'] != "") {
            $thismodel->where('users.id', $filter['user_id']);
        }
        if (!empty($filter['salary_type_id'])) {
            $keyword = $filter['salary_type_id'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('salary_type', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('salary_setups.status', $filter['status']);
        }

        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "User Id", "User Name", "Salary Type", "Basic Salary",
                "Gross Salary", "Dearness Allowance", "Washing Allowance", "House Rent Allowance", "Conveyance Allowance",
                "Medical Allowance", "Other Allowance", "Fix Incentive", "Variable Incentive", "Deductions", "Welfare Fund",
                "Affected Date", "Status", "Created At", "Updated At",
            ];
            $thismodel->select([
                'salary_setups.user_id', 'users.name', 'salary_types.salary_type', 'salary_setups.basic_salary',
                'salary_setups.gross_salary', 'salary_setups.dearness_allowance', 'salary_setups.washing_allowance',
                'salary_setups.house_rant_allowance', 'salary_setups.conveyance_allowance', 'salary_setups.medical_allowance',
                'salary_setups.other_allowance', 'salary_setups.fix_incentive', 'salary_setups.variable_incentive',
                'salary_setups.deductions', 'salary_setups.welfare_fund', 'salary_setups.affected_date',
                'salary_setups.status', 'salary_setups.created_at',
                'salary_setups.updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Salary Setup List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'Salary Setup.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Salary-Setup List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,
                ];


                $file = 'Salary-Setup.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        $setup = $thismodel->paginate($limit);
        // dd($setup);
        return view('backend.salary_setup.index', compact('setup', 'filter','user_list'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user_list = getUserList($data = []);
        $salary_type_list = SalaryType::where('status', '1')->get();
        //  dd($Leaveout);
        return view('backend.salary_setup.create', compact('user_list', 'salary_type_list'));
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

            'user_id' => ['required'],
            'salary_type_id' => ['required'],
            'salary_based_on' => ['required'],
            'basic_salary' => ['required'],
            'dearness_allowance' => ['nullable'],
            'washing_allowance' => ['nullable'],
            'per_hour_overtime_amount' => ['nullable'],
            'house_rant_allowance' => ['nullable'],
            'conveyance_allowance' => ['nullable'],
            'medical_allowance' => ['nullable'],
            'other_allowance' => ['nullable'],
            'fix_incentive' => ['nullable'],
            'variable_incentive' => ['nullable'],
            'deductions' => ['nullable'],
            'welfare_fund' => ['nullable'],
            'affected_date' => ['required'],

        ]);
        $loggedUser = Auth::user();
        $attributes['created_by'] = $loggedUser->admin_id;
        $attributes['admin_id'] = $loggedUser->admin_id;
        $attributes['status'] = 1;

        //  dd($attributes);
        SalarySetup::create($attributes);
        return redirect()->route('admin.payroll.salary-setup.index')
            ->with('success', __('Salary Setup created successfully.'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SalarySetup $salary_setup)
    {
        // dd($salary_setup);
        $user_list = getUserList($data = []);
        $salary_type_list = SalaryType::where('status', '1')->get();
        return view('backend.salary_setup.edit', compact('user_list', 'salary_type_list', 'salary_setup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalarySetup $salary_setup)
    {
        $attributes = request()->validate([
            'user_id' => ['required'],
            'salary_type_id' => ['required'],
            'salary_based_on' => ['required'],
            'basic_salary' => ['required'],
            'dearness_allowance' => ['nullable'],
            'washing_allowance' => ['nullable'],
            'per_hour_overtime_amount' => ['nullable'],
            'house_rant_allowance' => ['nullable'],
            'conveyance_allowance' => ['nullable'],
            'medical_allowance' => ['nullable'],
            'other_allowance' => ['nullable'],
            'fix_incentive' => ['nullable'],
            'variable_incentive' => ['nullable'],
            'deductions' => ['nullable'],
            'welfare_fund' => ['nullable'],
            'affected_date' => ['required'],
        ]);

        $loggedUser = Auth::user();
        $attributes['created_by'] = $loggedUser->admin_id;
        $salary_setup->update($attributes);
        return redirect()->route('admin.payroll.salary-setup.index')->with('success', __('Salary Setup Updated successfully.'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalarySetup $salary_setup)
    {
        $salary_setup->delete();
        return redirect()->route('admin.payroll.salary-setup.index')
            ->with('success', __('Salary Setup  deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        SalarySetup::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
