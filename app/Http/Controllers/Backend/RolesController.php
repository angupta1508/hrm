<?php

namespace App\Http\Controllers\Backend;

use PDF;
use App\Models\Role;
use App\Models\Module;
// use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser;
use App\Models\AdminModules;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\AdminModulePermission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Cviebrock\EloquentSluggable\Services\SlugService;

class RolesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $routeData = getRoleTypeFromRoute($request->route());
        // dd($routeData);
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }

        $loggedUser = Auth::user();
        $limit = config('constants.default_page_limit');
        $filter = $request->query();

        $thismodel = Role::sortable(['id' => 'asc']);

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('status', $filter['status']);
        }

        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('roles.name', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (!empty($routeId)) {
            $thismodel->where('role_type', '=', $routeId);
        } else {
            $thismodel->where('role_type', '=', 'User');
        }

        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Name", "Type", "Status",
            ];
            $thismodel->select([
                'roles.name', 'roles.role_type', 'roles.status',
            ]);
            $records = $thismodel->get();

            $file = $routeSlug;
            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  $routeSlug.' List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), $routeSlug.'.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Roles List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,
                ];

                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($routeSlug.'.pdf');
            }
        }

        $roles = $thismodel->paginate($limit);

        return view('backend.roles.index', compact('roles', 'filter', 'routeSlug', 'routeId'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $routeData = getRoleTypeFromRoute($request->route());
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }

        return view('backend.roles.create', compact('routeSlug', 'routeId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $routeData = getRoleTypeFromRoute($request->route());
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }

        $attributes = request()->validate([
            'name' => ['required', 'max:50', Rule::unique('roles', 'name')],
            'role_type' => ['nullable'],
            'status' => ['nullable', 'numeric'],
        ]);
        // $attributes['admin_id'] = Auth::user()->user_uni_id;


        $attributes["slug"] = SlugService::createSlug(Role::class, 'slug', $attributes['name']);
        // pr($attributes);die;
        Role::create($attributes);
 
        $routeRedirect = 'admin.roles.index';
        if (!empty($routeSlug)) {
            $routeRedirect = 'admin.' . $routeSlug . '.index';
        }

        return redirect()->route($routeRedirect)
            ->with('success', __('Role created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $role = Role::find($id);
        $routeData = getRoleTypeFromRoute($request->route());
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }

        return view('backend.roles.show', compact('role', 'routeSlug', 'routeId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $role = Role::find($id);
        $routeData = getRoleTypeFromRoute($request->route());
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }

        return view('backend.roles.edit', compact('role', 'routeSlug', 'routeId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        $routeData = getRoleTypeFromRoute($request->route());
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }

        $attributes = request()->validate([
            'name' => ['required', 'max:50', Rule::unique('roles', 'name')->ignore($role->id)],
            'role_type' => ['nullable'],
            'status' => ['nullable', 'numeric'],
        ]);

        if (!empty($attributes['name']) && $attributes['name'] != $role->name) {
            $attributes["slug"] = SlugService::createSlug(Role::class, 'slug', $attributes['name']);
        }

        $role->update($attributes);

        $routeRedirect = 'admin.roles.index';
        if (!empty($routeSlug)) {
            $routeRedirect = 'admin.' . $routeSlug . '.index';
        }

        return redirect()->route($routeRedirect)
            ->with('success', __('Role updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $role = Role::find($id);
        $routeData = getRoleTypeFromRoute($request->route());
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }

        $role->delete();

        $routeRedirect = 'admin.roles.index';
        if (!empty($routeSlug)) {
            $routeRedirect = 'admin.' . $routeSlug . '.index';
        }

        return redirect()->route($routeRedirect)
            ->with('success', __('Role deleted successfully'));
    }

    public function changeStatus(Request $request)
    {
        Role::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }

    public function permissions(Request $request, $id)
    {
        $role = Role::find($id);
        $routeData = getRoleTypeFromRoute($request->route());
        $routeSlug = $routeId = '';
        if (!empty($routeData['routeId'])) {
            $routeId = $routeData['routeId'];
            $routeSlug = $routeData['routeSlug'];
        }

        $modules = AdminModules::get()->toArray();
        foreach ($modules as $key => $module) {
            $oprs = explode("|", $module['operation']);
            $operations = [];
            foreach($oprs as $k => $opr){
                $operations[$k]['name'] = $opr;
                $moduleAccess = AdminModulePermission::where('role_id', $role->id)->where('module_id', $module['id'])->where('operation', $opr)->first();
                if (!empty($moduleAccess->status)) {
                    $operations[$k]['status'] = $moduleAccess->status;
                } else {
                    $operations[$k]['status'] = 0;
                }

            }

            $modules[$key]['operations'] = $operations;

        }
        // pr($modules);die;
        return view('backend.roles.permission', compact('modules', 'role', 'routeSlug', 'routeId'));
    }

    public function permissionStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'operation' => ['required'],
            'module_id' => ['required'],
            'role_id'   => ['required'],
            'status'    => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 0,
                "errors" => $validator->errors(),
                "message" => 'Something went wrong',
                "msg" => implode('\n', $validator->messages()->all()),
            ]);
        }

        $attributes = $request->all();
        $access = AdminModulePermission::where('role_id', $request->role_id)->where('module_id', $request->module_id)->where('operation', $request->operation)->first();
        if (!empty($access)) {
            $access->update($attributes);
        } else {
            AdminModulePermission::create($attributes);
        }

        return response()->json(["status" => 1, 'msg' => __('Status changed successfully.')]);
    }
}
