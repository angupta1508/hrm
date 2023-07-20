<?php

namespace App\Http\Controllers\Backend;

use PDF;
use DateTime;
use DatePeriod;
use DateInterval;
use App\Models\User;
use App\Models\Salary;
use App\Models\Employee;
use App\Models\Attendance;
use App\Exports\ExportUser;
use Illuminate\Http\Request;
use App\Models\AttendanceLog;
use App\Models\AttendanceReason;
use App\Exports\MonthlyWiseReport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;



class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request)
    // {

    //     $loggedUser = Auth::user();
    //     $limit = config('constants.default_page_limit');
    //     $attendanceReasons = getReasonList();
    //     $departments = getDepartmentList();
    //     $managers = getMangerList();
    //     $companies = getCompanyList();
    //     $locations = getLocationList();
    //     $shifts = getShiftList();
    //     $limit = config('constants.default_page_limit');
    //     $filter = $request->query();

    //     $thismodel = Attendance::sortable(['created_at' => 'DESC'])
    //         ->leftJoin('users', function ($join) {
    //             $join->on('attendances.user_id', '=', 'users.id');
    //         })->leftJoin('users as demo_user', function ($join) {
    //             $join->on('attendances.authorised_person_id', '=', 'demo_user.id');
    //         })
    //         ->leftJoin('attendance_reasons', function ($join) {
    //             $join->on('attendances.attendance_reason_id', '=', 'attendance_reasons.id');
    //         })->leftJoin('employees', function ($join) {
    //             $join->on('attendances.user_id', '=', 'employees.user_id');
    //         })->leftJoin('location_users', function ($join) {
    //             $join->on('attendances.user_id', '=', 'location_users.user_id');
    //         })
    //         ->select([
    //             'attendances.*', 'users.name as user_name', 'attendance_reasons.name', 'demo_user.name as author_name',
    //         ]);
    //     $thismodel->where('attendances.user_id', $loggedUser->id);
    //     //  dd(getQuery($thismodel));

    //     if (!empty($filter['search'])) {
    //         $keyword = $filter['search'];
    //         $thismodel->where(function ($query) use ($keyword) {
    //             $query->where('users.name', 'LIKE', '%' . $keyword . '%')->orwhere('employees.employee_code', 'LIKE', '%' . $keyword . '%')->orwhere('attendances.authorised_person_id', 'LIKE', '%' . $keyword . '%')->orwhere('attendances.attendance_reason_id', 'LIKE', '%' . $keyword . '%');
    //         });
    //     }

    //     if (!empty($filter['start_date'])) {
    //         $start_date_format = mysqlDateFormat($filter['start_date']);
    //         $thismodel->whereDate('attendances.created_at', '>=', $start_date_format);
    //     }
    //     if (!empty($filter['end_date'])) {
    //         $end_date_format = mysqlDateFormat($filter['end_date']);
    //         $thismodel->whereDate('attendances.created_at', '<=', $end_date_format);
    //     }

    //     if (isset($filter['attendance_reason_id']) && $filter['attendance_reason_id'] != "") {
    //         $thismodel->where('attendances.attendance_reason_id', $filter['attendance_reason_id']);
    //     }
    //     if (isset($filter['shift_id']) && $filter['shift_id'] != "") {
    //         $thismodel->where('employees.shift_id', $filter['shift_id']);
    //     }
    //     if (isset($filter['department_id']) && $filter['department_id'] != "") {
    //         $thismodel->where('employees.department_id', $filter['department_id']);
    //     }
    //     if (isset($filter['location_id']) && $filter['location_id'] != "") {
    //         $thismodel->where('location_users.location_id', $filter['location_id']);
    //     }
    //     if (isset($filter['company_id']) && $filter['company_id'] != "") {
    //         $thismodel->where('employees.company_id', $filter['company_id']);
    //     }
    //     if (isset($filter['attendance_type']) && $filter['attendance_type'] != "") {
    //         $thismodel->where('attendances.attendance_type', $filter['attendance_type']);
    //     }
    //     if (isset($filter['status']) && $filter['status'] != "") {
    //         $thismodel->where('attendances.status', $filter['status']);
    //     }
    //     if (isset($filter['authorised_person_id']) && $filter['authorised_person_id'] != "") {
    //         $thismodel->where('employees.authorised_person_id', $filter['authorised_person_id']);
    //     }
    //     if (isset($filter['designation_id']) && $filter['designation_id'] != "") {
    //         $thismodel->where('employees.designation_id', $filter['designation_id']);
    //     }

    //     $thismodel->groupBy('attendances.id')->orderBy('id','desc');
    //     // excel_export
    //     if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
    //         $headings = [
    //             "authorised_person", "attendances reason", "attendance_type", "from_time",
    //             "request_remark", "request_hard_copy", "attendance_date",
    //             "approve_remark", "approve_date", "approved_by",
    //             "Status", "Created_at",
    //         ];
    //         $thismodel->select([
    //             'users.name', 'attendance_reasons.name', 'attendances.attendance_type', 'attendances.from_time',
    //             'attendances.to_time', 'attendances.request_remark', 'attendances.request_hard_copy', 'attendances.attendance_date',
    //             'attendances.approve_remark', 'attendances.approve_date', 'attendances.approved_by',
    //             'attendances.status',
    //             'attendances.created_at',
    //         ]);
    //         $records = $thismodel->get();

    //         $header = [
    //             'Company Name'  =>  Config::get('company_name'),
    //             'File'        =>  'Attendance List'
    //         ];

    //         if (isset($filter['excel_export'])) {
    //             return Excel::download(new ExportUser($records, $headings, $header), 'attendances.csv');
    //         } else if (isset($filter['pdf_export'])) {
    //             $tabel_keys = [];
    //             if ($records->count() > 0) {
    //                 $tabel_keys = array_keys($records[0]->toArray());
    //             }

    //             $variabls = [
    //                 'top_heading' => 'Attendances List',
    //                 'headings' => $headings,
    //                 'tabel_keys' => $tabel_keys,
    //                 'records' => $records,
    //                 'header' => $header,

    //             ];

    //             $file = 'Attendances.pdf';
    //             $pdf = PDF::loadview('pdf', $variabls);

    //             if (count($headings) > 6) {
    //                 $pdf->setPaper('a4', 'landscape');
    //             }

    //             return $pdf->download($file);
    //         }
    //     }

    //     $attendance = $thismodel->paginate($limit);

    //     // dd($attendance);
    //     return view('backend.attendance.index', compact('attendance', 'filter', 'shifts', 'departments', 'locations', 'companies', 'managers', 'attendanceReasons'))->with('i', (request()->input('page', 1) - 1) * $limit);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function attendanceReport(Request $request)
    {
        $loggedUser = Auth::user();
        $limit = config('constants.default_page_limit');
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        $departments = getDepartmentList();
        $managers = getMangerList();
        $companies = getCompanyList();
        $designation = getDesignationList();
        $locations = getLocationList();
        $shifts = getShiftList();
        $filter = $request->query();
        // $thismodel = Attendances::sortable(['created_at' => 'DESC']);

        $thismodel = Attendance::sortable(['created_at' => 'DESC'])->leftJoin('users', function ($join) {
            $join->on('attendances.user_id', '=', 'users.id');
        })->leftJoin('employees', function ($join) {
            $join->on('attendances.user_id', '=', 'employees.user_id');
        })->leftJoin('location_users', function ($join) {
            $join->on('attendances.user_id', '=', 'location_users.user_id');
        })->select([
            'attendances.*', 'users.name',
        ])->groupBy('attendances.id');
        $thismodel->where('users.admin_id', $loggedUser->admin_id);

        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('attendances.request_remark', 'LIKE', '%' . $keyword . '%')->orwhere('employees.employee_code', 'LIKE', '%' . $keyword . '%')
                    ->orwhere('users.name', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (isset($filter['user_id']) && $filter['user_id'] != "") {
            $thismodel->where('users.id', $filter['user_id']);
        }
        if (isset($filter['shift_id']) && $filter['shift_id'] != "") {
            $thismodel->where('employees.shift_id', $filter['shift_id']);
        }
        if (isset($filter['department_id']) && $filter['department_id'] != "") {
            $thismodel->where('employees.department_id', $filter['department_id']);
        }
        if (isset($filter['location_id']) && $filter['location_id'] != "") {
            $thismodel->where('location_users.location_id', $filter['location_id']);
        }
        if (isset($filter['company_id']) && $filter['company_id'] != "") {
            $thismodel->where('employees.company_id', $filter['company_id']);
        }
        if (!empty($filter['start_date'])) {
            $start_date_format = mysqlDateFormat($filter['start_date']);
            $thismodel->whereDate('attendances.created_at', '>=', $start_date_format);
        }
        if (!empty($filter['end_date'])) {
            $end_date_format = mysqlDateFormat($filter['end_date']);
            $thismodel->whereDate('attendances.created_at', '<=', $end_date_format);
        }

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('attendances.attendance_status', $filter['status']);
        }

        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "User", "From Time",
                "To Time", "Working Hours", "Overtime", "Early In", "Late Out", "Late In", "Early Out", "Request Remark",
                "Status", "Created_at",
            ];
            $thismodel->select([
                'users.name', 'attendances.from_time',
                'attendances.to_time', 'attendances.working_hours', 'attendances.overtime', 'attendances.early_in', 'attendances.late_out',
                'attendances.late_in', 'attendances.early_out', 'attendances.request_remark',
                'attendances.attendance_status',
                'attendances.created_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'Excel'        =>  'Attendance List'
            ];

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Attendance List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings, $header), 'AttendanceReport.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Attendances List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,
                ];

                $file = 'AttendanceReport.pdf';
                $pdf = PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        $attendance = $thismodel->paginate($limit);
        // dd($attendances);
        return view('backend.attendance.attendance_report', compact('attendance', 'filter', 'shifts', 'departments','designation', 'locations', 'companies', 'managers', 'user_list'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    public function missPunchReport(Request $request)
    {
        $loggedUser = Auth::user();
        $limit = config('constants.default_page_limit');
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        $departments = getDepartmentList();
        $managers = getMangerList();
        $designation = getDesignationList();
        $companies = getCompanyList();
        $locations = getLocationList();
        $shifts = getShiftList();
        $filter = $request->query();
        
        $thismodel = Attendance::leftJoin('users', function ($join) {
            $join->on('attendances.user_id', '=', 'users.id');
        })->leftJoin('employees', function ($join) {
            $join->on('attendances.user_id', '=', 'employees.user_id');
        })->leftJoin('location_users', function ($join) {
            $join->on('attendances.user_id', '=', 'location_users.user_id');
        })->select(['attendances.*', 'users.name'])
        ->groupBy('attendances.id');

        $thismodel->where(function ($query)  {
            $query->whereNull('attendances.from_time');
            $query->orwhereNull('attendances.to_time');
        });
        // pr(getQuery($thismodel));die; 
        $thismodel->where('users.admin_id', $loggedUser->admin_id);

        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('users.name', 'LIKE', '%' . $keyword . '%')->orwhere('attendances.name', 'LIKE', '%' . $keyword . '%')->orwhere('employees.employee_code', 'LIKE', '%' . $keyword . '%')
                    ;
            });
        }
        if (isset($filter['user_id']) && $filter['user_id'] != "") {
            $thismodel->where('users.id', $filter['user_id']);
        }
        if (isset($filter['shift_id']) && $filter['shift_id'] != "") {
            $thismodel->where('employees.shift_id', $filter['shift_id']);
        }
        if (isset($filter['designation_id']) && $filter['designation_id'] != "") {
            $thismodel->where('employees.designation_id', $filter['designation_id']);
        }
        if (isset($filter['department_id']) && $filter['department_id'] != "") {
            $thismodel->where('employees.department_id', $filter['department_id']);
        }
        if (isset($filter['location_id']) && $filter['location_id'] != "") {
            $thismodel->where('location_users.location_id', $filter['location_id']);
        }
        if (isset($filter['company_id']) && $filter['company_id'] != "") {
            $thismodel->where('employees.company_id', $filter['company_id']);
        }
        if (!empty($filter['request_date'])) {
            $start_date_format = mysqlDateFormat($filter['request_date']);
            $thismodel->whereDate('attendances.attendance_date', '=', $start_date_format);
        }

        // pr(getQuery($thismodel));die;
        // dd($thismodel->get());
        $misspunchreport = $thismodel->paginate($limit);
        return view('backend.attendance.miss_punch_report', compact('misspunchreport', 'filter', 'shifts', 'departments','designation', 'locations', 'companies', 'managers', 'user_list'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    public  function attendanceLog(Request $request)
    {
        $loggedUser = Auth::user();
        $departments = getDepartmentList();
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        $managers = getMangerList();
        $companies = getCompanyList();
        $locations = getLocationList();
        $shifts = getShiftList();
        $limit = config('constants.default_page_limit');
        $filter = $request->query();
        // $thismodel = Attendances::sortable(['created_at' => 'DESC']);

        $thismodel = AttendanceLog::sortable(['created_at' => 'DESC'])->leftJoin('users', function ($join) {
            $join->on('attendance_logs.user_id', '=', 'users.id');
        })->leftJoin('employees', function ($join) {
            $join->on('attendance_logs.user_id', '=', 'employees.user_id');
        })->leftJoin('location_users', function ($join) {
            $join->on('attendance_logs.user_id', '=', 'location_users.user_id');
        })->select([
            'attendance_logs.*', 'users.name',
        ])->groupBy('attendance_logs.id')->orderBy('attendance_logs.id', 'DESC');

        $thismodel->where('users.admin_id', $loggedUser->admin_id);

        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('attendance_logs.punch_type', 'LIKE', '%' . $keyword . '%')->orwhere('employees.employee_code', 'LIKE', '%' . $keyword . '%')
                    ->orwhere('users.name', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (isset($filter['user_id']) && $filter['user_id'] != "") {
            $thismodel->where('users.id', $filter['user_id']);
        }
        if (!empty($filter['start_date'])) {
            $start_date_format = mysqlDateFormat($filter['start_date']);
            // dd($start_date_format);
            $thismodel->whereDate('attendance_logs.created_at', '>=', $start_date_format);
        }
        if (!empty($filter['end_date'])) {
            $end_date_format = mysqlDateFormat($filter['end_date']);
            $thismodel->whereDate('attendance_logs.created_at', '<=', $end_date_format);
        }
        if (isset($filter['shift_id']) && $filter['shift_id'] != "") {
            $thismodel->where('employees.shift_id', $filter['shift_id']);
        }
        if (isset($filter['department_id']) && $filter['department_id'] != "") {
            $thismodel->where('employees.department_id', $filter['department_id']);
        }
        if (isset($filter['location_id']) && $filter['location_id'] != "") {
            $thismodel->where('location_users.location_id', $filter['location_id']);
        }
        if (isset($filter['company_id']) && $filter['company_id'] != "") {
            $thismodel->where('employees.company_id', $filter['company_id']);
        }
        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "user", "admin_id",
                "punch_type", "from_where", "punch_time", "image", "Created_at",
            ];
            $thismodel->select([
                'users.name', 'attendance_logs.admin_id',
                'attendance_logs.punch_type', 'attendance_logs.from_where', 'attendance_logs.punch_time', 'attendance_logs.image',
                'attendance_logs.created_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Attendance List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings, $header), 'Attendance-log.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Attendances List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,
                ];

                $file = 'AttendanceLog.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }
        // pr(getQuery($thismodel));die;
        $attendance_log = $thismodel->paginate($limit);
        //    dd($attendance_log);
        return view('backend.attendance.attendance_log', compact('attendance_log', 'filter', 'shifts', 'departments', 'locations', 'companies', 'managers', 'user_list'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    public function monthWiseReport(Request $request)
    {

        $limit = config('constants.default_page_limit');
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        $filter = $request->query();
        // $thismodel = Attendances::sortable(['created_at' => 'DESC']);
        $user = User::leftJoin('employees', function ($join) {
            $join->on('users.id', '=', 'employees.user_id');
        });
        $user->select(['users.*']);
        $user->where('users.admin_id', Config::get('auth_detail')['id'])->where('users.role_id', config('constants.employee_role_id'))->where('users.status', 1);
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $user->where(function ($query) use ($keyword) {
                $query->where('users.name', 'LIKE', '%' . $keyword . '%')->orwhere('employees.employee_code', 'LIKE', '%' . $keyword . '%')
                    ->orwhere('users.username', 'LIKE', '%' . $keyword . '%')->orwhere('users.email', 'LIKE', '%' . $keyword . '%')->orwhere('users.mobile', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (isset($filter['user_id']) && $filter['user_id'] != "") {
            $user->where('users.id', $filter['user_id']);
        }

        if (isset($filter['excel_export'])) {
            $users = $user->get();
        } else {
            $users = $user->paginate($limit);
        }
        $attendanceArray    =   [];
        $userData = '';
        foreach ($users as $key => $value) {
            $today = now();
            $userData = User::getUserDetails($value->id, 'emp');
            $attendanceArray[$key]['id'] = $key + 1;
            $attendanceArray[$key]['employee_code'] = $userData->employee_code;
            $attendanceArray[$key]['name'] = $userData->name;
            $attendanceArray[$key]['hire_date'] = $userData->hire_date;
            $attendanceArray[$key]['department_name'] = $userData->department_name;

            $attributes = [
                'admin_id' => Config::get('auth_detail')['admin_id'],
                'user_id' => $value->id,
                'month' =>  !empty($filter['month']) ? $filter['month'] : $today,
            ];
            Attendance::fillMissingAttendance($attributes);

            $attendance = Attendance::where('attendances.admin_id', Config::get('auth_detail')['admin_id']);
            $attendance->where('attendances.user_id', $value->id);
            if (empty($filter['month'])) {
                $attendance->whereYear('attendances.attendance_date', $today->year);
                $attendance->whereMonth('attendances.attendance_date', $today->month);
            }
            if (!empty($filter['month'])) {
                $year  = date('Y', strtotime($filter['month']));
                $month  = date('m', strtotime($filter['month']));
                $attendance->whereYear('attendances.attendance_date', $year);
                $attendance->whereMonth('attendances.attendance_date', $month);
            }

            $attendances = $attendance->orderBy('attendance_date', 'ASC')->get()->toArray();

            $getDate = [
                'month' =>  !empty($filter['month']) ? $filter['month'] : $today,
            ];
            $totalDatees =  getMonthDates($attributes);
            // dd($totalDatees);
            foreach ($totalDatees as $dae => $date) {
                $hrh = findArrayOfColumn($attendances, 'attendance_date', $date);
                if (isset($hrh)) {
                    $attendanceArray[$key]['attendance'][prettyDateFormet($date, 'date')] = $attendances[$hrh];
                } else {
                    $attendanceArray[$key]['attendance'][prettyDateFormet($date, 'date')] = [];
                }
            }
        }
        if (isset($filter['excel_export'])) {
            return Excel::download(new MonthlyWiseReport($attendanceArray, $filter), 'Month-wise-attendance.xlsx');
        }
        return view('backend.attendance.month_wise_report', compact('attendanceArray', 'users', 'filter', 'user_list'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    public function presentRegister(Request $request)
    {
        $limit = config('constants.default_page_limit');
        $filter = $request->query();
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        $user = User::leftJoin('employees', function ($join) {
            $join->on('users.id', '=', 'employees.user_id');
        });
        $user->select(['users.*']);
        $user->where('users.admin_id', Config::get('auth_detail')['id'])->where('users.role_id', config('constants.employee_role_id'))->where('users.status', 1);
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $user->where(function ($query) use ($keyword) {
                $query->where('users.name', 'LIKE', '%' . $keyword . '%')->orwhere('employees.employee_code', 'LIKE', '%' . $keyword . '%')
                    ->orwhere('users.username', 'LIKE', '%' . $keyword . '%')->orwhere('users.email', 'LIKE', '%' . $keyword . '%')->orwhere('users.mobile', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (isset($filter['user_id']) && $filter['user_id'] != "") {
            $user->where('users.id', $filter['user_id']);
        }

        if (isset($filter['excel_export'])) {
            $users = $user->get();
        } else {
            $users = $user->paginate($limit);
        }

        $attendanceArray = [];

        $userData = '';
        foreach ($users as $key => $value) {
            $today = now();
            $userData = User::getUserDetails($value->id, 'emp');
            $attendanceArray[$key]['id'] = $key + 1;
            $attendanceArray[$key]['employee_code'] = $userData->employee_code;
            $attendanceArray[$key]['name'] = $userData->name;
            $attendanceArray[$key]['hire_date'] = $userData->hire_date;
            $attendanceArray[$key]['department_name'] = $userData->department_name;

            $attributes = [
                'admin_id' => Config::get('auth_detail')['admin_id'],
                'user_id' => $value->id,
                'month' =>  !empty($filter['month']) ? $filter['month'] : $today,
            ];
            Attendance::fillMissingAttendance($attributes);

            $attendance = Attendance::where('attendances.admin_id', Config::get('auth_detail')['admin_id']);
            $attendance->where('attendances.user_id', $value->id);
            $year  =    $today->year;
            $month  =    $today->month;
            if (empty($filter['month'])) {
                $attendance->whereYear('attendances.attendance_date', $year);
                $attendance->whereMonth('attendances.attendance_date', $month);
            }
            if (!empty($filter['month'])) {
                $year  = date('Y', strtotime($filter['month']));
                $month  = date('m', strtotime($filter['month']));
                $attendance->whereYear('attendances.attendance_date', $year);
                $attendance->whereMonth('attendances.attendance_date', $month);
            }

            $attendances = $attendance->orderBy('attendance_date', 'ASC')->get()->toArray();

            $salaryData = Salary::salaryDayCalculation($year . '-' . $month, $userData);
            // pr(getQuery($attendances));die;
            $getDate = [
                'month' =>  !empty($filter['month']) ? $filter['month'] : $today,
            ];
            $totalDatees =  getMonthDates($attributes);
            // dd($totalDatees);
            foreach ($totalDatees as $dae => $date) {
                $hrh = findArrayOfColumn($attendances, 'attendance_date', $date);
                if (isset($hrh)) {
                    $attendanceArray[$key]['attendance'][prettyDateFormet($date, 'date')] = $attendances[$hrh];
                } else {
                    $attendanceArray[$key]['attendance'][prettyDateFormet($date, 'date')] = [];
                }
            }

            $array = (object)[
                'user_id'           =>  $value->id,
                'admin_id'          =>  Config::get('auth_detail')['admin_id'],
                'month'             =>  !empty($filter['month']) ? $filter['month'] : $today,
            ];

            $present =  $salaryData->totalpresents;
            $absent  =  $salaryData->lopDays;
            $weekoff =  $salaryData->weekOff;
            $hoildays =  $salaryData->hoildays;

            $auto_leave    =   !empty($salaryData->autoLeave) ? $salaryData->autoLeave : 0;
            $leave = $salaryData->leaveday;
            $extra_present =  $salaryData->overday;


            $attendanceArray[$key]['present']               =  ($present > 0) ? $present : 0;
            $attendanceArray[$key]['leave_day']             =  ($leave > 0) ? $leave : 0;
            $attendanceArray[$key]['extra_present']         =  ($extra_present > 0) ? $extra_present : 0;
            $attendanceArray[$key]['week_off']              =  ($weekoff > 0) ? $weekoff : 0;
            $attendanceArray[$key]['applicable_week_off']   =  ($salaryData->applicable_week_off > 0) ? $salaryData->applicable_week_off : 0;
            $attendanceArray[$key]['auto_leave']            =  ($auto_leave > 0) ? $auto_leave : 0;
            $attendanceArray[$key]['hoildays']              =  ($hoildays > 0) ? $hoildays : 0;
            $attendanceArray[$key]['absent']                =  ($absent > 0) ? $absent : 0;
            $attendanceArray[$key]['total_attendance']      =  ($salaryData->workingDays > 0) ? $salaryData->workingDays : 0;
        }

        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {

            $excelArray = [];
            $heading = [];
            foreach ($attendanceArray[0] as $key => $atten) {
                if (!is_array($atten)) {
                    $heading[] = ucwords(str_replace('_', ' ', $key));
                } else {
                    foreach ($atten as $k => $val) {
                        $heading[] = $k;
                    }
                }
            }
            foreach ($attendanceArray as $key => $atten) {
                $excelArray[$key][] = $atten['id'];
                $excelArray[$key][] = $atten['employee_code'];
                $excelArray[$key][] = $atten['name'];
                $excelArray[$key][] = $atten['hire_date'];
                $excelArray[$key][] = $atten['department_name'];
                foreach ($atten['attendance'] as $dd => $value) {
                    $excelArray[$key][] = !empty($value['attendance_status']) ? $value['attendance_status'] : '';
                }
                $excelArray[$key][] =  $atten['present'];
                $excelArray[$key][] =  $atten['leave_day'];
                $excelArray[$key][] =  $atten['extra_present'];
                $excelArray[$key][] =  $atten['week_off'];
                $excelArray[$key][] =  $atten['applicable_week_off'];
                $excelArray[$key][] =  $atten['auto_leave'];
                $excelArray[$key][] =  $atten['hoildays'];
                $excelArray[$key][] =  $atten['absent'];
                $excelArray[$key][] =  $atten['total_attendance'];
            }
            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Attendance List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($excelArray, $heading, $header), 'present-register.csv');
            }
        }
        // dd($attendanceArray);
        return view('backend.attendance.presentregister', compact('attendanceArray', 'users', 'filter', 'user_list'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $shifts = getShiftList();
        $manager = getMangerList();
        $user_list = getUserList();
        $attendance = AttendanceReason::where('status', '1')->get();
        // dd($attendance);
        return view('backend.attendance.create', compact('user_list', 'attendance', 'manager', 'shifts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Attendance $attendance)
    {

        // dd($request);
        $attributes = request()->validate([
            'user_id' => ['required'],
            'attendance_reason_id' => ['required'],
            'from_time' => ['required'],
            'to_time' => ['required'],
            'request_remark' => ['required'],
            'attendance_date' => ['required'],
            'request_hard_copy' => ['nullable'],
        ]);

        $author = Employee::where('user_id', $attributes['user_id'])->first();
        $attributes['authorised_person_id'] = !empty($author->authorised_person_id) ? $author->authorised_person_id : '';
        $attributes['shift_id'] = !empty($author->shift_id) ? $author->shift_id : '';
        $attributes['admin_id'] = Config::get('auth_detail')['admin_id'];
        // dd($attributes);
        $attributes['is_manual_attendance'] = 1;

        if (!empty($attributes['request_hard_copy'])) {
            $imgKey = 'request_hard_copy';
            $imgPath = public_path(config('constants.attendance_image_path'));
            $filename = UploadImage($request, $imgPath, $imgKey);
            if (!empty($filename)) {
                $attributes['request_hard_copy'] = $filename;
            }
        }

        $attend = Attendance::where('attendance_date', $request->attendance_date)->where('user_id', $request->user_id)->first();
        if (!empty($attend)) {
            $attend->update($attributes);
        } else {
            Attendance::create($attributes);
        }
        // dd($attributes);

        return redirect()->route('admin.attendence.attendance.index')
            ->with('success', __('Manual Ateendance created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $attend_data = Attendance::sortable(['created_at' => 'DESC'])
            ->leftJoin('users AS authorised_person', function ($join) {
                $join->on('attendances.authorised_person_id', '=', 'authorised_person.id');
            })->leftJoin('users AS demo_user', function ($join) {
                $join->on('attendances.user_id', '=', 'demo_user.id');
            })->leftJoin('attendance_reasons', function ($join) {
                $join->on('attendances.attendance_reason_id', '=', 'attendance_reasons.id');
            })->leftJoin('users as aprroved_by', function ($join) {
                $join->on('attendances.user_id', '=', 'aprroved_by.id');
            })->leftJoin('shifts', function ($join) {
                $join->on('attendances.shift_id', '=', 'shifts.id');
            })->select([
                'attendances.*', 'authorised_person.name as author_name', 'aprroved_by.name as approve_name', 'attendance_reasons.name',
                'demo_user.name as user_name', 'shifts.shift_name',
            ])->where('attendances.id', $id)->first();
        return view('backend.attendance.attendance_approve', compact('id', 'attend_data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        $shifts = getShiftList();
        $user_list = getUserList();
        $attendanceReason = AttendanceReason::where('status', '1')->get();
        // dd($attendanceReason);
        return view('backend.attendance.edit', compact('user_list', 'attendanceReason', 'attendance', 'shifts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        $attributes = request()->validate([
            'user_id' => ['required'],
            'attendance_reason_id' => ['required'],
            'from_time' => ['required'],
            'to_time' => ['required'],
            'request_remark' => ['required'],
            'attendance_date' => ['required'],
            'request_hard_copy' => ['nullable'],

        ]);

        $author = Employee::where('user_id', $attributes['user_id'])->first();
        $attributes['authorised_person_id'] = !empty($author->authorised_person_id) ? $author->authorised_person_id : '';
        $attributes['shift_id'] = !empty($author->shift_id) ? $author->shift_id : '';

        if (!empty($attributes['request_hard_copy'])) {
            $imgKey = 'request_hard_copy';
            $imgPath = public_path(config('constants.attendance_image_path'));
            $filename = UploadImage($request, $imgPath, $imgKey, $attendance->request_hard_copy);
            if (!empty($filename)) {
                $attributes['request_hard_copy'] = $filename;
            }
        }

        $attendance->update($attributes);
        return redirect()->route('admin.attendence.attendance.index')->with('success', __('Attendances Updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Attendance $attendance)
    {
        // dd('ghfdhg');
        $attendance->delete();
        return redirect()->route('admin.attendence.attendance.attendanceLog')
            ->with('success', __('Attendance deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        Attendance::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
