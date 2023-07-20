<?php

namespace App\Http\Controllers\Backend;

use PDF;
use Carbon\Carbon;
use App\Exports\ExportUser;
use App\Http\Controllers\Controller;
use App\Models\Api;
use App\Models\Employee;
use App\Models\ModuleAccess;
use App\Models\Package;
use App\Models\RechargeHistory;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use App\Rules\UserUniqueRule;
// use Barryvdh\DomPDF\PDF;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Imports\EmployeeUser;
use App\Models\UserLocationTrack;
use App\Models\Attendance;
use App\Models\LeaveApplication;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // Session::put('variableName', $request->input("idevento"));

    public function index(Request $request)
    {
        $loggedUser = Auth::user();
        $companies = getCompanyList();
        $departments = getDepartmentList();
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        $locations = getLocationList();
        $designation = getDesignationList();
        $managers = getMangerList();
        $shifts = getShiftList();
        $routeData = getRoleFromRoute($request->route());
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }

        $limit = config('constants.default_page_limit');
        $filter = $request->query();
        $thismodel = User::sortable(['created_at' => 'desc']);

        if (!joined($thismodel, 'roles')) {
            $thismodel->leftJoin('roles', function ($join) {
                $join->on('users.role_id', '=', 'roles.id');
            })->leftJoin('location_users', function ($join) {
                $join->on('users.id', '=', 'location_users.user_id');
            })->leftJoin('employees', function ($join) {
                $join->on('users.id', '=', 'employees.user_id');
            })->leftJoin('user_location_tracks', function ($join) {
                $join->on('users.id', '=', 'user_location_tracks.user_id');
            });
        }


        $thismodel->leftJoin('countries', function ($join) {
            $join->on('users.country_id', '=', 'countries.id');
        });
        $thismodel->leftJoin('states', function ($join) {
            $join->on('users.state_id', '=', 'states.id');
        });
        $thismodel->leftJoin('cities', function ($join) {
            $join->on('users.city_id', '=', 'cities.id');
        });
        $thismodel->leftJoin('departments', function ($join) {
            $join->on('employees.department_id', '=', 'departments.id');
        });
        


        // if (isset($filter['status']) && $filter['status'] != "") {
        //     $thismodel->where('status', $filter['status']);
        // }


        if ($loggedUser->role_id != 1) {
            $thismodel->where('users.admin_id', $loggedUser->admin_id);
        }
        $thismodel->where('users.trash', 0);

        // dd(getQuery($thismodel));

        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('users.name', 'LIKE', '%' . $keyword . '%')->orwhere('user_location_tracks.admin_id', 'LIKE', '%' . $keyword . '%')->orwhere('employees.employee_code', 'LIKE', '%' . $keyword . '%')
                    ->orwhere('users.username', 'LIKE', '%' . $keyword . '%')->orwhere('users.email', 'LIKE', '%' . $keyword . '%')->orwhere('users.mobile', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (isset($filter['user_id']) && $filter['user_id'] != "") {
            $thismodel->where('users.id', $filter['user_id']);
        }
        if (isset($filter['company_id']) && $filter['company_id'] != "") {
            $thismodel->where('employees.company_id', $filter['company_id']);
        }
        if (isset($filter['department_id']) && $filter['department_id'] != "") {
            $thismodel->where('departments.id', $filter['department_id']);
        }
        if (isset($filter['location_id']) && $filter['location_id'] != "") {
            $thismodel->where('location_users.location_id', $filter['location_id']);
        }
        if (isset($filter['designation_id']) && $filter['designation_id'] != "") {
            $thismodel->where('employees.designation_id', $filter['designation_id']);
        }
        if (isset($filter['shift_id']) && $filter['shift_id'] != "") {
            $thismodel->where('employees.shift_id', $filter['shift_id']);
        }
        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('users.status', $filter['status']);
        }
        if (isset($filter['date']) && $filter['date'] != "") {
            $thismodel->where('users.date', $filter['date']);
        }
        if (!empty($filter['start_date'])) {
            $start_date_format = mysqlDateFormat($filter['start_date']);
            // dd($start_date_format);
            $thismodel->whereDate('users.created_at', '>=', $start_date_format);
        }
        if (!empty($filter['end_date'])) {
            $end_date_format = mysqlDateFormat($filter['end_date']);
            $thismodel->whereDate('users.created_at', '<=', $end_date_format);
        }
        //  dd($routeId);
        if (!empty($routeId)) {
            $thismodel->where('role_id', '=', $routeId);
        } else {
            $thismodel->where('roles.role_type', '=', 'User');
        }

        $thismodel->select([
            'users.*', 'roles.name as role_name'
            // 'roles.id as role_id'
            , 'employees.company_id','employees.employee_code', 'cities.name as city_name', 'states.name as state_name', 'countries.name as country_name', 'departments.id as department_name',
        ])->groupBy('users.id');
        // dd(getQuery($ff));


        if (!empty($filter['excel_export']) || !empty($filter['pdf_export'])) {

            $headings = [
                "Admin Id", "User Id", "Username", "Role Id", "Name", "Email", "Mobile", "Address", "City", "State", "Country", "Gender", "Latitude", "Longitude", "Status", "Created Date", "updated Date",
            ];

            $thismodel->select([
                'users.admin_id', 'users.user_uni_id', 'users.username', 'users.role_id', 'users.name', 'users.email', 'users.mobile', 'users.address', 'cities.name as city_name', 'states.name as state_name', 'countries.name as country_name', 'users.gender', 'users.latitude', 'users.longitude', 'users.status', 'users.created_at', 'users.updated_at',
            ]);
            $records = $thismodel->get();
            $records = $records->each->setAppends([]);

            if (!empty($routeSlug)) {
                $file = $routeSlug;
            } else {
                $file = 'users';
            }
            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  $file . ' List'
            ];
            // dd($headings);
            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings, $header), $file . '.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }
                // dd($tabel_keys);
                $variabls = [
                    'top_heading' => 'User List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,
                ];


                $pdf = PDF::loadview('pdf', $variabls);
                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file . '.pdf');
            }
        }
        // dd(getQueryWithBindings($thismodel));
        // $users = $thismodel->get();
        // dd($users->toArray());
        // dd(getQuery($thismodel));
        // $thismodel->groupBy('users.id');
        // pr(getQuery($thismodel));die;
        // dd($thismodel->get());

        $users = $thismodel->paginate($limit);
        return view('backend.users.index', compact('users', 'filter', 'routeSlug', 'routeId', 'locations', 'companies', 'departments', 'designation', 'shifts', 'user_list'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $LoggedUser = Auth::user();
        $routeData = getRoleFromRoute($request->route());
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }
        $package = Package::where([['status', 1], ['trash', 0]])->get();
        $country_id = old('country_id', config('constants.default_country'));
        $state_id = old('state_id', config('constants.default_state'));
        $state_list = $city_list = '';
        $country_list = getCountrylist();
        $state_list = getStatelist($country_id);
        $city_list = getCitylist($state_id);
        $role_list = getRolelist();
        $companies = getCompanyList();
        $departments = getDepartmentList();
        $locations = getLocationList();
        $designation = getDesignationList();
        $managers = getMangerList();
        $shifts = getShiftList();
        $policy = getPolicyList();


        return view('backend.users.create', compact('role_list', 'country_list', 'state_list', 'city_list', 'routeSlug', 'routeId', 'package', 'companies', 'departments', 'locations', 'designation', 'shifts', 'managers', 'policy'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $routeData = getRoleFromRoute($request->route());
        // dd($routeData);
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }
        // dd($request);
        $loggedUser = Auth::user();
        if (!checkUserLimit()) {
            back()->with('error', __('User limit has been reached. Cannot do this operation.'));
        }
        $attributes = request()->validate([
            'role_id' => ['required', 'numeric'],
            'name' => ['required', 'max:50'],
            'email' => ['nullable', 'email', 'max:50', Rule::unique('users')],
            'username' => ['nullable', 'max:50', Rule::unique('users')],
            'mobile' => ['required', Rule::unique('users')],
            'password' => ['required', 'min:5', 'max:20'],
            'city_id' => ['nullable'],
            'state_id' => ['nullable'],
            'country_id' => ['nullable'],
            'profile_image' => ['nullable'],
            'gender' => ['nullable'],
            'address' => ['nullable'],
            'package' => ['nullable'],
            'alternate_mobile' => ['nullable'],
            'pincode' => ['nullable'],

            'policy_id' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'company_code' => ['required_if:role_id,' . config("constants.admin_role_id")],

            'father_name' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'mother_name' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'dob' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'religion' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'marital_status' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'spouse_name' => ['required_if:marital_status,"married"'],

            'anniversary_date' => ['required_if:marital_status,"married"'],

            'aadhaar_no' => ['nullable'],

            'pan_no' => ['nullable'],

            'driving_license_no' => ['nullable'],

            'passport_no' => ['nullable'],

            'employee_code' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'machine_code' => ['nullable'],

            'is_manager' => ['nullable'],

            'technical_qualification' => ['nullable'],

            'education_qualification' => ['nullable'],

            'authorised_person_id' =>  ['nullable'],

            'company_id' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'location_id' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'is_tracking_on' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'department_id' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'designation_id' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'shift_id' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'hire_date' => ['nullable'],

            'joined_date' => ['nullable'],

            'termination_date' => ['nullable'],

            'termination_reason' => ['nullable'],

            'termination_type_id' => ['nullable'],

            'contract_type' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'pf_status' => ['nullable'],

            'pf_no' => ['nullable'],

            'esic_status' => ['nullable'],

            'esic_no' => ['nullable'],

            'uan_no' => ['nullable'],

            'vpf' => ['nullable'],

            'vpf_value' => ['nullable'],

            'eps_status' => ['nullable'],

            'eps_no' => ['nullable'],

            'eps_option' => ['nullable'],

            'working_status' => ['nullable'],

            'profile_image' => ['nullable'],


        ]);
        // dd($attributes);
        if ($attributes['role_id'] == config('constants.superadmin_role_id')) {
            $attributes['user_uni_id'] = new_sequence_code('SUP');
        } elseif ($attributes['role_id'] == config('constants.superadmin_staff_role_id')) {
            $attributes['user_uni_id'] = new_sequence_code('SUP-STF');
            $attributes['admin_id']     = $loggedUser->id;
        } elseif ($attributes['role_id'] == config('constants.admin_role_id')) {
            $attributes['user_uni_id'] = new_sequence_code('ADM');
            $attributes['package_uni_id'] = $attributes['package'];
            $package = Package::where([['package_uni_id', $attributes['package']]])->first();
            $attributes['package_valid_date'] = date('Y-m-d', strtotime(Config::get('current_date') . ' +' . $package->duration . 'day'));
        } elseif ($attributes['role_id'] == config('constants.admin_staff_role_id')) {
            $attributes['admin_id']     = $loggedUser->id;
            $attributes['user_uni_id'] = new_sequence_code('ADM-STF');
        } elseif ($attributes['role_id'] == config('constants.employee_role_id')) {
            $attributes['admin_id']     = $loggedUser->admin_id;
            $attributes['user_uni_id'] = new_sequence_code('EMP');
        }

        if (!empty($attributes['profile_image'])) {
            $imgKey = 'profile_image';
            $imgPath = public_path(config('constants.user_image_path'));
            $filename = UploadImage($request, $imgPath, $imgKey);
            if (!empty($filename)) {
                $attributes['profile_image'] = $filename;
            }
        }

        if ($attributes['role_id'] == config('constants.employee_role_id')) {
            $emp['employee_code'] = !empty($attributes['employee_code']) ? $attributes['employee_code'] : '';

            $emp['machine_code'] = !empty($attributes['machine_code']) ? $attributes['machine_code'] : '';

            $emp['is_manager'] = !empty($attributes['is_manager']) ? $attributes['is_manager'] : '0';

            $emp['authorised_person_id'] = !empty($attributes['authorised_person_id']) ? $attributes['authorised_person_id'] : '';

            $emp['company_id'] = !empty($attributes['company_id']) ? $attributes['company_id'] : '';

            $emp['department_id'] = !empty($attributes['department_id']) ? $attributes['department_id'] : '';

            $emp['designation_id'] = !empty($attributes['designation_id']) ? $attributes['designation_id'] : '';

            $emp['shift_id'] = !empty($attributes['shift_id']) ? $attributes['shift_id'] : '';

            $emp['policy_id'] = !empty($attributes['policy_id']) ? $attributes['policy_id'] : '';

            $emp['hire_date'] = !empty($attributes['hire_date']) ? $attributes['hire_date'] : '';

            $emp['joined_date'] = !empty($attributes['joined_date']) ? $attributes['joined_date'] : '';

            $emp['termination_date'] = !empty($attributes['termination_date']) ? $attributes['termination_date'] : '';

            $emp['termination_reason'] = !empty($attributes['termination_reason']) ? $attributes['termination_reason'] : '';

            $emp['termination_type_id'] = !empty($attributes['termination_type_id']) ? $attributes['termination_type_id'] : '';

            $emp['contract_type'] = !empty($attributes['contract_type']) ? $attributes['contract_type'] : '';

            $emp['is_tracking_on'] = !empty($attributes['is_tracking_on']) ? $attributes['is_tracking_on'] : '0';

            $emp['pf_status'] = !empty($attributes['pf_status']) ? $attributes['pf_status'] : '0';

            $emp['pf_no'] = !empty($attributes['pf_no']) ? $attributes['pf_no'] : '';

            $emp['esic_status'] = !empty($attributes['esic_status']) ? $attributes['esic_status'] : '';

            $emp['esic_no'] = !empty($attributes['esic_no']) ? $attributes['esic_no'] : '';

            $emp['uan_no'] = !empty($attributes['uan_no']) ? $attributes['uan_no'] : '';

            $emp['vpf'] = !empty($attributes['vpf']) ? $attributes['vpf'] : '';

            $emp['vpf_value'] = !empty($attributes['vpf_value']) ? $attributes['vpf_value'] : '';

            $emp['eps_status'] = !empty($attributes['eps_status']) ? $attributes['eps_status'] : '0';

            $emp['eps_no'] = !empty($attributes['eps_no']) ? $attributes['eps_no'] : '';

            $emp['eps_option'] = !empty($attributes['eps_option']) ? $attributes['eps_option'] : '';

            $emp['working_status'] = !empty($attributes['working_status']) ? $attributes['working_status'] : '';
        }
        $attributes['status'] = 1;
        // dd($emp);
        $user = User::create($attributes);
        if ($user->role_id == config('constants.employee_role_id')) {
            $location_ids = $attributes['location_id'];
            $emp['admin_id'] = $user->admin_id;

            $emp['user_id'] = $user->id;
            Employee::create($emp);
            $user->location()->attach($location_ids, ['admin_id' => $loggedUser->admin_id]);
        }
        if ($user->role_id == config('constants.admin_role_id')) {
            User::where('id', $user->id)->update(['admin_id' => $user->id]);
            settingSaver($user->id);
        }

        $routeRedirect = 'admin.users.index';
        if (!empty($routeSlug)) {
            $routeRedirect = 'admin.' . $routeSlug . '.index';
        }

        return redirect()->route($routeRedirect)
            ->with('success', __('User created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = User::find($id);
        $routeData = getRoleFromRoute($request->route());
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }

        return view('backend.users.show', compact('user', 'routeSlug', 'routeId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {

        $routeData = getRoleFromRoute($request->route());
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }

        if ($routeId == config('constants.employee_role_id')) {
            $user = User::getUserDetails($id, 'emp');
        } else {
            $user = User::getUserDetails($id);
        }
        $package = Package::where([['status', 1], ['trash', 0]])->get();
        $country_id = old('country_id', $user->country_id);
        $state_id = old('state_id', $user->state_id);
        $country_list = getCountrylist();
        $state_list = getStatelist($country_id);
        $city_list = getCitylist($state_id);
        $role_list = getRolelist();

        $managers = [];
        $companies = getCompanyList();
        $departments = getDepartmentList();
        $locations = getLocationList();
        $designation = getDesignationList();
        $managers = getMangerList();
        $shifts = getShiftList();
        $policy = getPolicyList();



        // dd($managers);
        return view('backend.users.edit', compact('user', 'role_list', 'country_list', 'state_list', 'city_list', 'routeSlug', 'routeId', 'package', 'companies', 'departments', 'locations', 'designation', 'managers', 'shifts', 'policy'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        // dd($request);
        $user = User::find($id);
        // dd($user);
        $routeData = getRoleFromRoute($request->route());
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }

        $attributes = request()->validate([
            'role_id' => ['required', 'numeric'],
            'name' => ['required', 'max:50'],
            'email' => ['nullable', 'email', 'max:50', new UserUniqueRule($user)],
            'username' => ['nullable', 'max:50', new UserUniqueRule($user)],
            'mobile' => ['required', new UserUniqueRule($user)],
            'city_id' => ['nullable'],
            'state_id' => ['nullable'],
            'country_id' => ['nullable'],
            'profile_image' => ['nullable'],
            'gender' => ['nullable'],
            'address' => ['nullable'],
            'package' => ['nullable'],
            'alternate_mobile' => ['nullable'],
            'pincode' => ['nullable'],
            'company_code' => ['required_if:role_id,' . config("constants.admin_role_id")],

            'policy_id' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'father_name' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'mother_name' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'dob' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'religion' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'spouse_name' => ['required_if:marital_status,"married"'],

            'anniversary_date' => ['required_if:marital_status,"married"'],

            'marital_status' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'aadhaar_no' => ['nullable'],

            'pan_no' => ['nullable'],

            'driving_license_no' => ['nullable'],

            'passport_no' => ['nullable'],

            'employee_code' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'machine_code' => ['nullable'],

            'is_manager' => ['nullable'],

            'technical_qualification' => ['nullable'],

            'education_qualification' => ['nullable'],

            'authorised_person_id' => ['nullable'],

            'company_id' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'location_id' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'is_tracking_on' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'department_id' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'designation_id' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'shift_id' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'hire_date' => ['nullable'],

            'joined_date' => ['nullable'],

            'termination_date' => ['nullable'],

            'termination_reason' => ['nullable'],

            'termination_type_id' => ['nullable'],

            'contract_type' => ['required_if:role_id,' . config("constants.employee_role_id")],

            'pf_status' => ['nullable'],

            'pf_no' => ['nullable'],

            'esic_status' => ['nullable'],

            'esic_no' => ['nullable'],

            'uan_no' => ['nullable'],

            'vpf' => ['nullable'],

            'vpf_value' => ['nullable'],

            'eps_status' => ['nullable'],

            'eps_no' => ['nullable'],

            'eps_option' => ['nullable'],
        ]);
        if (!empty($attributes['profile_image'])) {
            $imgKey = 'profile_image';
            $imgPath = public_path(config('constants.user_image_path'));
            $filename = UploadImage($request, $imgPath, $imgKey, $user->profile_image);
            if (!empty($filename)) {
                $attributes['profile_image'] = $filename;
            }
        }

        if ($attributes['role_id'] == config('constants.employee_role_id')) {
            $emp['employee_code'] = !empty($attributes['employee_code']) ? $attributes['employee_code'] : '';

            $emp['machine_code'] = !empty($attributes['machine_code']) ? $attributes['machine_code'] : '';

            $emp['is_manager'] = !empty($attributes['is_manager']) ? $attributes['is_manager'] : '0';

            $emp['authorised_person_id'] = !empty($attributes['authorised_person_id']) ? $attributes['authorised_person_id'] : '';

            $emp['company_id'] = !empty($attributes['company_id']) ? $attributes['company_id'] : '';

            $emp['department_id'] = !empty($attributes['department_id']) ? $attributes['department_id'] : '';

            $emp['designation_id'] = !empty($attributes['designation_id']) ? $attributes['designation_id'] : '';

            $emp['policy_id'] = !empty($attributes['policy_id']) ? $attributes['policy_id'] : '';

            $emp['shift_id'] = !empty($attributes['shift_id']) ? $attributes['shift_id'] : '';

            $emp['hire_date'] = !empty($attributes['hire_date']) ? $attributes['hire_date'] : '';

            $emp['joined_date'] = !empty($attributes['joined_date']) ? $attributes['joined_date'] : '';

            $emp['termination_date'] = !empty($attributes['termination_date']) ? $attributes['termination_date'] : '';

            $emp['termination_reason'] = !empty($attributes['termination_reason']) ? $attributes['termination_reason'] : '';

            $emp['termination_type_id'] = !empty($attributes['termination_type_id']) ? $attributes['termination_type_id'] : '';

            $emp['contract_type'] = !empty($attributes['contract_type']) ? $attributes['contract_type'] : '';

            $emp['is_tracking_on'] = !empty($attributes['is_tracking_on']) ? $attributes['is_tracking_on'] : '0';

            $emp['pf_status'] = !empty($attributes['pf_status']) ? $attributes['pf_status'] : '0';

            $emp['pf_no'] = !empty($attributes['pf_no']) ? $attributes['pf_no'] : '';

            $emp['esic_status'] = !empty($attributes['esic_status']) ? $attributes['esic_status'] : '';

            $emp['esic_no'] = !empty($attributes['esic_no']) ? $attributes['esic_no'] : '';

            $emp['uan_no'] = !empty($attributes['uan_no']) ? $attributes['uan_no'] : '';

            $emp['vpf'] = !empty($attributes['vpf']) ? $attributes['vpf'] : '';

            $emp['vpf_value'] = !empty($attributes['vpf_value']) ? $attributes['vpf_value'] : '';

            $emp['eps_status'] = !empty($attributes['eps_status']) ? $attributes['eps_status'] : '0';

            $emp['eps_no'] = !empty($attributes['eps_no']) ? $attributes['eps_no'] : '';

            $emp['eps_option'] = !empty($attributes['eps_option']) ? $attributes['eps_option'] : '';

            $emp['working_status'] = !empty($attributes['working_status']) ? $attributes['working_status'] : '';
        }
        // dd($emp);
        $user->update($attributes);
        if ($user->role_id == config('constants.employee_role_id')) {
            $location_id = !empty($attributes['location_id']) ? $attributes['location_id'] : '';
            Employee::where('user_id', $user->id)->update($emp);
            $user->location()->sync($location_id);
        }

        $routeRedirect = 'admin.users.index';
        if (!empty($routeSlug)) {
            $routeRedirect = 'admin.' . $routeSlug . '.index';
        }

        return redirect()->route($routeRedirect)
            ->with('success', __('User updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $routeData = getRoleFromRoute($request->route());
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }
        $user = User::find($id);
        if ($user->role_id == config('constants.employee_role_id')) {
            $user->location()->detach();
            Employee::where('user_id', $id)->delete();
        }
        $user->delete();

        $routeRedirect = 'admin.users.index';
        if (!empty($routeSlug)) {
            $routeRedirect = 'admin.' . $routeSlug . '.index';
        }

        return redirect()->route($routeRedirect)
            ->with('success', __('User deleted successfully.'));
    }

    public function trash(Request $request)
    {
        $routeData = getRoleFromRoute($request->route());
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }
        $arry   =   array('trash' => 1, 'status' => 0);
        User::where('id', $request->id)->update($arry);
        $routeRedirect = 'admin.users.index';
        if (!empty($routeSlug)) {
            $routeRedirect = 'admin.' . $routeSlug . '.index';
        }

        return redirect()->route($routeRedirect)
            ->with('success', 'Customer deleted successfully');
    }

    public function changeStatus(Request $request)
    {
        if (!checkUserLimit()) {
            return  response()->json(['error' => __('User limit has been reached. Cannot do this operation.')]);
        }
        $user = User::find($request->id)->update(['status' => $request->status]);

        return response()->json(['success' => __('Status changed successfully.')]);
    }

    public function importView(Request $request)
    {
        $routeData = getRoleFromRoute($request->route());
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }
        return view('backend.users.import', compact('routeSlug', 'routeId'));
    }
    public function import(Request $request)
    {
        $routeData = getRoleFromRoute($request->route());
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }

        $routeRedirect = 'admin.users.index';
        if (!empty($routeSlug)) {
            $routeRedirect = 'admin.' . $routeSlug . '.index';
        }
        $attributes = request()->validate([
            'file' => ['required'],
        ]);
        
        try {
            Excel::import(new EmployeeUser, $request->file('file')->store('files'));
            return redirect()->route($routeRedirect)->with('success', 'Excel file imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route($routeRedirect)->with('error', 'Excel file is invalid coulumn count');
        }
    }

    public function getUserDetail(Request $request)
    {
        $thismodel = User::where('user_uni_id', $request->user); //->first();

        $thismodel->leftJoin('employees', function ($join) {
            $join->on('users.id', '=', 'employees.user_id');
        })->leftJoin('companies', function ($join) {
            $join->on('employees.company_id', '=', 'companies.id');
        })->leftJoin('departments', function ($join) {
            $join->on('employees.department_id', '=', 'departments.id');
        })->leftJoin('designations', function ($join) {
            $join->on('employees.designation_id', '=', 'designations.id');
        })->leftJoin('shifts', function ($join) {
            $join->on('employees.shift_id', '=', 'shifts.id');
        });


        $thismodel->leftJoin('location_users', function ($join) {
            $join->on('users.id', '=', 'location_users.user_id');
        })->leftJoin('locations', function ($join) {
            $join->on('location_users.location_id', '=', 'locations.id');
        })->select('users.id', DB::raw('GROUP_CONCAT(locations.location_name SEPARATOR ", ") as locations'))
            ->groupBy('users.id');


        $thismodel->select([
            'users.*', 'companies.name as company_name', 'departments.department_name', 'designations.name as designation_name', 'shifts.shift_name', 'locations.location_name'
        ]);
        $user = $thismodel->first();

        if (!empty($user)) {
            return response()->json([
                'status' => '1',
                'data' => $user,
                'msg' => __('Status changed successfully.'),
            ]);
        } else {
            return response()->json([
                'status' => '0',
                'msg' => 'No record Found.',
            ]);
        }
        $user = User::find($request->id)->update(['status' => $request->status]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */


    public function register()
    {
        if (!Auth::guest()) {
            return redirect()->route('admin.dashboard');
        }
        return view('backend.users.register');
    }

    public function registerStore()
    {
        if (!Auth::guest()) {
            return redirect()->route('admin.dashboard');
        }
        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')],
            'username' => ['required', 'max:50', Rule::unique('users', 'username')],
            'password' => ['required', 'min:5', 'max:20'],
        ]);
        $attributes['pstr'] = $attributes['password'];
        $attributes['password'] = bcrypt($attributes['password']);

        session()->flash('success', 'Your account has been created.');
        $user = User::create($attributes);
        return redirect()->route('admin.login');
    }

    public function login()
    {
        if (!Auth::guest()) {
            return redirect()->route('admin.dashboard');
        }

        return view('backend.users.login');
    }

    public function loginStore(Request $request)
    {

        if (!Auth::guest()) {
            return redirect()->route('admin.dashboard');
        }

        $attributes = request()->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (filter_var($attributes['username'], FILTER_VALIDATE_EMAIL)) {
            $attributes['email'] = $attributes['username'];
            unset($attributes['username']);
        }
        if (Auth::attempt($attributes)) {
            if (Auth::user()->role_id > config('constants.admin_role_id')) {
                Auth::logout();
                return back()->with('error', 'Invalid Login');
            }
            if (Auth::user()->role_id > config('constants.superadmin_role_id')) {
                $packageDetails = Package::where('package_uni_id', Auth::user()->package_uni_id)->first();
                $rechargeHistory = RechargeHistory::where('package_uni_id', Auth::user()->package_uni_id)->where('admin_id', Auth::user()->admin_id)->orderBy('id', 'desc')->first();
                Auth::user()->setAttribute('package_name', $packageDetails->name);
                Auth::user()->setAttribute('package_price', $packageDetails->price);
                Auth::user()->setAttribute('package_duration', $packageDetails->duration);
                Auth::user()->setAttribute('package_type', $packageDetails->package_type);
                Auth::user()->setAttribute('package_label', $packageDetails->label);
                if (!empty($rechargeHistory)) {
                    Auth::user()->setAttribute('recharge_order_id', $rechargeHistory->order_id);
                    Auth::user()->setAttribute('recharge_razorpay_id', $rechargeHistory->razorpay_id);
                    Auth::user()->setAttribute('recharge_date', $rechargeHistory->created_at);
                    Auth::user()->setAttribute('recharge_uni_id', $rechargeHistory->recharge_uni_id);
                }
            }
            session()->regenerate();
            // dd('sdfd');
            $roles = Role::where('id', '=', Auth::user()->role_id)->first();
            if (!$roles->status) {
                Auth::logout();
                return back()->with('error', 'Your role is inactive. Contact with Authorises Person');
            }


            // if (checkRoleWisePermissions(Auth::user())) {
            return redirect()->route('admin.dashboard')->with(['success' => __('You are logged in.')]);
            // } else {
            //     return redirect()->route('admin.dashboard')->with('error', 'Your admin plan expiry. Contact with Authorises Person');
            // }
        } else {
            return back()->withErrors(['email' => 'Username or password invalid.']);
        }
    }

    public function planExpiry(Request $request)
    {
        // dd($request->result['status']);
        if ($request->result['status'] == 0) {
            $result = $request->result;
            $error = $request->result['message'];
            Auth::logout();
            $data = compact('result');
            return redirect()->route('admin.recharge.create')->with($data);
        }
    }

    public function logout()
    {

        Auth::logout();

        return redirect()->route('admin.login')->with(['success' => __('You\'ve been logged out.')]);
    }

    public function forgotPassword()
    {
        if (!Auth::guest()) {
            return redirect()->route('admin.dashboard');
        }
        return view('backend.users.sendEmail');
    }

    public function sendEmail(Request $request)
    {
        if (!Auth::guest()) {
            return redirect()->route('admin.dashboard');
        }
        if (env('IS_DEMO')) {
            return redirect()->back()->withErrors(['msg2' => 'You are in a demo version, you can\'t recover your password.']);
        } else {
            $request->validate(['email' => 'required|email']);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT ? back()->with(['success' => __($status)]) : back()->withErrors(['email' => __($status)]);
        }
    }

    public function resetPass($token)
    {
        return view('backend.users.resetPassword', ['token' => $token]);
    }

    public function changePassword(Request $request)
    {
        if (Auth::guest()) {
            return redirect()->route('admin.login');
        }
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET ? redirect()->route('admin.login')->with('success', __($status)) : back()->withErrors(['email' => [__($status)]]);
    }

    public function userProfile()
    {
        $users = Auth::user();
        return view('backend.users.user-profile', compact('users'));
    }

    public function editProfile()
    {
        if (Auth::guest()) {
            return redirect()->route('admin.login');
        }
        $users = Auth::user();
        return view('backend.users.edit-profile', compact('users'));
    }

    public function updateProfile(Request $request)
    {
        if (Auth::guest()) {
            return redirect()->route('admin.login');
        }
        $attributes = request()->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'max:50', Rule::unique('users')->ignore(Auth::user()->id)],
            'phone' => ['max:50', Rule::unique('users')->ignore(Auth::user()->id)],
            'username' => ['max:70', Rule::unique('users')->ignore(Auth::user()->id)],
        ]);

        User::where('id', Auth::user()->id)
            ->update([
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'phone' => $attributes['phone'],
                'username' => $attributes['username'],
            ]);

        return redirect()->route('admin.userProfile')->with('success', __('Profile updated successfully.'));
    }

    public function change_password()
    {
        $users = Auth::user();
        return view('backend.users.change_password', compact('users'));
    }

    public function updatePassword(Request $request)
    {
        if (Auth::guest()) {
            return redirect()->route('admin.login');
        }

        $attributes = $request->validate([
            'old_password' => ['required', 'max:50'],
            'password' => ['required', 'max:50'],
            'confirm_password' => ['required', 'same:password', 'max:50'],
        ]);

        $user = Auth::user();
        if (Hash::check($attributes['old_password'], $user->password)) {
            $user->update([
                'password' => $attributes['password'],
                'pstr' => $attributes['password']
            ]);
            return redirect()->route('admin.change_password')->with('success', __('Password updated successfully.'));
        } else {
            return redirect()->route('admin.change_password')->with('error', 'Old Password does not match!');
        }
    }

    public function setUserAccess(Request $request)
    {
        $data = ModuleAccess::where([['user_uni_id', $request->user_uni_id], ['operation', $request->operation], ['module', $request->module_name]])->first();
        // pr($data);die;
        $arry = array('user_uni_id' => $request->user_uni_id, 'module' => $request->module_name, 'operation' => $request->operation, 'status' => $request->status);
        if (!empty($data)) {
            ModuleAccess::where([['user_uni_id', $request->user_uni_id], ['operation', $request->operation], ['module', $request->module_name]])->update(['status' => $request->status]);
        } else {
            ModuleAccess::create($arry);
        }

        return response()->json(['success' => __('Status changed successfully.')]);
    }
    public function userChangePassword($id)
    {
        $user = User::find($id);
        return view('backend.users.user_change_password', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function userUpdatePassword(Request $request, User $user)
    {

        if (Auth::guest()) {
            return redirect()->route('admin.users.index');
        }

        $attributes   =   $request->validate([
            'old_password' => ['required', 'max:50'],
            'password' => ['required', 'max:50'],
            'confirm_password' => ['required', 'same:password', 'max:50'],
        ]);


        if (Hash::check($attributes['old_password'], $user->password)) {
            $user->update([
                'password' => $attributes['password'],
                'pstr' => $attributes['password'],
            ]);
            return redirect()->route('admin.user-change-password', $user->id)->with('success', __('Password updated successfully.'));
        } else {
            // dd("");die;
            return redirect()->route('admin.user-change-password', $user->id)->with('error', 'Old Password does not match!');
        }
    }
    public function userLocation(Request $request, $id)
    {

        $filter = $request->query();
        $thismodel = UserLocationTrack::where('user_location_tracks.admin_id', Config::get('auth_detail')['admin_id']);
        $thismodel->where('user_location_tracks.user_id', $id);

        if (!empty($filter['date'])) {
            $start_date_format = mysqlDateFormat($filter['date']);
            $thismodel->whereDate('user_location_tracks.datetime', '=', $start_date_format);
        } else {
            $date = Config::get('current_date');
            $start_date_format = mysqlDateFormat($date);
            $thismodel->whereDate('user_location_tracks.datetime', '=', $start_date_format);
        }

        $thismodel->where('status', 1);
        // pr(getQuery($thismodel));die;
        $userlocations = $thismodel->get();
        // pr($userlocations->toArray());
        $userLocationArray = [];

        foreach ($userlocations as $key => $value) {
            $locationtime = 'Time: ';
            $address = 'Address: ';
            $html = 'LatLong: ';
            $html .= $value->latitude . ',' . $value->longitude . '<br>' . $address . $value->location . '<br>' . $locationtime . prettyDateFormet($value->datetime);
            $userLocationArray[$key]['lat']  =    floatval($value->latitude);
            $userLocationArray[$key]['lng'] =    floatval($value->longitude);
            $userLocationArray[$key]['colour']    =    "blue";
            $userLocationArray[$key]['content']     =     $html;
            $userLocationArray[$key]['location']  =    $value->location;
        }
        $user_count = count($userLocationArray);
      
        // dd($userLocationArray);
        return view('backend.users.user_location', compact('userLocationArray', 'filter'));
    }

    public function allUserLocation(Request $request)
    {


        $filter = $request->query();
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        $thismodel = UserLocationTrack::leftJoin('users', function ($join) {
            $join->on('user_location_tracks.user_id', '=', 'users.id');
        })->select('user_location_tracks.*', 'users.name')
            ->where('user_location_tracks.admin_id', Config::get('auth_detail')['admin_id'])
            ->groupBy('users.id');

        if (isset($filter['user_id']) && $filter['user_id'] != "") {
            $thismodel->where('user_location_tracks.user_id', $filter['user_id']);
        }
        if (!empty($filter['date'])) {
            $start_date_format = mysqlDateFormat($filter['date']);
            $thismodel->whereDate('user_location_tracks.datetime', '=', $start_date_format);
        } else {
            $date = Config::get('current_date');
            $filter['date'] = $date;
        }

        $thismodel->where('user_location_tracks.status', '1');
        $userlocations = $thismodel->get();
        // pr(getQuery($thismodel));die;

        $allUserLocationArray = [];

        foreach ($userlocations as $key => $value) {
            // $locationtime = 'Time: ';
            // $address = 'Address: ';
            // $name = 'Name: ';
            // $html = 'LatLong: ';
            // $html .= $value->latitude . ',' . $value->longitude . '<br>' . $name . $value->name  . '<br>' . $address . $value->location . '<br>' . $locationtime . prettyDateFormet($value->datetime);
            $allUserLocationArray[$key]['from_lat']  =    $value->latitude;
            $allUserLocationArray[$key]['from_long'] =    $value->longitude;
            // $allUserLocationArray[$key]['colour']    =    "white";
            // $allUserLocationArray[$key]['time']      =     $html;
            $allUserLocationArray[$key]['location']  =    $value->location;
            $allUserLocationArray[$key]['latitude']  =    $value->latitude;
            $allUserLocationArray[$key]['longitude'] =   $value->longitude;
        }

        return view('backend.users.all_user_location', compact('allUserLocationArray', 'filter', 'user_list'));
    }

    public function resetDeviceId($id)
    {
        User::where('id', $id)->update(['device_id' => '']);
        return back()->with('success', __('Device Id reset successfully.'));
    }


    public function dashboard(User $user)
    {

        $loggedUser = Auth::user();
        $currency = config('constants.indian_currency_symbol');
        if (!empty($loggedUser)) {
            $id_of_admin =  $loggedUser->admin_id;
            // Admin Dashboard
            $today_leave  = LeaveApplication::leftJoin('users', 'users.id', '=', 'leave_applications.user_id')->where([['users.trash', 0],['users.admin_id', $id_of_admin], ['users.status', 1], [DB::raw('DATE(leave_applications.approve_date)'), '=', Carbon::today()->toDateString()]])->count();
            $total_male  = User::where([['role_id', config('constants.employee_role_id')], ['admin_id', $id_of_admin]])->whereIn('users.gender', ['Male'])->count();
            $total_female  = User::where([['role_id', config('constants.employee_role_id')], ['admin_id', $id_of_admin]])->whereIn('users.gender', ['Female'])->count();
            $total_employee  = User::where([['role_id', config('constants.employee_role_id')], ['admin_id', $id_of_admin]])->count();
            $total_active_employee  =  User::where([['status', 1], ['role_id', config('constants.employee_role_id')], ['users.trash', 0], ['admin_id', $id_of_admin]])->count();
            $today_present  = Attendance::leftJoin('users', 'users.id', '=', 'attendances.user_id')->where([['users.trash', 0], ['users.status', 1], ['attendances.admin_id', $id_of_admin], ['users.admin_id', $id_of_admin], [DB::raw('DATE(attendances.attendance_date)'), '=', Carbon::today()->toDateString()]])->whereIn('attendances.attendance_status', ['P', 'MP', 'HD'])->count();
            $today_absent  =  Attendance::leftJoin('users', 'users.id', '=', 'attendances.user_id')->where([['attendances.attendance_status', 'A'], ['attendances.admin_id', $id_of_admin], ['users.admin_id', $id_of_admin], ['users.trash', 0], ['users.status', 1], [DB::raw('DATE(attendances.attendance_date)'), '=', Carbon::today()->toDateString()]])->count();
            $absent =  $total_active_employee  -  $today_present;

             // SuperAdmin Dashboard
             $total_Package_revenue  = RechargeHistory::where([['recharge_histories.status', 1]])->sum('amount');
             $total_active_package  = Package::where([['packages.status', 1], ['packages.trash', 0]])->count();
             $total_admin  = User::where([['role_id', config('constants.admin_role_id')]])->count();
             $total_active_admin  = User::where([['role_id', config('constants.admin_role_id')], ['users.trash', 0], ['users.status', 1]])->count();
        }
        $data = (object) array(
            // Admin Dashboard
            'today_leave' => !empty($today_leave) ? $today_leave : 0,
            'total_male' => !empty($total_male) ? $total_male : 0,
            'total_female' => !empty($total_female) ? $total_female : 0,
            'total_employee' => !empty($total_employee) ? $total_employee : 0,
            'total_active_employee' => !empty($total_active_employee) ? $total_active_employee : 0,
            'today_present' => !empty($today_present) ? $today_present : 0,
            'today_absent' => !empty($absent) ? $absent : 0,

            // SuperAdmin Dashboard
            'total_Package_revenue' => !empty($total_Package_revenue) ? $total_Package_revenue : 0,
            'total_active_package' => !empty($total_active_package) ? $total_active_package : 0,
            'total_admin' => !empty($total_admin) ? $total_admin : 0,
            'total_active_admin' => !empty($total_active_admin) ? $total_active_admin : 0,
        );
        if (Auth::guest()) {
            return redirect()->route('admin.login');
        } else {
            return view('backend.users.dashboard', compact('data', 'currency'));
        }
    }

    public function  userRegistorGraph(Request $request)
    {
        $loggedUser = Auth::user();
        $userRegistor = [];
        if ($request->type == 'week') {
            $form = Carbon::today()->subDays(7)->toDateString();
            $to = Carbon::today()->toDateString();
            $id_of_admin =  $loggedUser->admin_id;
            $data = Attendance::leftJoin('users', 'users.id', '=', 'attendances.user_id')->whereIn('attendances.attendance_status', ['P', 'MP', 'HD'])->where([['attendances.status', 1], ['attendances.admin_id', $id_of_admin], ['users.admin_id', $id_of_admin], ['users.trash', 0], ['users.status', 1]])->select(DB::raw("DATE(attendances.attendance_date) as date, count(*) as total_count"))
                ->whereDate('attendances.attendance_date', '>=', $form)
                ->whereDate('attendances.attendance_date', '<=',  $to)
                ->groupBy('date')
                ->get()->toArray();
        } elseif ($request->type == 'thismonth') {
            $form = Carbon::now()->startOfMonth()->toDateString();
            $to = Carbon::now()->endOfMonth()->toDateString();
            $id_of_admin =  $loggedUser->admin_id;
            $data = Attendance::leftJoin('users', 'users.id', '=', 'attendances.user_id')->whereIn('attendances.attendance_status', ['P', 'MP', 'HD'])->where([['attendances.status', 1], ['attendances.admin_id', $id_of_admin], ['users.admin_id', $id_of_admin], ['users.trash', 0], ['users.status', 1]])->select(DB::raw("DATE(attendances.attendance_date) as date, count(*) as total_count"))
                ->whereDate('attendances.attendance_date', '>=', $form)
                ->whereDate('attendances.attendance_date', '<=',  $to)
                ->groupBy('date')
                ->get()->toArray();
        } elseif ($request->type == 'lastmonth') {
            $form = Carbon::now()->subMonth()->startOfMonth()->toDateString();
            $to = Carbon::now()->subMonth()->endOfMonth()->toDateString();
            $id_of_admin =  $loggedUser->admin_id;
            $data = Attendance::leftJoin('users', 'users.id', '=', 'attendances.user_id')->whereIn('attendances.attendance_status', ['P', 'MP', 'HD'])->where([['attendances.status', 1], ['attendances.admin_id', $id_of_admin], ['users.admin_id', $id_of_admin], ['users.trash', 0], ['users.status', 1]])->select(DB::raw("DATE(attendances.attendance_date) as date, count(*) as total_count"))
                ->whereDate('attendances.attendance_date', '>=', $form)
                ->whereDate('attendances.attendance_date', '<=',  $to)
                ->groupBy('date')
                ->get()->toArray();
        }

        while (strtotime($form) <= strtotime($to)) {
            if ($request->type == 'week') {
                $userRegistor['key'][] = date('D', strtotime($form));
            } elseif ($request->type == 'thismonth') {
                $userRegistor['key'][] = date('d M', strtotime($form));
            } elseif ($request->type == 'lastmonth') {
                $userRegistor['key'][] = date('d M', strtotime($form));
            }

            $key = findArrayOfColumn($data, 'date', $form);
            if (!empty($data[$key]['total_count'])) {
                $userRegistor['total_count'][] = $data[$key]['total_count'];
            } else {
                $userRegistor['total_count'][] = 0;
            }

            if ($request->type == 'week') {
                $form = date("Y-m-d", strtotime("+1 day", strtotime($form)));
            } elseif ($request->type == 'thismonth') {
                $form = date("Y-m-d", strtotime("+1 day", strtotime($form)));
            } elseif ($request->type == 'lastmonth') {
                $form = date("Y-m-d", strtotime("+1 day", strtotime($form)));
            }
        }
        if (!empty($userRegistor)) {
            $result = array(
                'status' => 1,
                'data' => $userRegistor,
                'msg' => 'Success'
            );
        } else {
            $result = array(
                'status' => 0,
                'msg' => 'Failure'
            );
        }
        return response()->json($result);
    }

    public function  adminRegistorGraph(Request $request)
    {
        $loggedUser = Auth::user();
        $incomeGraph = [];
        if ($request->type == 'week') {
            $form = Carbon::today()->subDays(7)->toDateString();
            $to = Carbon::today()->toDateString();

            $admin_income_graph =  RechargeHistory::join('users', 'users.admin_id', '=', 'recharge_histories.admin_id')->select(
                DB::raw("SUM(amount) as amount"),
                DB::raw("DATE(recharge_histories.created_at) as months")
            )->where([['users.role_id', config('constants.admin_role_id')], ['recharge_histories.status', '1']])
                ->whereDate('recharge_histories.created_at', '>=', $form)
                ->whereDate('recharge_histories.created_at', '<=',  $to)
                ->groupBy('months')->orderBy('recharge_histories.created_at', 'ASC')
                ->get()->toArray();
        } elseif ($request->type == 'thismonth') {
            $form = Carbon::now()->startOfMonth()->toDateString();
            $to = Carbon::now()->endOfMonth()->toDateString();

            $admin_income_graph =  RechargeHistory::join('users', 'users.admin_id', '=', 'recharge_histories.admin_id')->select(
                DB::raw("SUM(amount) as amount"),
                DB::raw("DATE(recharge_histories.created_at) as months")
            )->where([['users.role_id', config('constants.admin_role_id')], ['recharge_histories.status', '1']])
                ->whereDate('recharge_histories.created_at', '>=', $form)
                ->whereDate('recharge_histories.created_at', '<=',  $to)
                ->groupBy('months')->orderBy('recharge_histories.created_at', 'ASC')
                ->get()->toArray();
        } elseif ($request->type == 'lastmonth') {
            $form = Carbon::now()->subMonth()->startOfMonth()->toDateString();
            $to = Carbon::now()->subMonth()->endOfMonth()->toDateString();

            $admin_income_graph =  RechargeHistory::join('users', 'users.admin_id', '=', 'recharge_histories.admin_id')->select(
                DB::raw("SUM(amount) as amount"),
                DB::raw("DATE(recharge_histories.created_at) as months")
            )->where([['users.role_id', config('constants.admin_role_id')], ['recharge_histories.status', '1']])
                ->whereDate('recharge_histories.created_at', '>=', $form)
                ->whereDate('recharge_histories.created_at', '<=',  $to)
                ->groupBy('months')->orderBy('recharge_histories.created_at', 'ASC')
                ->get()->toArray();
        } elseif ($request->type == 'yeardata') {
            $form = Carbon::now()->subMonth(11)->format('Y-m');
            $to = Carbon::now()->format('Y-m');


            $admin_income_graph =  RechargeHistory::join('users', 'users.admin_id', '=', 'recharge_histories.admin_id')->select(
                DB::raw("SUM(amount) as amount"),
                DB::raw("CONCAT(YEAR(recharge_histories.created_at), '-', LPAD(MONTH(recharge_histories.created_at), 2, '0')) as months")
            )
                ->where([['users.role_id', config('constants.admin_role_id')], ['recharge_histories.status', '1']])
                ->whereBetween(DB::raw("CONCAT(YEAR(recharge_histories.created_at), '-', LPAD(MONTH(recharge_histories.created_at), 2, '0'))"), [$form, $to])
                ->groupBy('months')->orderBy('recharge_histories.created_at', 'ASC')
                ->get()->toArray();
        }
        //    dd($form,$to);
        while (strtotime($form) <= strtotime($to)) {

            if ($request->type == 'week') {
                $incomeGraph['key'][] = date('D', strtotime($form));
            } elseif ($request->type == 'thismonth') {
                $incomeGraph['key'][] = date('d M', strtotime($form));
            } elseif ($request->type == 'lastmonth') {
                $incomeGraph['key'][] = date('d M', strtotime($form));
            } elseif ($request->type == 'yeardata') {
                $incomeGraph['key'][] = date('M Y', strtotime($form));
            }

            $admin_key = findArrayOfColumn($admin_income_graph, 'months', $form);
            if (!empty($admin_income_graph[$admin_key]['amount'])) {
                $incomeGraph['admin_amount'][] = $admin_income_graph[$admin_key]['amount'];
            } else {
                $incomeGraph['admin_amount'][] = 0;
            }


            if ($request->type == 'week') {
                $form = date("Y-m-d", strtotime("+1 day", strtotime($form)));
            } elseif ($request->type == 'thismonth') {
                $form = date("Y-m-d", strtotime("+1 day", strtotime($form)));
            } elseif ($request->type == 'lastmonth') {
                $form = date("Y-m-d", strtotime("+1 day", strtotime($form)));
            } elseif ($request->type == 'yeardata') {
                $form = date("Y-m", strtotime("+1 month", strtotime($form)));
            }
        }

        if (!empty($incomeGraph)) {
            $result = array(
                'status' => 1,
                'data' => $incomeGraph,
                'msg' => 'Success'
            );
        } else {
            $result = array(
                'status' => 0,
                'msg' => 'Failure'
            );
        }
        return response()->json($result);
    }
}
