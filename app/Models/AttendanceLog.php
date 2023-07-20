<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceLog extends Model
{
    use HasFactory,Sortable;
    protected $fillable = [
        'admin_id',
        'user_id',   
        'punch_type',   
        'from_where',                    
        'punch_time',
        'image',           
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

    public static function attendanceLog($request)
    {
        if (!empty($request->image)) {
            $file = $request->image;
            $imgPath = public_path(config('constants.attendance_image_path'));
            $filename = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
            $file->move($imgPath, $filename);
            $image = $filename;
        }

        $attendance_log = array(
            'admin_id' => $request->admin_id,
            'user_id' => $request->user_id,
            'punch_type' => $request->punch_type,
            'from_where' => $request->from_where,
            'punch_time' => Config::get('current_datetime'),
            'image' => !empty($image) ? $image : '',
            'created_at' => Config::get('current_datetime'),
        );
        $attendanceLog = AttendanceLog::create($attendance_log);
        return $attendanceLog;
    }
}
