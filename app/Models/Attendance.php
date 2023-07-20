<?php

namespace App\Models;

use App\Console\Commands\MyCommand;
use App\Models\ManualAttendance;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;

class Attendance extends Model
{
    use HasFactory, Sortable;
    protected $fillable = [
        'admin_id',
        'user_id',
        'shift_id',
        'leave_id',
        'authorised_person_id',
        'attendance_reason_id',
        'attendance_type',
        'from_time',
        'to_time',
        'previous_state',
        'working_hours',
        'is_manual_attendance',
        'attendance_status',
        'overtime',
        'early_in',
        'late_out',
        'late_in',
        'early_out',
        'request_remark',
        'request_hard_copy',
        'attendance_date',
        'description',
        'overday',
        'approve_remark',
        'approve_date',
        'approved_by',
        'tag',
        'status',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function punchIn($request)
    {
        $shifts = Shift::getUserShift($request->user_id);
        $request->date = Config::get('current_date');
        $request->shift_id = !empty($attributes['shift_id']) ? $attributes['shift_id'] : $shifts->id;
        if ($request->from_where == 'Web') {
            $check = checkIpAddress($request);
        } else {
            $check = Location::checkInGeo($request);
        }

        if ($check == true) {
            $userData = User::getUserDetails($request->user_id, 'emp');
            $current_time = Config::get('current_time');
            if ($userData->shift_rooster == 1) {
            } else {
                $result = self::attendanceGenralShiftIn($request);
            }
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'Your location is invalid',
            );
        }
        return $result;
    }

    public static function attendanceGenralShiftIn($request)
    {
        $attendance = Attendance::where([['admin_id', $request->admin_id], ['user_id', $request->user_id], ['shift_id', $request->shift_id], ['attendance_date', Config::get('current_date')], ['status', 1]])->first();
        // dd($attendance);
        if (empty($attendance)) {
            $array = array(
                'admin_id' => $request->admin_id,
                'user_id' => $request->user_id,
                'shift_id' => $request->shift_id,
                'attendance_type' => 'Auto',
                'from_time' => Config::get('current_datetime'),
                // 'from_time' => '23-03-24 13:00:00',
                'attendance_date' => Config::get('current_date'),
                'attendance_status' => 'MP',
                'status' => 1,
                'created_at' => Config::get('current_datetime'),
            );
            Attendance::create($array);
            $attendanceLog = AttendanceLog::attendanceLog($request);
            if ($attendanceLog) {
                $result = array(
                    "status" => 1,
                    'data' => self::getTodayAttendanceData($request),
                    "msg" => 'Attendance Punched Successfully',
                );
            } else {
                $result = array(
                    "status" => 0,
                    "msg" => 'Something went Wrong',
                );
            }
        } elseif (!empty($attendance) && $attendance->attendance_status == "HL") {
            $array = array(
                'admin_id' => $request->admin_id,
                'user_id' => $request->user_id,
                'shift_id' => $request->shift_id,
                'attendance_type' => 'Auto',
                'from_time' => Config::get('current_datetime'),
                // 'from_time' => '23-03-24 13:00:00',
                'attendance_date' => Config::get('current_date'),
                'attendance_status' => 'HL',
                'status' => 1,
                'created_at' => Config::get('current_datetime'),
            );
            $attendance->update($array);
        } else {
            $result = array(
                "status" => 1,
                "msg" => 'You are already Punched',
            );
        }
        return $result;
    }

