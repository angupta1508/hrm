<?php

namespace App\Http\Controllers\Backend;

use PDF;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;
use App\Models\Employee;
use Carbon\CarbonPeriod;
use App\Models\LeaveType;
use App\Models\Attendance;
use App\Models\UserPolicy;
use App\Exports\ExportUser;
use Illuminate\Http\Request;
use App\Models\LeaveTypeInOut;
use App\Models\LeaveApplication;
use Illuminate\Support\Facades\DB;
use App\Console\Commands\MyCommand;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;




class LeaveApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $loggedUser = Auth::user();
        $managers = getMangerList();
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        $limit =  config('constants.default_page_limit');
        $filter = $request->query();

        $thismodel = LeaveApplication::sortable(['created_at' => 'DESC'])
            ->leftJoin('users', function ($join) {
                $join->on('leave_applications.user_id', '=', 'users.id');
            })->leftJoin('leave_types', function ($join) {
                $join->on('leave_applications.leave_type_id', '=', 'leave_types.id');
            })->leftJoin('employees', function ($join) {
                $join->on('leave_applications.user_id', '=', 'employees.user_id');
            })->leftJoin('users as authors', function ($join) {
                $join->on('leave_applications.authorised_person_id', '=', 'authors.id');
            })
            ->select([
                'leave_applications.*', 'users.name', 'authors.name as authorise_person', 'leave_types.leave_type',
            ])->groupBy('leave_applications.id');
        $thismodel->where('leave_applications.admin_id', $loggedUser->admin_id);

        // dd(getQuery($thismodel));

        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('users.name', 'LIKE', '%' . $keyword . '%')
                    ->orwhere('leave_types.leave_type', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (!empty($filter['user_id'])) {
            $keyword = $filter['user_id'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('users.id', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (!empty($filter['start_request_date'])) {
            $start_date_format = mysqlDateFormat($filter['start_request_date']);
            $thismodel->whereDate('leave_applications.request_start_date', '>=', $start_date_format);
        }
        if (!empty($filter['end_request_date'])) {
            $end_date_format = mysqlDateFormat($filter['end_request_date']);
            $thismodel->whereDate('leave_applications.request_end_date', '<=', $end_date_format);
        }
        
        // if (!empty($filter['start_date'])) {
        //     $start_date_format = mysqlDateFormat($filter['start_date']);
        //     $thismodel->whereDate('leave_applications.created_at', '>=', $start_date_format);
        // }

        // if (!empty($filter['end_date'])) {
        //     $end_date_format = mysqlDateFormat($filter['end_date']);
        //     $thismodel->whereDate('leave_applications.created_at', '<=', $end_date_format);
        // }

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('leave_applications.status', $filter['status']);
        }
        if (isset($filter['authorised_person_id']) && $filter['authorised_person_id'] != "") {
            $thismodel->where('employees.authorised_person_id', $filter['authorised_person_id']);
        }

        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Name", "Leave Type", "Apply Date",
                "Date From", "To Date", "Remark",
                "Approve By",
                "leave Status",
            ];
            $thismodel->select([
                'users.name', 'leave_type', 'leave_applications.request_date',
                'leave_applications.request_start_date', 'leave_applications.request_end_date',
                'leave_applications.request_remark',
                'leave_applications.approved_by',
                'leave_applications.status',
            ]);
            $records = $thismodel->get();

            // dd($records);
            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Leave Application List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings, $header), 'Leave-Application.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Leave-Application List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,
                ];

                $file = 'Leave-Aplication.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        $leaves = $thismodel->paginate($limit);
        // dd($leaves);
        return view('backend.leave_application.index', compact('leaves', 'filter', 'managers', 'user_list'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // dd($request);
        $loggedUser = Auth::user();
        $user_list = getUserList($data = []);
        $leaveType = LeaveType::where('status', '1')->get();
        $LeaveOut = LeaveTypeInOut::where('status', '1')->get();
        $Leave_list = LeaveTypeInOut::where('status', '1')->get();


        return view('backend.leave_application.create', compact('user_list', 'leaveType', 'LeaveOut', 'Leave_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $loggedUser = Auth::user();
        $attributes = request()->validate([
            'user_id' => ['required'],
            'leave_type_id' => ['required'],
            'request_start_date' => ['required'],
            'request_end_date' => ['required'],
            'request_remark' => ['required'],
            'request_hard_copy' => ['nullable'],
            'request_leave_type_out_id' => ['required'],
            'request_leave_type_in_id' => ['required'],
            'request_day' => ['required'],
        ]);

        $datedata = date('Y-m-d');
        $attributes['request_date'] = $datedata;
        $userData = User::getUserDetails($attributes['user_id'], 'emp');
        $attributes['authorised_person_id'] = !empty($userData->authorised_person_id) ? $userData->authorised_person_id : '';
        $attributes['admin_id'] = Config::get('auth_detail')['admin_id'];
        $attributes['shift_id'] = !empty($userData->shift_id) ? $userData->shift_id : '';
        //   dd($attributes);

        $ary = (object)array(
            'user_id'           => $request->user_id,
            'date'              => $request->request_start_date,
            'admin_id'          => Config::get('auth_detail')['id'],
            'leave_type_id'     => $request->leave_type_id,
        );

        $balance =  LeaveApplication::checkLeaveBalance($ary);
        // dd($balance);
        if ($request->request_day > $balance->available_leave_blance) {
            return back()->with('error', __('You do not enough leave balance.Your have avialable leaves is ' . $balance->available_leave_blance . ''));
        }

        if (!empty($attributes['request_hard_copy'])) {
            $imgKey     =   'request_hard_copy';
            $imgPath    =   public_path(config('constants.leave_request_hard_copy_image_path'));
            $filename = UploadImage($request, $imgPath, $imgKey);
            if (!empty($filename)) {
                $attributes['request_hard_copy'] = $filename;
            }
        }

        $leave  =   LeaveApplication::create($attributes);
        MyCommand::fireBaseNotification($userData->authorised_person_id, 'Leave Request', $userData->name . ' is sending a leave request for ' . $leave->request_day . ' day');
        return redirect()->route('admin.leave.leaves.index')
            ->with('success', __('Leave application created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $leave_data = LeaveApplication::sortable(['created_at' => 'DESC'])
            ->leftJoin('users AS authorised_person', function ($join) {
                $join->on('leave_applications.authorised_person_id', '=', 'authorised_person.id');
            })->leftJoin('leave_types', function ($join) {
                $join->on('leave_applications.leave_type_id', '=', 'leave_types.id');
            })->leftJoin('employees', function ($join) {
                $join->on('leave_applications.user_id', '=', 'employees.user_id');
            })->leftJoin('leave_type_in_out as request_leave', function ($join) {
                $join->on('leave_applications.request_leave_type_out_id', '=', 'request_leave.id');
            })->leftJoin('leave_type_in_out as request_leave_in', function ($join) {
                $join->on('leave_applications.request_leave_type_in_id', '=', 'request_leave_in.id');
            })->leftJoin('leave_type_in_out as approve_leave_out', function ($join) {
                $join->on('leave_applications.approve_leave_type_out_id', '=', 'approve_leave_out.id');
            })->leftJoin('leave_type_in_out as approve_leave_in', function ($join) {
                $join->on('leave_applications.approve_leave_type_in_id', '=', 'approve_leave_in.id');
            })->leftJoin('users as user_person', function ($join) {
                $join->on('leave_applications.user_id', '=', 'user_person.id');
            })
            ->select([
                'leave_applications.*', 'authorised_person.name as authorise_name',
                'authorised_person.email', 'authorised_person.mobile',
                'employees.employee_code', 'employees.company_id', 'employees.machine_code', 'leave_types.leave_type',
                'request_leave.name as request_out_name', 'request_leave_in.name as request_in_name',
                'approve_leave_out.name as approve_out_name', 'approve_leave_in.name as approve_in_name',
                'user_person.name as user_name',
            ])->where('leave_applications.id', $id)->first();
        $LeaveOut = LeaveTypeInOut::where('status', '1')->get();

        return view('backend.leave_application.approve_leave_application', compact('id', 'leave_data', 'LeaveOut'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $leave_application = LeaveApplication::leftJoin('leave_type_in_out', function ($join) {
            $join->on('leave_applications.request_leave_type_out_id', '=', 'leave_type_in_out.id');
        })->leftJoin('leave_type_in_out as leave_tables', function ($join) {
            $join->on('leave_applications.request_leave_type_in_id', '=', 'leave_tables.id');
        })
            ->select([
                'leave_applications.*', 'leave_type_in_out.name as leave_out_name', 'leave_tables.name as leave_in_name',
            ])->find($id);
        $user_list = getUserList($data = []);
        $leave_type = LeaveType::where('status', '1')->get();
        $leave_in_out = LeaveTypeInOut::where('status', '1')->get();

        return view('backend.leave_application.edit', compact('user_list', 'leave_type', 'leave_application', 'leave_in_out',));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attributes = request()->validate([
            'user_id' => ['required'],
            'leave_type_id' => ['required'],
            'request_start_date' => ['required'],
            'request_end_date' => ['required'],
            'request_remark' => ['required'],
            'request_hard_copy' => ['nullable'],
            'request_leave_type_out_id' => ['required'],
            'request_leave_type_in_id' => ['required'],
        ]);

        $leaveUpdate = LeaveApplication::find($id);

        $datedata = date('Y-m-d');
        $attributes['request_date'] = $datedata;
        $author =   Employee::where('user_id', $attributes['user_id'])->first();
        $attributes['authorised_person_id'] = !empty($author->authorised_person_id) ? $author->authorised_person_id : '';

        if (!empty($attributes['request_hard_copy'])) {
            $imgKey     =   'request_hard_copy';
            $imgPath    =   public_path(config('constants.leave_request_hard_copy_image_path'));
            $filename = UploadImage($request, $imgPath, $imgKey, $leaveUpdate->request_hard_copy);
            if (!empty($filename)) {
                $attributes['request_hard_copy'] = $filename;
            }
        }

        // dd($attributes);
        $leaveUpdate->update($attributes);
        return redirect()->route('admin.leave.leaves.index')->with('success', __('Leave Application Updated successfully.'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // dd($request);
        $ans = LeaveApplication::find($id);
        $ans->delete();
        return redirect()->route('admin.leave.leaves.index')
            ->with('success', __('Leave Application deleted successfully.'));
    }



    public function changeStatus(Request $request)
    {
        LeaveApplication::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status change successfully.')]);
    }

    public function approveStatus(Request $request)
    {
        $leaveData =  LeaveApplication::where('id', $request->id)->first();
        $leave  =    LeaveApplication::leaveApproval($leaveData, $request);
        if ($leave == '1') {
            return redirect()->route('admin.leave.leaves.index')
                ->with('success', __('Leave Approve successfully.'));
        } elseif ($leave == '2') {
            return redirect()->route('admin.leave.leaves.index')
                ->with('success', __('Leave Cancel successfully.'));
        } elseif ($leave == '0') {
            return redirect()->route('admin.leave.leaves.index')
                ->with('success', __('Leave Pending successfully.'));
        }
    }
}
