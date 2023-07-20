<?php

namespace App\Http\Controllers\Backend;

use App\Models\UserPolicy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

class PolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit =  config('constants.default_page_limit');
        $filter = $request->query();

        $thismodel = UserPolicy::sortable(['created_at' => 'DESC']);
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('user_policies.policy_name', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('user_policies.status', $filter['status']);
        }
        $thismodel->where('admin_id',Config::get('auth_detail')['admin_id']);
        $userpolicy = $thismodel->paginate($limit);
        return view('backend.userpolicy.index', compact('userpolicy', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.userpolicy.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = request()->validate([
            'policy_name'                       => ['required'],
            'eneble_working_hours_relaxation'   => ['nullable'],
            'fullday_relaxation'                => ['nullable'],
            'halfday_relaxation'                => ['nullable'],
            'eneble_late_coming'                => ['nullable'],
            'late_coming_relaxation'            => ['nullable'],
            'late_coming_deduction_repeate'     => ['nullable'],
            'eneble_early_going'                => ['nullable'],
            'early_going_relaxation'            => ['nullable'],
            'early_going_deduction_repeate'     => ['nullable'],
            'overtime_apply_time'               => ['nullable'],
            'eneble_overtime_working_day'       => ['nullable'],
            'eneble_sandwich'                   => ['nullable'],
            'eneble_holiday_working_hours'      => ['nullable'],
            'holiday_working_hours'             => ['nullable'],
            'eneble_weekoff_working_hours'      => ['nullable'],
            'weekday_for_weekend'               => ['nullable'],
            'eneble_weekday_for_weekend'        => ['nullable'],
            'weekoff_working_hours'             => ['nullable'],
            'autual_month_day'                  => ['nullable'],
            'cl'                                => ['nullable'],
            'pl'                                => ['nullable'],
            'medical_leave'                     => ['nullable'],
            'paternity_leave'                   => ['nullable'],
            'maternity_leave'                   => ['nullable'],
            'every_month_paid_leave'            => ['nullable'],
            'carry_forward_month'               => ['nullable'],
            'carry_forward_year'                => ['nullable'],
            'carry_forward_till_month'          => ['nullable'],
        ]);
        
        $attributes['status'] = 1;
        $attributes['admin_id'] = Config::get('auth_detail')['admin_id'];
        
       
        UserPolicy::create($attributes);
        return redirect()->route('admin.administration.user-policy.index')
            ->with('success', __('User Policy created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(UserPolicy $userPolicy)
    {
        return view('backend.userpolicy.edit', compact('userPolicy'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserPolicy $userPolicy)
    {
        $attributes = request()->validate([
            'policy_name'                       => ['required'],
            'eneble_working_hours_relaxation'   => ['nullable'],
            'fullday_relaxation'                => ['nullable'],
            'halfday_relaxation'                => ['nullable'],
            'eneble_late_coming'                => ['nullable'],
            'late_coming_relaxation'            => ['nullable'],
            'late_coming_deduction_repeate'     => ['nullable'],
            'eneble_early_going'                => ['nullable'],
            'early_going_relaxation'            => ['nullable'],
            'early_going_deduction_repeate'     => ['nullable'],
            'overtime_apply_time'               => ['nullable'],
            'eneble_overtime_working_day'       => ['nullable'],
            'eneble_holiday_working_hours'      => ['nullable'],
            'holiday_working_hours'             => ['nullable'],
            'eneble_weekoff_working_hours'      => ['nullable'],
            'weekday_for_weekend'               => ['nullable'],
            'eneble_weekday_for_weekend'        => ['nullable'],
            'autual_month_day'                  => ['nullable'],
            'weekoff_working_hours'             => ['nullable'],
            'eneble_sandwich'                   => ['nullable'],
            'cl'                                => ['nullable'],
            'pl'                                => ['nullable'],
            'medical_leave'                     => ['nullable'],
            'paternity_leave'                   => ['nullable'],
            'maternity_leave'                   => ['nullable'],
            'every_month_paid_leave'            => ['nullable'],
            'carry_forward_month'               => ['nullable'],
            'carry_forward_year'                => ['nullable'],
            'carry_forward_till_month'          => ['nullable'],
        ]);

        if(empty($attributes['eneble_working_hours_relaxation']))
        {
            $attributes['eneble_working_hours_relaxation'] = 0;

        }

        if(empty($attributes['eneble_late_coming']))
        {
            $attributes['eneble_late_coming'] = 0;

        }
        
        if(empty($attributes['eneble_early_going']))
        {
            $attributes['eneble_early_going'] = 0;

        }
     
        
        if(empty($attributes['eneble_overtime_working_day']))
        {
            $attributes['eneble_overtime_working_day'] = 0;

        }
        
        if(empty($attributes['eneble_sandwich']))
        {
            $attributes['eneble_sandwich'] = 0;
        }
        
        if(empty($attributes['eneble_holiday_working_hours']))
        {
            $attributes['eneble_holiday_working_hours'] = 0;
        }
        
        if(empty($attributes['eneble_weekoff_working_hours']))
        {
            $attributes['eneble_weekoff_working_hours'] = 0;
        }

        if(empty($attributes['eneble_weekday_for_weekend']))
        {
            $attributes['eneble_weekday_for_weekend'] = 0;
        }
        
        if(empty($attributes['carry_forward_month']))
        {
            $attributes['carry_forward_month'] = 0;
        }
        
        if(empty($attributes['carry_forward_year']))
        {
            $attributes['carry_forward_year'] = 0;
        }

        $userPolicy->update($attributes);
        return redirect()->route('admin.administration.user-policy.index')->with('success', __('User Policy Updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserPolicy $userPolicy)
    {
        $userPolicy->delete();
        return redirect()->route('admin.administration.user-policy.index')
            ->with('success', __('User Policy deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        UserPolicy::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
