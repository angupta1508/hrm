<?php

namespace App\Models;

use DateTime;
use Carbon\Carbon;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalarySetup extends Model
{
    use HasFactory, Sortable;
    protected $fillable = [
        'admin_id',
        'user_id',
        'salary_type_id',   
        'basic_salary',
        'salary_based_on',
        'dearness_allowance',
        'washing_allowance',
        'house_rant_allowance',
        'conveyance_allowance',
        'per_hour_overtime_amount',
        'salary_based_on',
        'medical_allowance',
        'other_allowance',
        'fix_incentive',
        'variable_incentive',
        'deductions',
        'welfare_fund',
        'affected_date',
        'created_by',
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

    public static function getUserSalarySetUp($request)
    {
        $year  = date('Y', strtotime($request->month));
        $month  = date('m', strtotime($request->month));
        $newDate = Carbon::create($year, $month, '01')->format('Y-m-d');
        $salary = SalarySetup::where('admin_id', $request->admin_id)->where('user_id', $request->user_id)->where('affected_date', '<=', $newDate)->orderBy('affected_date', 'DESC')->first();
        return $salary;
    }
}
