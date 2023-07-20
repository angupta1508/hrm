<?php

namespace App\Models;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Sortable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'role_id',
        'user_uni_id',
        'package_uni_id',
        'package_valid_date',
        'admin_id',
        'istrial',
        'company_code',
        'gateway_id',
        'name',
        'email',
        'mobile',
        'username',
        'mobile_verification_status',
        'mobile_otp',
        'password',
        'profile_image',
        'father_name',
        'mother_name',
        'gender',
        'dob',
        'religion',
        'marital_status',
        'spouse_name',
        'anniversary_date',
        'education_qualification',
        'technical_qualification',
        'alternate_mobile',
        'aadhaar_no',
        'pan_no',
        'driving_license_no',
        'process_status',
        'passport_no',
        'present_address',
        'present_city_id',
        'present_state_id',
        'present_country_id',
        'present_pincode',
        'address',
        'city_id',
        'state_id',
        'country_id',
        'pincode',
        'latitude',
        'longitude',
        'device_id',
        'user_fcm_token',
        'user_ios_token',
        'trash',
        'status',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
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

    protected $appends = ['full_info'];

    public function getFullInfoAttribute()
    {
        return $this->name . ' (' . $this->username . ') {' . $this->email . ' / ' . $this->mobile . '} [' . $this->user_uni_id . ']';
    }

    public $sortable = ['id', 'role_id', 'user_uni_id', 'name', 'email', 'username', 'mobile', 'status', 'created_at', 'updated_at'];

    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            unset($this->attributes['password']);
        }
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function apikey()
    {
        return $this->belongsTo(ApiKeys::class, 'user_uni_id', 'user_uni_id');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id ', 'user_id');
    }

    public function location()
    {
        return $this->belongsToMany(Location::class, 'location_users', 'user_id', 'location_id');
    }

    public static function getUserDetails($id, $type = '')
    {
        if ($type == 'emp') {
            $data = User::leftJoin('employees', function ($join) {
                $join->on('users.id', '=', 'employees.user_id');
            });
            $data->leftJoin('users as authorised_person', function ($join) {
                $join->on('employees.authorised_person_id', '=', 'authorised_person.id');
            });
            $data->leftJoin('companies', function ($join) {
                $join->on('employees.company_id', '=', 'companies.id');
            });
            $data->leftJoin('departments', function ($join) {
                $join->on('employees.department_id', '=', 'departments.id');
            });
            $data->leftJoin('designations', function ($join) {
                $join->on('employees.designation_id', '=', 'designations.id');
            });
            $data->leftJoin('shifts', function ($join) {
                $join->on('employees.shift_id', '=', 'shifts.id');
            });
            $data->leftJoin('user_bankers', function ($join) {
                $join->on('users.id', '=', 'user_bankers.user_id');
            });
            $data->leftJoin('banks', function ($join) {
                $join->on('user_bankers.bank_id', '=', 'banks.id');
            });
            $user   =   $data->select([
                'employees.*',
                'users.*',
                'users.id',
                'users.status',
                'authorised_person.name  as  author_name',
                'companies.name as company_name',
                'departments.department_name',
                'designations.name as designation_name',
                'shifts.id as shift_id',
                'shifts.shift_name as shift_name',
                'shifts.shift_type',
                'shifts.from_time',
                'shifts.to_time',
                'user_bankers.account_no',
                'user_bankers.account_type',
                'user_bankers.ifsc_code',
                'user_bankers.account_name',
                'banks.bank_name',
                'users.created_at',
                'users.updated_at',
                'employees.created_at as employee_created_at',
                'employees.updated_at as employee_updated_at',
            ])->where('users.id', $id)->with('location')->first();

                
            $imgPath = config('constants.user_image_path');
            $imgDefaultPath = config('constants.default_user_image_path');
            $user->profile_image = ImageShow($imgPath, $user->profile_image, 'icon', $imgDefaultPath);
        } else {
            $user = User::where('users.id', $id)->first();
            $imgPath = config('constants.user_image_path');
            $imgDefaultPath = config('constants.default_user_image_path');
            $user->profile_image = ImageShow($imgPath, $user->profile_image, 'icon', $imgDefaultPath);
        }
        return $user;
    }

    public static function getUserrAdminData($admin_id)
    {
        $user = User::where('admin_id', $admin_id)->first();
        return $user;
    }


    public static function getUserData($filter_array, $is_first = 0)
    {
        $offset = 0;
        $records = [];
        // dd($filter_array);
        $loggedUser = Auth::guard('front-user')->user();
     
        $employe =  Employee::leftJoin('users', function ($join) {
            $join->on('users.id', '=', 'employees.user_id');
        })->leftJoin('designations', function ($join) {
            $join->on('designations.id', '=', 'employees.designation_id');
        })->select([
                'employees.*', 'users.*', 'designations.name as designation'
            ]);

        if (!empty($filter_array['search'])) {
            $search = $filter_array['search'];
            $employe->where(function ($query) use ($search) {
                $query->where('users.name', 'LIKE', '%' . $search . '%')->orwhere('users.mobile', 'LIKE', '%' . $search . '%')
                ->orwhere('employees.employee_code', 'LIKE', '%' . $search . '%');
            });
        }

        if (!empty($filter_array['gender'])) {
            $gender = $filter_array['gender'];
            $employe->where('users.gender', $gender);
        }

        if (!empty($filter_array['mobile'])) {
            $employe->where('users.mobile', $filter_array['mobile']);
        }

        if (!empty($filter_array['designation_id'])) {
            $employe->where('employees.designation_id', $filter_array['designation_id']);
        }
        if (!empty($filter_array['shift_id'])) {
            $employe->where('employees.shift_id', $filter_array['shift_id']);
        }

        if (!empty($filter_array['admin_id'])) {
            $employe->where('employees.admin_id', $filter_array['admin_id']);
        }

        if (!empty($filter_array['user_id'])) {
            $employe->where('employees.user_id', $filter_array['user_id']);
        }

        if (isset($filter_array['status']) && $filter_array['status'] != '') {
            $employe->where('users.status', $filter_array['status']);
        }

        if (!empty($filter_array['authorised_person_id'])) {
            $employe->where('employees.authorised_person_id', $filter_array['authorised_person_id']);
        }


        $employe->orderBy('users.id', 'DESC');
        if (isset($filter_array['offset']) && $filter_array['offset'] > -1) {
            $employe->offset($filter_array['offset'])->limit(!empty($filter['limit']) ? $filter['limit'] : config('constants.api_page_limit'));
        }

        // dd(getQuery($employe));die;
        if ($is_first == 2) {
            $record =   $employe->get()->count();
        } else if ($is_first == 1) {
            $record =    $employe->first();
            // dd($record);
            $user = ApiKeys::where('id', "=", $record['user_id'])->first();
            $record->user_api_key = !empty($user['api_key']) ? $user['api_key'] : '';
            $records = Api::getUserAssets($record);
        } else {
            $records =    $employe->get();

            if ($records->count() > 0) {
                foreach ($records as $key => $value) {
                    $user = User::where('id', "=", $value['user_id'])->get();
                    $value['user_api_key'] = !empty($user['api_key']) ? $user['api_key'] : '';
                    $records[$key] = User::getUserAssets($value);
                }
            }
        }


        return $records;
    }

    public static function getUserAssets($user)
    {
        // if (!empty(($user['name']) && !empty($user['phone']) && !empty($user['email']) && !empty($user['birth_date']) && !empty($user['birth_time']) && !empty($user['birth_place']) && !empty($user['latitude']) && !empty($user['longitude']))) {
        //     $user['process_status'] = 1;
        // } else {
        //     $user['process_status'] = 0;
        // }

        $imgPath = public_path(config('constants.user_image_path'));
        if (!empty($user['profile_image']) && file_exists($imgPath . $user['profile_image'])) {
            $user['profile_image'] = url(config('constants.user_image_path') . $user['profile_image']);
        } else {
            $user['profile_image'] = asset(config('constants.default_user_image_path'));
        }

        return $user;
    }

    public static function getAdminActiveUser($admin_id){
        $count = User::where('admin_id',$admin_id)->where('role_id',config('constants.employee_role_id'))->where('status',1)->count();
        return $count;
    }
}
