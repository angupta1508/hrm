<?php

namespace App\Models;

use App\Models\User;
use Carbon\CarbonPeriod;
use App\Models\Attendance;
use App\Models\UserPolicy;
use App\Models\LeaveTypeInOut;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Console\Commands\MyCommand;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveApplication extends Model
{
  use HasFactory, Sortable;
  protected $fillable = [
    'admin_id',
    'user_id',
    'authorised_person_id',
    'leave_type_id',
    'request_start_date',
    'request_end_date',
    'request_date',
    'request_remark',
    'request_hard_copy',
    'approved_by',
    'request_day',
    'request_leave_type_out_id',
    'request_leave_type_in_id',
    'shift_id',
    'approve_start_date',
    'approve_end_date',
    'approve_leave_type_out_id',
    'approve_leave_type_in_id',
    'approve_day',
    'approve_remark',
    'approve_date',
    'approved_by',
    'remove_days',
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

  public static function leaveCalculate($array)
  {
    if ($array->type == 'approval') {
      $attendance = LeaveApplication::where([['admin_id', $array->admin_id], ['id', $array->id]])->update(['status' => 1]);
    } else {
      $attendance = LeaveApplication::where([['admin_id', $array->admin_id], ['id', $array->id]])->update(['status' => 2]);
    }
    return true;
  }


  public function leaveType()
  {
    return $this->belongsTo(LeaveTypeInOut::class, 'request_leave_type_out_id', 'id');
  }

  public function requetLeave()
  {
    return $this->belongsTo(LeaveTypeInOut::class, 'request_leave_type_in_id', 'id');
  }

  public function approveLive()
  {
    return $this->belongsTo(LeaveTypeInOut::class, 'approve_leave_type_out_id', 'id');
  }

  public function leaveOut()
  {
    return $this->belongsTo(LeaveTypeInOut::class, 'approve_leave_type_in_id', 'id');
  }

  public function getTeamleaderAttribute()
  {
    return $this->leaveType ?: $this->requetLeave;
  }

  public static function checkLeaveBalance($ary)
  {
    $userData     =   User::getUserDetails($ary->user_id, 'emp');
    $userPolicy   =   UserPolicy::getEmployeePolicy($userData->policy_id);
    $year =  !empty($ary->date) ? date('Y', strtotime($ary->date)) : date('Y');
    $month =  !empty($ary->date) ? date('m', strtotime($ary->date)) : date('m');
    $passed_month = 12 - $month;
    $total_leave_balance = self::yearWiseLeaveBalance($ary, $year);
    $available_leave_blance = $total_leave_balance - ($passed_month * $userPolicy->every_month_paid_leave);
    $pre_year =  date('Y', strtotime('-1 year', strtotime($year)));
    if ($userPolicy->carry_forward_year == 1 && $pre_year <= date('Y', strtotime($userData->joined_date)) && $userPolicy->carry_forward_till_month >= date('m')) {
      $pre_leave = self::yearWiseLeaveBalance($ary, $pre_year);
      $total_leave_balance    += $pre_leave;
      $available_leave_blance += $pre_leave;
    }
    $request_leave = self::requestedLeave($ary, $year);
    $auto_leave = self::autoLeave($ary, $year);
    $available_leave_blance -= $request_leave;
    $available_leave_blance -= $auto_leave;
    $array = (object)array(
      'total_leave_balance' => $total_leave_balance,
      'available_leave_blance' => $available_leave_blance,
    );
    return  $array;
  }

  public static function autoLeave($ary, $year)
  {
    $thismodel = DB::table('salaries')->select(DB::raw("SUM(auto_leave) as al"));

    $thismodel->where('admin_id', $ary->admin_id)->where('user_id', $ary->user_id);

    $thismodel->where('salaries.salary_name', 'Like', '%' . $year . '%');
    // pr(getQuery($thismodel));die;
    $data =  $thismodel->first();
    $al = !empty($data) ? $data->al : 0;
    return $al;
  }


  public static function requestedLeave($ary, $year)
  {
    $thismodel = DB::table('leave_applications')->select(DB::raw("SUM(request_day) as leaves"));
    $thismodel->groupBy('leave_applications.leave_type_id');

    $thismodel->where('admin_id', $ary->admin_id)->where('user_id', $ary->user_id);
    $thismodel->where('leave_type_id', $ary->leave_type_id);
    $thismodel->where('status', 0);

    $thismodel->whereYear('leave_applications.request_start_date', $year);
    // pr(getQuery($thismodel));die;
    $data =  $thismodel->first();
    $leave = !empty($data) ? $data->leaves : 0;
    return $leave;
  }


  public static function yearWiseLeaveBalance($ary, $year)
  {
    $userData     =   User::getUserDetails($ary->user_id, 'emp');
    $userPolicy   =   UserPolicy::getEmployeePolicy($userData->policy_id);

    $thismodel = DB::table('leave_applications')->select(DB::raw("SUM(approve_day - remove_days) as leaves"));
    $thismodel->groupBy('leave_applications.leave_type_id');

    $thismodel->where('admin_id', $ary->admin_id)->where('user_id', $ary->user_id);
    $thismodel->where('leave_type_id', $ary->leave_type_id);
    $thismodel->where('status', 1);

    $thismodel->whereYear('leave_applications.approve_start_date', $year);
    // pr(getQuery($thismodel));die;


    $data =  $thismodel->first();

    $leave = !empty($data) ? $data->leaves : 0;

    $pl = $userPolicy->pl;
    $adminData = User::where('id', $userData->admin_id)->first();
    $admin_date = date('Y-m-d', strtotime($adminData->created_at));
    $joined_date = $userData->joined_date;
    $date = max([$admin_date, $joined_date]);
    if ($year == date('Y', strtotime($date))) {
      $month =  date('m', strtotime($date));
      $passed_month = $month - 1;
      if ($passed_month > 0) {
        $pl = $userPolicy->pl - ($passed_month * $userPolicy->every_month_paid_leave);
      }
    }

    $available_blance = $pl - $leave;
    return $available_blance;
  }

  public static function leaveApproval($leaveData, $request = '')
  {
    $userData = User::getUserDetails($leaveData['user_id'], 'emp');
    if ($request['status'] == '1') {
      // dd($request['approve_day']);
      $array = [
        'status'                      => $request['status'],
        'approve_start_date'          => $request['approve_start_date'],
        'approve_leave_type_out_id'   => $request['approve_leave_type_out_id'],
        'approve_end_date'            => $request['approve_end_date'],
        'approve_leave_type_in_id'    => $request['approve_leave_type_in_id'],
        'approve_remark'              => $request['approve_remark'],
        'approve_day'                 => $request['approve_day'],
        'approved_by'                 => $request['approved_by'],
        'approve_date'                => date('Y-m-d')
      ];
      $leave = LeaveApplication::where('id', $leaveData['id'])->update($array);
      $leaveData = LeaveApplication::where('id', $leaveData['id'])->first();
      self::leavetoAttendance($leaveData);
      MyCommand::fireBaseNotification($userData->id, 'Leave Request Approved', 'Your leave request is approved for '.$leaveData->approve_day.' day');
    } elseif ($request['status'] == '2') {
      LeaveApplication::where('id', $leaveData['id'])->update([
        'status' => $request['status'],
      ]);
      MyCommand::fireBaseNotification($userData->id, 'Leave Request Cancel', 'Your leave request is cancel.');
    } elseif ($request['status'] == '0') {
      LeaveApplication::where('id', $leaveData['id'])->update([
        'status' => $request['status'],
      ]);
    }
    return $request['status'];
  }

  public static function leavetoAttendance($leaveData)
  {
    if ($leaveData->leave_type_id == 1) {
      $startDate = Carbon::parse($leaveData['approve_start_date']);
      $endDate = Carbon::parse($leaveData['approve_end_date']);

      $period = CarbonPeriod::create($startDate, $endDate);

      // Iterate over each date in the period
      foreach ($period as $date) {
        $attendance = Attendance::where([['admin_id', $leaveData->admin_id], ['user_id', $leaveData->user_id], ['shift_id', $leaveData->shift_id], ['attendance_date', $date->format('Y-m-d')], ['status', 1]])->first();

        $attendance_status = 'L';
        $description = 'Leave';
        if ($date == date_create($leaveData->approve_start_date) && $leaveData->approve_leave_type_in_id != 1) {
          $attendance_status = 'HL';
          $description = 'Haif Leave';
        }
        if ($date == date_create($leaveData->approve_end_date) && $leaveData->approve_leave_type_out_id != 1) {
          $attendance_status = 'HL';
          $description = 'Haif Leave';
        }
        if (!empty($attendance) && ($attendance->attendance_status == "HD" || $attendance->attendance_status == "MP")) {
          $attendance_status = 'HD-HL';
          $description = 'Haif Day and Haif Leave';
        }
        $attendance_array = array(
          'admin_id' => $leaveData->admin_id,
          'user_id' => $leaveData->user_id,
          'leave_id' => $leaveData->id,
          'shift_id' => $leaveData->shift_id,
          'attendance_type' => 'None',
          'attendance_status' => $attendance_status,
          'description' => $description,
          'status'    => '1',
          'attendance_date' => $date->format('Y-m-d'),
        );

        if (!empty($attendance)) {
          $attendance->update($attendance_array);
        } else {
          $attendance->create($attendance_array);
        }
      }
    }
  }
}
