<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveApplication;
use App\Models\User;
use App\Models\LeaveType;
use App\Models\LeaveTypeInOut;
use App\Models\Employee;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\UserPolicy;


class LeavesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $loggedUser = Auth::guard('front-user')->user();
        // dd($loggedUser);
        $leaveTyp = LeaveType::where('status', '1')->get();
        $limit =  config('constants.default_page_limit');
        $filter = $request->query();

        $thismodel = LeaveApplication::sortable(['created_at' => 'DESC'])
            ->leftJoin('users', function ($join) {
                $join->on('leave_applications.user_id', '=', 'users.id');
            })->leftJoin('leave_types', function ($join) {
                $join->on('leave_applications.leave_type_id', '=', 'leave_types.id');
            })->leftJoin('users AS authorised_person', function ($join) {
                $join->on('leave_applications.authorised_person_id', '=', 'authorised_person.id');
            })->select([
                'leave_applications.*', 'users.name as user_name', 'leave_types.leave_type',
                'authorised_person.name as author_name',
            ]);
        $thismodel->where('users.id', $loggedUser->id)->where('users.admin_id', $loggedUser->admin_id);

        // dd(getQuery())
        // dd($thismodel);

        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('users.name', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (!empty($request->leave_type_id)) {
            $thismodel->where('leave_applications.leave_type_id', $request->leave_type_id);
        }

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('leave_applications.status', $filter['status']);
        }

        if (!empty($filter['start_date'])) {
            $start_date_format = mysqlDateFormat($filter['start_date']);
            $thismodel->whereDate('leave_applications.created_at', '>=', $start_date_format);
        }
        if (!empty($filter['end_date'])) {
            $end_date_format = mysqlDateFormat($filter['end_date']);
            $thismodel->whereDate('leave_applications.created_at', '<=', $end_date_format);
        }

        $leaves = $thismodel->paginate($limit);
        // dd($leaves);

        return view('home.employee.leave_application.index', compact('leaves', 'filter', 'leaveTyp'))
            ->with('i', (request()->input('page', 1) - 1) * $limit);
    }



    public function approveLeaveList(Request $request)
    {
        
        $loggedUser = Auth::guard('front-user')->user();
        $filter = $request->query();

        $thismode = LeaveApplication::leftJoin('users', function ($join) {
            $join->on('leave_applications.user_id', '=', 'users.id');
        })->select([
            'leave_applications.*', 'users.name', 'users.profile_image'
        ])->where('leave_applications.authorised_person_id', $loggedUser->id)
            ->where('leave_applications.admin_id', $loggedUser->admin_id);
 
            if (!empty($filter['search'])) {
                $keyword = $filter['search'];
                $thismode->where(function ($query) use ($keyword) {
                    $query->where('users.name', 'LIKE', '%' . $keyword . '%');
                });
            }
            if (isset($filter['status']) && $filter['status'] != "") {
                $thismode->where('leave_applications.status', $filter['status']);
            }

            $leaveApprovel = $thismode->orderby('id', 'desc')->get();
            return view('home.employee.dashboard.approvel.approve_leave_list', compact('leaveApprovel','filter'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $loggedUser = Auth::guard('front-user')->user();
        $thismodel =   Employee::where('user_id',  $loggedUser['id'])->first();
        $author = User::where('id', $thismodel->authorised_person_id)->select([
            'users.name', 'users.profile_image',
        ])->first();
 

        $leaveType = LeaveType::where('status', '1')->get();
        $LeaveOut = LeaveTypeInOut::where('status', '1')->get();
        $Leave_list = LeaveTypeInOut::where('status', '1')->get();
        //  dd($Leaveout);
        return view('home.employee.leave_application.create', compact('leaveType', 'LeaveOut', 'Leave_list', 'author'));
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
        $attributes = request()->validate([
            'leave_type_id' => ['required'],
            'request_start_date' => ['required'],
            'request_end_date' => ['required'],
            'request_remark' => ['required'],
            'request_hard_copy' => ['nullable'],
            'leave_type_id' => ['required'],
            'request_leave_type_out_id' => ['required'],
            'request_leave_type_in_id' => ['required'],
            'request_day' => ['required'],
        ]);
        $datedata = date('Y-m-d');
        $loggedUser = Auth::guard('front-user')->user();
        // dd($loggedUser);
        $attributes['user_id'] = $loggedUser->id;
        $attributes['request_date'] = $datedata;
        $author =   Employee::where('user_id', $attributes['user_id'])->first();
        $attributes['authorised_person_id'] = !empty($author->authorised_person_id) ? $author->authorised_person_id : '';
        $attributes['admin_id'] = Config::get('auth_detail')['admin_id'];
        $attributes['shift_id'] = !empty($author->shift_id) ? $author->shift_id : '';


        $ary = (object)array(
            'user_id'           => $loggedUser->id,
            'date'              => $request->request_start_date,
            'admin_id'          => Config::get('auth_detail')['id'],
            'leave_type_id'     => $request->leave_type_id,
        );

        // dd($ary);
        $balance =  LeaveApplication::checkLeaveBalance($ary);
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

        // dd($attributes);

        LeaveApplication::create($attributes);
        return redirect()->route('employe-leave.index')
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
                $join->on('leave_applications.user_id', '=', 'employees.id');
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
        // dd($leave_data);
        return view('home.employee.leave_application.leave_approve', compact('id', 'leave_data', 'LeaveOut'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $loggedUser = Auth::guard('front-user')->user();
        $thismodel =   Employee::where('user_id',  $loggedUser['id'])->first();
        $author = User::where('id', $thismodel->authorised_person_id)->select([
            'users.name', 'users.profile_image',
        ])->first();
 

        $leave_application = LeaveApplication::leftJoin('leave_type_in_out', function ($join) {
            $join->on('leave_applications.request_leave_type_out_id', '=', 'leave_type_in_out.id');
        })->leftJoin('leave_type_in_out as leave_tables', function ($join) {
            $join->on('leave_applications.request_leave_type_in_id', '=', 'leave_tables.id');
        })
            ->select([
                'leave_applications.*', 'leave_type_in_out.name as leave_out_name', 'leave_tables.name as leave_in_name',
            ])->find($id);

        $leave_type = LeaveType::where('status', '1')->get();
        $leave_in_out = LeaveTypeInOut::where('status', '1')->get();

        return view('home.employee.leave_application.edit', compact('leave_type', 'leave_application', 'leave_in_out', 'author',));
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
            'leave_type_id' => ['required'],
            'request_start_date' => ['required'],
            'request_end_date' => ['required'],
            'request_remark' => ['required'],
            'request_hard_copy' => ['nullable'],
            'request_leave_type_out_id' => ['required'],
            'request_leave_type_in_id' => ['required'],
            'request_day' => ['required'],
        ]);

        $leaveUpdate = LeaveApplication::find($id);

        if (!empty($attributes['request_hard_copy'])) {
            $imgKey     =   'request_hard_copy';
            $imgPath    =   public_path(config('constants.leave_request_hard_copy_image_path'));
            $filename = UploadImage($request, $imgPath, $imgKey);
            if (!empty($filename)) {
                $attributes['request_hard_copy'] = $filename;
            }
        }
        // dd($attributes);
        $leaveUpdate->update($attributes);
        return redirect()->route('employe-leave.index')
            ->with('success', __('Leave application update successfully.'));
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
        return redirect()->route('employe-leave.index')
            ->with('success', __('Leave Application deleted successfully.'));
    }

    public function approveLeave(Request $request)
    {

        $leaveData =  LeaveApplication::where('id', $request->id)->first();
        $leave  =    LeaveApplication::leaveApproval($leaveData, $request);
        if ($leave == '1') {
            return redirect()->route('approvel')
                ->with('success', __('Leave Approve successfully.'));
        } elseif ($leave == '2') {
            return redirect()->route('approvel')
                ->with('success', __('Leave Cancel successfully.'));
        } elseif ($leave == '0') {
            return redirect()->route('approvel')
                ->with('success', __('Leave Pending successfully.'));
        }
    }
}
