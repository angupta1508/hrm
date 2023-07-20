<?php

namespace App\Http\Controllers\Backend;

use PDF;
// use Barryvdh\DomPDF\PDF;
use App\Models\Department;
use App\Exports\ExportUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;

class DepartmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->query();
        $limit =  config('constants.default_page_limit');

        $thismodel = Department::sortable(['created_at' => 'DESC']);
        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('status', $filter['status']);
        }
        $thismodel->where('admin_id', Config::get('auth_detail')['admin_id']);
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('departments.department_name', 'LIKE', '%' . $keyword . '%');
            });
        }
        // dd(getQueryWithBindings($thismodel));

        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Department Name", "Status", "Created", "Updated",
            ];
            $thismodel->select([
                'departments.department_name',
                'departments.status', 'departments.created_at', 'departments.updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Departments List'
            ];
            
            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'departments.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Banker List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,    
                ];
                $file = 'departments.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        $departments = $thismodel->paginate($limit);
        return view('backend.departments.index', compact('departments', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $LoggedUser = Auth::user();

        $attributes = request()->validate([
            'department_name' => ['required'],
            // 'admin_charges' => ['required'],
        ]);

        $attributes['admin_id'] = $LoggedUser->id;


        Department::create($attributes);
        return redirect()->route('admin.administration.departments.index')
            ->with('success', __('Department created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        return view('backend.departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $LoggedUser = Auth::user();

        $attributes = request()->validate([
            'department_name' => ['required'],
            // 'admin_charges' => ['required'],
        ]);

        $attributes['admin_id'] = $LoggedUser->id;


        $department->update($attributes);
        return redirect()->route('admin.administration.departments.index')
            ->with('success', __('Department created successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('admin.administration.departments.index')
            ->with('success', __('Department deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        Department::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
