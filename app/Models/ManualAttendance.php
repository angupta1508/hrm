<?php

namespace App\Models;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ManualAttendance extends Model
{
    use HasFactory, Sortable;
    protected $fillable = [
        'admin_id',
        'user_id',
        'shift_id',
        'authorised_person_id',
        'attendance_reason_id',
        'from_time',
        'to_time',
        'late_in',
        'early_out',
        'request_remark',
        'request_hard_copy',
        'attendance_date',
        'approve_remark',
        'approve_date',
        'approved_by',
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


    public static function manualAttendanceList($request)
    {
       // dd($request);
       $offset = !empty($request->offset) ? $request->offset : '0';
       $page_limit = config('constants.api_page_limit');
       $attendance = ManualAttendance::leftJoin('users as user', function ($join) {
           $join->on('manual_attendances.user_id', '=', 'user.id');
       });
       $attendance->leftJoin('users as auth_person', function ($join) {
           $join->on('manual_attendances.authorised_person_id', '=', 'auth_person.id');
       });
       $attendance->leftJoin('attendance_reasons', function ($join) {
           $join->on('manual_attendances.attendance_reason_id', '=', 'attendance_reasons.id');
       });
       $attendance->select([
           'manual_attendances.*', 'user.username as user_username',
           'auth_person.username as authorised_person_username',
           'attendance_reasons.name as attendance_reasons_name'
           
       ]);
       $attendance->where('manual_attendances.admin_id', $request->admin_id);
       $attendance->where('manual_attendances.user_id', $request->user_id);
       if(isset($request->status) && $request->status != ''){
           $attendance->where('manual_attendances.status', $request->status);
           
       }
       $attendance->offset($offset)->limit($page_limit);
       $attendance->orderBy('manual_attendances.id', 'DESC');
       // pr(getQuery($attendance));die;
       $attendances = $attendance->get();
       return $attendances;
    }
}