    public static function punchOut($request)
    {
        $shifts = Shift::getUserShift($request->user_id);
        $request->date = Config::get('current_date');
        $request->shift_id = !empty($attributes['shift_id']) ? $attributes['shift_id'] : $shifts->id;
        if ($request->from_where == 'Web') {
            $check = checkIpAddress($request);
        } else {
            $check = Location::checkInGeo($request);
        }
        if ($check == true) {
            $userData = User::getUserDetails($request->user_id, 'emp');
            if ($userData->shift_rooster == 1) {
            } else {
                $result = self::attendanceGenralShiftOut($request);
            }
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'Your location is invalid',
            );
        }
        return $result;
    }

    public static function attendanceGenralShiftOut($request)
    {
        $attendance = Attendance::where([['admin_id', $request->admin_id], ['user_id', $request->user_id], ['shift_id', $request->shift_id], ['status', 1]]);
        // $current_time = '07:00:00';
        $current_time = Config::get('current_time');
        $attendanceData = $attendance->where('attendance_date', $request->date)->first();
        if (!empty($attendanceData) && !empty($attendanceData->from_time)) {
            $array = array(
                'to_time' => Config::get('current_datetime'),
                'updated_at' => Config::get('current_datetime'),
            );
            $attendanceData->update($array);
            $attendanceLog = AttendanceLog::attendanceLog($request);
            if ($attendanceLog) {
                self::attendanceCalculate($attendanceData);
                $result = array(
                    "status" => 1,
                    "data" => self::getTodayAttendanceData($request),
                    "msg" => 'Attendance Punched Successfully',
                );
            } else {
                $result = array(
                    "status" => 0,
                    "msg" => 'Something went Wrong',
                );
            }
        } else {
            $result = array(
                "status" => 0,
                "msg" => 'You are not punch In',
            );
        }
        return $result;
    }

    public static function attendanceCalculate($attendance)
    {

        if (!empty($attendance->from_time) && !empty($attendance->to_time)) {
            $from_date = date('Y-m-d', strtotime($attendance->from_time));
            $to_date = date('Y-m-d', strtotime($attendance->to_time));
            $shifts = Shift::getUserShift($attendance->user_id);
            $shift_from_time = strtotime($from_date . ' ' . $shifts->from_time);
            $shift_to_time = strtotime($to_date . ' ' . $shifts->to_time);
            $shiftTIme = $shift_to_time - $shift_from_time;
            $attendance_from_time = strtotime($attendance->from_time);
            $attendance_to_time = strtotime($attendance->to_time);
            $late_in = 0;
            $early_in = 0;
            $early_out = 0;
            $late_out = 0;
            $working_hours = 0;
            $overtime = 0;

            $working_hours = $attendance_to_time - $attendance_from_time;
            if ($working_hours > $shiftTIme) {
                $overtime = $working_hours - $shiftTIme;
            }
            // dd($shift_from_time);
            if ($attendance_from_time > $shift_from_time) {
                $late_in = $attendance_from_time - $shift_from_time;
            } elseif ($shift_from_time > $attendance_from_time) {
                $early_in = $shift_from_time - $attendance_from_time;
            }
            // dd($shift_to_time);
            if ($shift_to_time > $attendance_to_time) {
                $early_out = $shift_to_time - $attendance_to_time;
            } elseif ($attendance_to_time > $shift_to_time) {
                $late_out = $attendance_to_time - $shift_to_time;
            }

            $array = array(
                'late_in' => gmdate('H:i:s', $late_in),
                'early_in' => gmdate('H:i:s', $early_in),
                'early_out' => gmdate('H:i:s', $early_out),
                'late_out' => gmdate('H:i:s', $late_out),
                'working_hours' => gmdate('H:i:s', $working_hours),
                'overtime' => gmdate('H:i:s', $overtime),
                'status' => 1,
            );
            // dd($array);
            $attendance->update($array);
            checkAttendanceCondition($attendance);
        }
        return true;
    }

    public static function attendanceApproval($request)
    {
        $result = [];
        $attendanceData = ManualAttendance::where('id', $request->id)->first();
        if (!empty($attendanceData)) {
            if ($request->status == '1') {
                $attendance = Attendance::getTodayAttendanceData($attendanceData);
                Attendance::where('id', $attendance->id)->update(['previous_state' => $attendance->toArray()]);
                if (!empty($attendance)) {
                    $array = array(
                        'is_manual_attendance' => 1,
                        'updated_at' => Config::get('current_datetime'),
                        'approve_date' => Config::get('current_date'),
                    );
                    if (!empty($attendanceData->from_time)) {
                        $array['from_time'] = $attendanceData->from_time;
                    }
                    if (!empty($attendanceData->to_time)) {
                        $array['to_time'] = $attendanceData->to_time;
                    }
                    $attendance->update($array);
                    // $attendance = Attendance::getTodayAttendanceData($attendanceData);
                    Attendance::attendanceCalculate($attendance);
                    $array1 = array(
                        'status' => $request->status,
                        'approved_by' => $request->approved_by,
                        'approve_date' => Config::get('current_date'),
                    );
                    $attendanceData->update($array1);
                }
                $result = array(
                    'status' => 1,
                    'msg' => 'Attendance Approved successfully.',
                );
                MyCommand::fireBaseNotification($attendanceData->user_id, 'Manual Attendance Approved', 'Your manual attendance is approved for ' . prettyDateFormet($attendanceData->attendance_date, 'date'));
            } else if ($request->status == '2') {
                $previous_data = Attendance::getTodayAttendanceData($attendanceData);
                if (!empty($previous_data->previous_state)) {
                    $previous_state = json_decode($previous_data->previous_state);
                    $previous_data->update($previous_state);
                }
                $attendanceData->update([
                    'status' => $request->status,
                ]);
                $result = array(
                    'status' => 1,
                    'msg' => 'Attendance Cancel successfully.',
                );
                MyCommand::fireBaseNotification($attendanceData->user_id, 'Manual Attendance Cancel', 'Your manual attendance is cancel for ' . prettyDateFormet($attendanceData->attendance_date, 'date'));
            } else if ($request->status == '0') {
                $attendanceData->update([
                    'status' => $request->status,
                ]);

                $result = array(
                    'status' => 1,
                    'msg' => 'Attendance Pending successfully.',
                );
            }
        } else {
            $result['status'] = 0;
            $result['msg'] = "Invalid Attendance";
        }

        return $result;
    }

    public static function getTodayAttendanceData($request)
    {

        $attendance = '';
        $date = !empty($request->date) ? $request->date : $request->attendance_date;
        $attendance = Attendance::where([
            ['admin_id', $request->admin_id], ['user_id', $request->user_id],
            ['shift_id', $request->shift_id]
        ])->where('attendance_date', $date)->first();
        //  pr(getQuery($attendance));die;

        if (!empty($attendance)) {
            $attendance->from_time = !empty($attendance->from_time) ? $attendance->from_time : '';
            $attendance->to_time = !empty($attendance->to_time) ? $attendance->to_time : '';
        }

        return $attendance;
    }

    public static function dailyAttendanceCalculation()
    {
        $users = User::where('role_id', config('constants.employee_role_id'))->where('status', 1)->get();
        foreach ($users as $key => $value) {
            self::AttendanceSequence($value->id, $value->admin_id, Config::get('yesterday_date'));
        }
    }

    public static function AttendanceSequence($user_id, $admin_id, $date = '')
    {
        $attendance = Attendance::where('attendance_date', $date)->where([['admin_id', $admin_id], ['user_id', $user_id]])->whereIn('attendance_status', ['L', 'HL'])->first();
        if (empty($attendance)) {
            $weekday = date('l', strtotime($date));
            $userData = User::getUserDetails($user_id, 'emp');
            $holiday = Holiday::where('admin_id', $admin_id)->where('status', 1);
            $shift = Shift::getUserShift($userData->user_id);
            $saveData = [];
            $saveData = [
                'admin_id' => $userData->admin_id,
                'user_id' => $userData->user_id,
                'shift_id' => $shift->id,
                'attendance_type' => 'None',
            ];

            $holidays = $holiday->where('date', $date)->first();
            $saveData['attendance_date'] = $date;

            if (!empty($holidays) && $holidays->date == $date) {
                $saveData['attendance_status'] = 'HO';
                $saveData['status'] = 1;
            } elseif ($userData->weekly_holiday == $weekday) {
                $saveData['attendance_status'] = 'WO';
                $saveData['status'] = 1;
            } else {
                $saveData['attendance_status'] = 'A';
                $saveData['status'] = 1;
            }
            // dd($saveData);
            Attendance::create($saveData);
        }
    }

    public static function manualAttendanceList($request)
    {
        $offset = !empty($attributes['offset']) ? $attributes['offset'] : '0';
        $page_limit = config('constants.api_page_limit');
        $attendance = Attendance::leftJoin('users as user', function ($join) {
            $join->on('attendances.user_id', '=', 'user.id');
        });
        $attendance->leftJoin('users as auth_person', function ($join) {
            $join->on('attendances.authorised_person_id', '=', 'auth_person.id');
        });
        $attendance->leftJoin('attendance_reasons', function ($join) {
            $join->on('attendances.attendance_reason_id', '=', 'attendance_reasons.id');
        });
        $attendance->select([
            'attendances.*', 'user.username as user_username',
            'auth_person.username as authorised_person_username',
            'attendance_reasons.name as attendance_reasons_name'
        ]);
        $attendance->where('attendances.admin_id', $request->admin_id);
        $attendance->where('attendances.user_id', $request->user_id);
        $attendance->where('attendances.is_manual_attendance', 1);
        if (!empty($attributes['offset'])) {
            $offset = $attributes['offset'];
            $attendance->offset($offset)->limit($page_limit);
        }
        $attendances = $attendance->get();
        return $attendances;
    }

    public static function getUserAttendanceList($request)
    {
        $fullDay = self::AttendanceQuery($request, ['P']);

        $HaifDay = self::AttendanceQuery($request, ['HD', 'HD-HL']);

        $leaveDay = self::AttendanceQuery($request, ['L']);

        $haifLeave = self::AttendanceQuery($request, ['HD-HL']);

        $absentDay = self::AttendanceQuery($request, ['A', 'HL', 'MP']);

        $weekOff = self::AttendanceQuery($request, ['WO']);

        $hoildays = self::AttendanceQuery($request, ['HO']);

        $workingHours = self::AttendanceQuery($request, [], 'work');

        $overtime = self::AttendanceQuery($request, [], 'over');

        $overDay = self::AttendanceQuery($request, [], 'overday');

        // $otherhaifLeave = Attendance::where('attendances.admin_id', $request->admin_id);
        // $otherhaifLeave->where('attendances.user_id', $request->user_id);
        // $otherhaifLeave->where('attendances.status', 1);
        // if (!empty($request->month)) {
        //     $year = date('Y', strtotime($request->month));
        //     $month = date('m', strtotime($request->month));
        //     $otherhaifLeave->whereYear('attendance_date', $year);
        //     $otherhaifLeave->whereMonth('attendance_date', $month);
        // }
        // $otherhaifLeaves    =   $otherhaifLeave->where('attendance_status','HD')->whereNotNull('leave_id')->count();
        // $totalhaifLeaveDay = $haifLeave + $otherhaifLeaves;



        $array = (object) array(
            'present' => $fullDay,
            'leaveday' => $leaveDay,
            'haifleave' => $haifLeave,
            'halfday' => $HaifDay,
            'absent' => $absentDay,
            'weekOff' => $weekOff,
            'hoildays' => $hoildays,
            'overday' => $overDay,
            'workinghours' => $workingHours->total_working_hours,
            'overtime' => $overtime->total_overtime,
        );

        return $array;
    }

    public static function AttendanceQuery($request, $array = [], $type = '')
    {
        // dd($request);
        $attendance = Attendance::where('attendances.admin_id', $request->admin_id);
        $attendance->where('attendances.user_id', $request->user_id);
        $attendance->where('attendances.status', 1);
        if (!empty($request->month)) {
            $year = date('Y', strtotime($request->month));
            $month = date('m', strtotime($request->month));
            $attendance->whereYear('attendance_date', $year);
            $attendance->whereMonth('attendance_date', $month);
        }

        $userData   = User::getUserDetails($request->user_id, 'emp');
        $userPolicy =   UserPolicy::getEmployeePolicy($userData->policy_id);
        if (!empty($type) && $type == 'over') {
            $time = date('H:i', mktime(0, $userPolicy->overtime_apply_time));
            $attendances = $attendance->where('overtime', '>=', $time . ':00')->select(DB::raw("SEC_TO_TIME( SUM( TIME_TO_SEC( `overtime` ) ) ) AS total_overtime"))->first();
            // pr(getQuery($attendances));die;
        } elseif (!empty($type) && $type == 'work') {
            $attendances = $attendance->select(DB::raw("SEC_TO_TIME( SUM( TIME_TO_SEC( `working_hours` ) ) ) AS total_working_hours"))->first();
        } elseif (!empty($type) && $type == 'overday') {
            $attendances = $attendance->where('overday', 1)->count();
        } else {
            $attendances = $attendance->whereIn('attendance_status', $array)->count();
        }
        return $attendances;
    }

    public static function fillMissingAttendance($attributes)
    {
        $year = date('Y', strtotime($attributes['month']));
        $month = date('m', strtotime($attributes['month']));

        $AdminData = User::getUserDetails($attributes['admin_id']);
        $admin_date = date('Y-m-d', strtotime($AdminData->created_at));
        $UserData = User::getUserDetails($attributes['user_id'], 'emp');
        $user_date = $UserData->joined_date;
        $month_date = "$year-$month-01";

        $date = max([$admin_date, $user_date, $month_date]);

        $startDate = date('Y-m-01', strtotime($date));
        $endDate = date('Y-m-t', strtotime($startDate));
        if ($endDate >= Config::get('current_date')) {
            $endDate = Config::get('yesterday_date');
        }
        $missingDate = self::getMissingAttendanceQuery($attributes, $startDate, $endDate);
        foreach ($missingDate as $key => $val) {
            Attendance::AttendanceSequence($attributes['user_id'], $attributes['admin_id'], $val->date);
        }
        return true;
    }

    public static function getMissingAttendanceQuery($attributes, $startDate, $endDate)
    {
        $query = "SELECT d.date,a.id,a.attendance_date
         FROM (
          SELECT DATE_SUB(DATE_ADD('$startDate', INTERVAL id DAY), INTERVAL 1 DAY) AS date
          FROM (
            SELECT t * 10 + u AS id
            FROM (
              SELECT 0 AS t
              UNION SELECT 1
              UNION SELECT 2
              UNION SELECT 3
              UNION SELECT 4
              UNION SELECT 5
              UNION SELECT 6
              UNION SELECT 7
              UNION SELECT 8
              UNION SELECT 9
            ) AS t
            CROSS JOIN (
              SELECT 0 AS u
              UNION SELECT 1
              UNION SELECT 2
              UNION SELECT 3
              UNION SELECT 4
              UNION SELECT 5
              UNION SELECT 6
              UNION SELECT 7
              UNION SELECT 8
              UNION SELECT 9
            ) AS u
          ) AS days
          WHERE DATE_SUB(DATE_ADD('$startDate', INTERVAL id DAY), INTERVAL 1 DAY) <= '$endDate'
        ) AS d
        LEFT JOIN attendances a ON d.date = a.attendance_date AND  `a`.`admin_id` = '" . $attributes['admin_id'] . "' AND  `a`.`user_id` = '" . $attributes['user_id'] . "'
        WHERE a.id IS NULL and `d`.`date` between '$startDate' and '$endDate'
        order BY d.date";
        // pr($query);die;
        $missingDate = DB::select($query);
        return $missingDate;
    }
}
