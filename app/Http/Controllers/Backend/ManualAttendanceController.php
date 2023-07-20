<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\ExportUser;
use App\Exports\MonthlyWiseReport;
use App\Models\Attendance;
use App\Models\ManualAttendance;
use App\Models\AttendanceLog;
use App\Models\AttendanceReason;
use App\Models\Employee;
use App\Models\User;
use DateInterval;
use DatePeriod;
use DateTime;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;


class ManualAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $loggedUser = Auth::user();
        // dd($loggedUser);
        $limit = config('constants.default_page_limit');
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        $attendanceReasons = getReasonList();
        $departments = getDepartmentList();
        $managers = getMangerList();
        $companies = getCompanyList();
        $locations = getLocationList();
        $shifts = getShiftList();
        $limit = config('constants.default_page_limit');
        $filter = $request->query();


        $thismodel = ManualAttendance::sortable(['created_at' => 'DESC'])
            ->leftJoin('users', function ($join) {
                $join->on('manual_attendances.user_id', '=', 'users.id');
            })->leftJoin('users as demo_user', function ($join) {
                $join->on('manual_attendances.authorised_person_id', '=', 'demo_user.id');
            })
            ->leftJoin('attendance_reasons', function ($join) {
                $join->on('manual_attendances.attendance_reason_id', '=', 'attendance_reasons.id');
            })->leftJoin('employees', function ($join) {
                $join->on('manual_attendances.user_id', '=', 'employees.user_id');
            })->leftJoin('location_users', function ($join) {
                $join->on('manual_attendances.user_id', '=', 'location_users.user_id');
            })
            ->select([
                'manual_attendances.*', 'users.name as user_name', 'attendance_reasons.name', 'demo_user.name as author_name',
            ]);
        $thismodel->groupBy('manual_attendances.id');

        $thismodel->where('manual_attendances.admin_id', $loggedUser->admin_id);
        //  dd(getQuery($thismodel));

        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('users.name', 'LIKE', '%' . $keyword . '%')->orwhere('employees.employee_code', 'LIKE', '%' . $keyword . '%')->orwhere('manual_attendances.authorised_person_id', 'LIKE', '%' . $keyword . '%')->orwhere('manual_attendances.attendance_reason_id', 'LIKE', '%' . $keyword . '%');
            });
          
        }
        if (isset($filter['user_id']) && $filter['user_id'] != "") {
            $thismodel->where('users.id', $filter['user_id']);
        }
        if (!empty($filter['request_date'])) {
            $start_date_format = mysqlDateFormat($filter['request_date']);
            $thismodel->whereDate('manual_attendances.attendance_date', '=', $start_date_format);
        }
        // if (!empty($filter['start_date'])) {
        //     $start_date_format = mysqlDateFormat($filter['start_date']);
        //     $thismodel->whereDate('manual_attendances.created_at', '>=', $start_date_format);
        // }
        // if (!empty($filter['end_date'])) {
        //     $end_date_format = mysqlDateFormat($filter['end_date']);
        //     $thismodel->whereDate('manual_attendances.created_at', '<=', $end_date_format);
        // }

        if (isset($filter['attendance_reason_id']) && $filter['attendance_reason_id'] != "") {
            $thismodel->where('manual_attendances.attendance_reason_id', $filter['attendance_reason_id']);
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
        if (isset($filter['attendance_type']) && $filter['attendance_type'] != "") {
            $thismodel->where('manual_attendances.attendance_type', $filter['attendance_type']);
        }
        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('manual_attendances.status', $filter['status']);
        }
        if (isset($filter['authorised_person_id']) && $filter['authorised_person_id'] != "") {
            $thismodel->where('employees.authorised_person_id', $filter['authorised_person_id']);
        }
        if (isset($filter['designation_id']) && $filter['designation_id'] != "") {
            $thismodel->where('employees.designation_id', $filter['designation_id']);
        }


        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "authorised person ",
                "manual attendances reason",
                "from time",
                "to time",
                "request remark",
                "request hard copy",
                "attendance date",
                "approve remark",
                "approve date", 
                "approved by",
                "Status", 
                "Created at",
            ];
            $thismodel->select([
                'manual_attendances.authorised_person_id',
                'attendance_reasons.name',
                'manual_attendances.from_time',
                'manual_attendances.to_time',
                'manual_attendances.request_remark',
                'manual_attendances.request_hard_copy',
                'manual_attendances.attendance_date',
                'manual_attendances.approve_remark',
                'manual_attendances.approve_date',
                'manual_attendances.approved_by',
                'manual_attendances.status',
                'manual_attendances.created_at',
            ]);
            $records = $thismodel->get();
            foreach($records as $key => $value){
                if($value->status == 0){
                    $records[$key]->status = "Pending";
                }elseif($value->status == 1){
                    $records[$key]->status = "Approved";
                }elseif($value->status == 2){
                    $records[$key]->status = "Cancel";
                }
            }
            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Manual attendances List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'Manual attendances.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Manual attendances List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,    
                ];

                $file = 'manual_attendances.pdf';
                $pdf = PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        $attendance = $thismodel->paginate($limit);

        // dd($attendance);
        return view('backend.attendance.index', compact('attendance', 'filter', 'shifts', 'departments', 'locations', 'companies', 'managers', 'attendanceReasons','user_list'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $loggedUser = Auth::user();
        $shifts = getShiftList();
        $manager = getMangerList();
        $user_list = getUserList();
        $attendance = AttendanceReason::where([['status', '1'],['attendance_reasons.admin_id', $loggedUser->admin_id]])->get();
        // dd($attendance);
        return view('backend.attendance.create', compact('user_list', 'attendance', 'manager', 'shifts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ManualAttendance $manualAttendance)
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
        // dd($author);
        $attributes['authorised_person_id'] = !empty($author->authorised_person_id) ? $author->authorised_person_id : '';
        $attributes['shift_id'] = !empty($author->shift_id) ? $author->shift_id : '';
        $attributes['admin_id'] = Config::get('auth_detail')['admin_id'];
        // dd($author);

        if (!empty($attributes['request_hard_copy'])) {
            $imgKey = 'request_hard_copy';
            $imgPath = public_path(config('constants.attendance_image_path'));
            $filename = UploadImage($request, $imgPath, $imgKey);
            if (!empty($filename)) {
                $attributes['request_hard_copy'] = $filename;
            }
        }

        $attend = ManualAttendance::where('attendance_date', $request->attendance_date)->where('user_id', $request->user_id)->first();
        if (!empty($attend)) {
            $attend->update($attributes);
        } else {
            ManualAttendance::create($attributes);
        }

        return redirect()->route('admin.attendence.manualAttendance.index')
            ->with('success', __('Manual attendance created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attend_data = ManualAttendance::leftJoin('users AS authorised_person', function ($join) {
                $join->on('manual_attendances.authorised_person_id', '=', 'authorised_person.id');
            })->leftJoin('users AS demo_user', function ($join) {
                $join->on('manual_attendances.user_id', '=', 'demo_user.id');
            })->leftJoin('attendance_reasons', function ($join) {
                $join->on('manual_attendances.attendance_reason_id', '=', 'attendance_reasons.id');
            })->leftJoin('shifts', function ($join) {
                $join->on('manual_attendances.shift_id', '=', 'shifts.id');
            })->select([
                'manual_attendances.*', 'authorised_person.name as author_name',  'attendance_reasons.name',
                'demo_user.name as user_name', 'shifts.shift_name',
            ])->where('manual_attendances.id', $id)->first();
        return view('backend.attendance.attendance_approve', compact('id', 'attend_data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ManualAttendance $manualAttendance)
    {
        $loggedUser = Auth::user();
        $shifts = getShiftList();
        $user_list = getUserList();
        $attendanceReason = AttendanceReason::where([['status', '1'],['attendance_reasons.admin_id', $loggedUser->admin_id]])->get();
        // dd($attendanceReason);
        return view('backend.attendance.edit', compact('user_list', 'attendanceReason', 'manualAttendance', 'shifts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ManualAttendance $manualAttendance)
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
            $filename = UploadImage($request, $imgPath, $imgKey, $manualAttendance->request_hard_copy);
            if (!empty($filename)) {
                $attributes['request_hard_copy'] = $filename;
            }
        }

        $manualAttendance->update($attributes);
        return redirect()->route('admin.attendence.manualAttendance.index')->with('success', __('Attendance Updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ManualAttendance $manualAttendance)
    {
        $manualAttendance->delete();
        return redirect()->route('admin.attendence.manualAttendance.index')
            ->with('success', __('Attendance deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        ManualAttendance::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }

    public function approveStatus(Request $request)
    {
        $result = Attendance::attendanceApproval($request);
        if ($result['status'] == 1) {
            return redirect()->route('admin.attendence.manualAttendance.index')
                ->with('success', __($result['msg']));
        } else {
            return redirect()->route('admin.attendence.manualAttendance.index')
                ->with('success', __($result['msg']));
        }
    }
}
