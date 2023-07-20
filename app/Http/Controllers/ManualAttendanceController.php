<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\AttendanceReason;
use App\Models\LeaveApplication;
use App\Models\ManualAttendance;
use App\Console\Commands\MyCommand;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class ManualAttendanceController extends Controller
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

        $limit =  config('constants.default_page_limit');
        $filter = $request->query();
        // dd($filter);

        $thismodel = ManualAttendance::sortable(['created_at' => 'DESC'])
            ->leftJoin('users', function ($join) {
                $join->on('manual_attendances.user_id', '=', 'users.id');
            })
            ->leftJoin('users AS authorised_person', function ($join) {
                $join->on('manual_attendances.authorised_person_id', '=', 'authorised_person.id');
            })->select([
                'manual_attendances.*', 'users.name as user_name',
                'authorised_person.name as author_name',
            ]);


        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('manual_attendances.status', $filter['status']);
        }

        if (!empty($filter['from_time'])) {
            $start_date_format = mysqlDateFormat($filter['from_time']);
            $thismodel->whereDate('manual_attendances.attendance_date', '>=', $start_date_format);
        }
        if (!empty($filter['to_time'])) {
            $end_date_format = mysqlDateFormat($filter['to_time']);
            $thismodel->whereDate('manual_attendances.attendance_date', '<=', $end_date_format);
        }
        $thismodel->where('manual_attendances.user_id', $loggedUser->id)
            ->where('manual_attendances.admin_id', $loggedUser->admin_id);

        $attendance = $thismodel->paginate($limit);

        return view('home.employee.dashboard.attendance.index', compact('attendance', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }



    public function approveAttendanceList(Request $request)
    {

        $loggedUser = Auth::guard('front-user')->user();
        $filter = $request->query();
        $thismodel = ManualAttendance::leftJoin('users', function ($join) {
            $join->on('manual_attendances.user_id', '=', 'users.id');
        })->select([
            'manual_attendances.*', 'users.name', 'users.profile_image'
        ])->where('manual_attendances.authorised_person_id', $loggedUser->id)
            ->where('manual_attendances.admin_id', $loggedUser->admin_id);

        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('users.name', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('manual_attendances.status', $filter['status']);
        }

        $attendance = $thismodel->orderby('id', 'desc')->get();
        
        return view('home.employee.dashboard.approvel.approve_attendance_list', compact('attendance','filter'));
    }



    // public function approvelAttendance(Request $request)
    // {

    //     $loggedUser = Auth::guard('front-user')->user();

    //     $attend_data = ManualAttendance::leftJoin('users AS authorised_person', function ($join) {
    //         $join->on('manual_attendances.authorised_person_id', '=', 'authorised_person.id');
    //     })->leftJoin('users AS demo_user', function ($join) {
    //         $join->on('manual_attendances.user_id', '=', 'demo_user.id');
    //     })->leftJoin('attendance_reasons', function ($join) {
    //         $join->on('manual_attendances.attendance_reason_id', '=', 'attendance_reasons.id');
    //     })->leftJoin('shifts', function ($join) {
    //         $join->on('manual_attendances.shift_id', '=', 'shifts.id');
    //     })->select([
    //         'manual_attendances.*', 'authorised_person.name as author_name','attendance_reasons.name',
    //         'demo_user.name as user_name', 'shifts.shift_name',
    //     ])->where('users.id', $loggedUser->id)->where('users.admin_id', $loggedUser->admin_id)->get();

    //     return view('home.employee.dashboard.attendance_approve', compact('attend_data'));
    // }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $author = ManualAttendance::leftJoin('users', function ($join) {
            $join->on('manual_attendances.authorised_person_id', '=', 'users.id');
        })->select([
            'manual_attendances.*', 'users.name', 'users.profile_image',
        ])->first();

        $reason = AttendanceReason::where('status', '1')->get();

        return view('home.employee.dashboard.attendance.create', compact('author', 'reason'));
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
            // 'user_id' => ['required'],
            'attendance_reason_id' => ['required'],
            'from_time' => ['required'],
            'to_time' => ['required'],
            'request_remark' => ['required'],
            'attendance_date' => ['required'],
            'request_hard_copy' => ['nullable'],
        ]);

        $loggedUser = Auth::guard('front-user')->user();
        $attributes['user_id'] = $loggedUser->id;
        $userData = User::getUserDetails($attributes['user_id'],'emp');
        $attributes['authorised_person_id'] = !empty($userData->authorised_person_id) ? $userData->authorised_person_id : '';
        $attributes['shift_id'] = !empty($userData->shift_id) ? $userData->shift_id : '';
        $attributes['admin_id'] = Config::get('auth_detail')['admin_id'];

        if (!empty($attributes['request_hard_copy'])) {
            $imgKey     =   'request_hard_copy';
            $imgPath    =   public_path(config('constants.attendance_image_path'));
            $filename = UploadImage($request, $imgPath, $imgKey);
            if (!empty($filename)) {
                $attributes['request_hard_copy'] = $filename;
            }
        }   
        
        $attributes['is_manual_attendance'] = 1;
        ManualAttendance::create($attributes);
        MyCommand::fireBaseNotification($userData->authorised_person_id,'Manual Attendance Request',$userData->name.' is sending a manal attendance request for '.prettyDateFormet($attributes['attendance_date'],'date'));
        return redirect()->route('attendance-regularise.index')
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
        $loggedUser = Auth::guard('front-user')->user();
        $attend_data = ManualAttendance::sortable(['created_at' => 'DESC'])
            ->leftJoin('users AS authorised_person', function ($join) {
                $join->on('manual_attendances.authorised_person_id', '=', 'authorised_person.id');
            })->leftJoin('users AS demo_user', function ($join) {
                $join->on('manual_attendances.user_id', '=', 'demo_user.id');
            })->leftJoin('attendance_reasons', function ($join) {
                $join->on('manual_attendances.attendance_reason_id', '=', 'attendance_reasons.id');
            })->leftJoin('shifts', function ($join) {
                $join->on('manual_attendances.shift_id', '=', 'shifts.id');
            })->select([
                'manual_attendances.*', 'authorised_person.name as author_name', 'attendance_reasons.name',
                'demo_user.name as user_name', 'shifts.shift_name',
            ])
            ->where('manual_attendances.id', $id)->first();

        return view('home.employee.dashboard.attendance.attendance_approve', compact('id', 'attend_data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
        $ans = ManualAttendance::find($id);
        $ans->delete();
        return redirect()->route('attendance-regularise.index')
            ->with('success', __('Mannual attendance deleted successfully.'));
    }

    public function approveStatus(Request $request)
    {
        $result = Attendance::attendanceApproval($request);
        if ($result['status'] == 1) {
            return redirect()->route('approvel')
                ->with('success', __($result['msg']));
        } else {
            return redirect()->route('approvel')
                ->with('success', __($result['msg']));
        }
    }
}
