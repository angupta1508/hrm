<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LeaveTypeInOut extends Model
{
    use HasFactory, Sortable;
    
    protected $table = 'leave_type_in_out';
    protected $fillable = [
        'name',         
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

    public $sortable = ['id', 'name', 'status', 'created_at', 'updated_at'];

    public function user_bankes()
    {
        // return $this->hasMany(UserBanker::class, 'bank_id', 'id');
    }
    
 

    public function leave_applications()
    {
        return $this->hasMany(LeaveApplication::class, 'request_leave_type_out_id', 'id')
                    ->orWhere('request_leave_type_in_id', $this->id)
                    ->orWhere('approve_leave_type_out_id', $this->id)
                    ->orWhere('approve_leave_type_in_id', $this->id);
    }
  
}
