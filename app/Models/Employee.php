<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'admin_id',
        'user_id',
        'shift_rooster',
        'employee_code',
        'machine_code',
        'policy_id',
        'is_manager',
        'authorised_person_id',
        'company_id',
        'location_id',
        'department_id',
        'is_tracking_on',
        'designation_id',
        'shift_id',
        'hire_date',
        'joined_date',
        'termination_date',
        'termination_reason',
        'termination_type_id',
        'contract_type',
        'pf_status',
        'pf_no',
        'esic_status',
        'esic_no',
        'uan_no',
        'vpf',
        'vpf_value',
        'eps_status',
        'eps_no',
        'eps_option',
        'working_status',
        'policy_id',
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
        return $this->hasOne(User::class, 'user_id ', 'id');
    }
}
