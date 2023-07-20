<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveApplication;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use App\Models\AttendanceReason;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request)
    // {


    //     $loggedUser = Auth::guard('front-user')->user();
    //     // dd($loggedUser);
    //     $limit =  config('constants.default_page_limit');
    //     $filter = $request->query();
    //     // dd($filter);

    //     $thismodel = Attendance::sortable(['created_at' => 'DESC'])
    //         ->leftJoin('users', function ($join) {
    //             $join->on('attendances.user_id', '=', 'users.id');
    //         })
    //         ->leftJoin('users AS authorised_person', function ($join) {
    //             $join->on('attendances.authorised_person_id', '=', 'authorised_person.id');
    //         })->select([
    //             'attendances.*', 'users.name as user_name',
    //             'authorised_person.name as author_name',
    //         ]);
    //     $thismodel->where('users.id', $loggedUser->id)->where('users.admin_id', $loggedUser->admin_id);



    //     if (isset($filter['status']) && $filter['status'] != "") {
    //         $thismodel->where('attendances.status', $filter['status']);
    //     }

    //     if (!empty($filter['from_time'])) {
    //         $start_date_format = mysqlDateFormat($filter['from_time']);
    //         $thismodel->whereDate('attendances.created_at', '>=', $start_date_format);
    //     }
    //     if (!empty($filter['to_time'])) {
    //         $end_date_format = mysqlDateFormat($filter['to_time']);
    //         $thismodel->whereDate('attendances.created_at', '<=', $end_date_format);
    //     }

    //     $attendance = $thismodel->paginate($limit);
    //     // dd($leaves);
    //     return view('home.employee.dashboard.attendance.index', compact('attendance', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    // }

    public function attendanceList(Request $request)
    {

        $loggedUser = Auth::guard('front-user')->user();
        // dd($loggedUser);
        $limit = config('constants.default_page_limit');
        $filter = $request->query();
        $attendances = Attendance::orderBy('attendance_date', 'DESC');
        $attendances->where('admin_id', $loggedUser->admin_id);
        $attendances->where('user_id', $loggedUser->id);
        // pr(getQuery($attendance));die;
        $attendance = $attendances->paginate($limit);
        foreach ($attendance as $key => $value) {
            $value->to_time = !empty($value->to_time) ? $value->to_time : '----/--/-- --:--:--';
        }
        // dd($attendance);
        return view('home.employee.dashboard.attendance_list', compact('attendance', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Attendance $attendance)
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $ans = Attendance::find($id);
        $ans->delete();
        return redirect()->route('employee-regularise.index')
            ->with('success', __('Mannual attendance deleted successfully.'));
    }
}
