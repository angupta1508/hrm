<?php

namespace App\Models;

use App\Models\User;
use Laravel\Sanctum\HasApiTokens;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserPolicy extends Model
{
    use HasApiTokens, HasFactory, Notifiable, Sortable;
    protected $fillable = [
        'admin_id',
        'policy_name',
        'eneble_working_hours_relaxation',
        'fullday_relaxation',
        'halfday_relaxation',
        'eneble_late_coming',
        'late_coming_relaxation',
        'late_coming_deduction_repeate',
        'eneble_early_going',
        'early_going_relaxation',
        'early_going_deduction_repeate',
        'overtime_apply_time',
        'eneble_overtime_working_day',
        'eneble_holiday_working_hours',
        'holiday_working_hours',
        'eneble_weekoff_working_hours',
        'eneble_weekday_for_weekend',
        'weekday_for_weekend',
        'weekoff_working_hours',
        'eneble_sandwich',
        'autual_month_day',
        'cl',
        'pl',
        'medical_leave',
        'paternity_leave',
        'maternity_leave',
        'every_month_paid_leave',
        'carry_forward_month',
        'carry_forward_year',
        'carry_forward_till_month',
        'status',
    ];

    // public function User()
    // {
    //     return $this->belongsTo(User::class);
    // }


    public static function getEmployeePolicy($id)
    {
        $userPolicy = UserPolicy::where('id',$id)->where('status',1)->first();
        return $userPolicy;
    }
}
