<?php

namespace App\Http\Controllers\Backend;

USE PDF;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\SalaryType; 


class SalaryTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // echo 'te';die;
        $limit =  config('constants.default_page_limit');
        $filter = $request->query();

        $thismodel = SalaryType::sortable(['created_at' => 'DESC']);
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('salary_type', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('salary_types.status', $filter['status']);
        }

        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Salary Type Name", "Status", "Created_at", "Updated_at",
            ];
            $thismodel->select([
                'salary_type', 'salary_types.status', 'salary_types.created_at', 'salary_types.updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Salary types List'
            ];
            
            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'salary types.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Salary Types List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,
                ];

                $file = 'Salary Types.pdf';

                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        // dd(getQueryWithBindings($thismodel));
        $salary_types = $thismodel->paginate($limit);
        return view('backend.salary-types.index', compact('salary_types', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('backend.salary-types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $attributes = request()->validate([
            'salary_type' => ['required'],
        ]);

        $attributes['status'] = 1;
        SalaryType::create($attributes);
        return redirect()->route('admin.payroll.salary-types.index')
            ->with('success', __('Salary Type created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SalaryType $salary_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SalaryType $salary_type)
    {
        return view('backend.salary-types.edit', compact('salary_type'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalaryType $salary_type)
    {
        // dd($salary_type);
        $attributes = request()->validate([
            'salary_type' => ['required'],
        ]);

        $salary_type->update($attributes);
        return redirect()->route('admin.payroll.salary-types.index')->with('success', __('Salary Type Detail Updated successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalaryType $salary_type)
    {
        $salary_type->delete();
        return redirect()->route('admin.payroll.salary-types.index')
            ->with('success', __('Salary Type deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        SalaryType::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
 