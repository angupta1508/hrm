<?php


namespace App\Http\Controllers\Backend;

use PDF;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser; 
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PerformanceType;  


class PerformanceTypesController extends Controller
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

        $thismodel = PerformanceType::sortable(['created_at' => 'DESC']);
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('performance_types.name', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('performance_types.status', $filter['status']);
        }

        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Performance Type Name", "Status", "Created_at", "Updated_at",
            ];
            $thismodel->select([
                'performance_types.name', 'performance_types.status', 'performance_types.created_at', 'performance_types.updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Performance types List'
            ];
            
            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'performance types.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Performance Types List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                     'header' => $header,            
                    'records' => $records,
                    
                ];
                $file=  'performance_types.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }
 
        // dd(getQueryWithBindings($thismodel));
        $performance_types = $thismodel->paginate($limit);
        return view('backend.performance-types.index', compact('performance_types', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('backend.performance-types.create');
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
            'name' => ['required'],
        ]);

        $attributes['status'] = 1;
        
        PerformanceType::create($attributes);
        return redirect()->route('admin.performance.performance-types.index')
            ->with('success', __('Performance Type created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PerformanceType $performance_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PerformanceType $performance_type)
    {
        return view('backend.performance-types.edit', compact('performance_type'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PerformanceType $performance_type)
    {
        // dd($performance_type);
        $attributes = request()->validate([
            'name' => ['required'],
        ]); 

        $performance_type->update($attributes);
        return redirect()->route('admin.performance.performance-types.index')->with('success', __('Performance Type Detail Updated successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PerformanceType $performance_type)
    {
        $performance_type->delete();
        return redirect()->route('admin.performance.performance-types.index')
            ->with('success', __('Performance Type deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        PerformanceType::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
 
}
