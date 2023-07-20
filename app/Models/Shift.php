<?php

namespace App\Models;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model
{
    use HasFactory, Sortable;
    protected $fillable = [
        'admin_id',
        'shift_name',
        'shift_type',          
        'from_time',
        'to_time',         
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

    public $sortable = ['id', 'shift_name', 'shift_type', 'from_time', 'to_time', 'status', 'created_at', 'updated_at'];


    public static function getUserShift($user_id)
    {
        $userData =  User::getUserDetails($user_id,'emp');
        if($userData->shift_rooster == 1){

        }else{
            $data = Shift::where('shifts.id',$userData->shift_id)->select(['shifts.*'])->first();
        }
        // $data = Employee::leftJoin('shifts', function ($join) {
        //     $join->on('employees.shift_id', '=', 'shifts.id');    
        // })->where('employees.user_id',$user_id)->where('shifts.status',1)->select(['shifts.*'])->first();
        return $data;
    }
}
