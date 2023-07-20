<?php

namespace App\Http\Controllers\Backend;


use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DutyType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;
// use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser;


class DutyTypesController extends Controller
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

        $thismodel = DutyType::sortable(['created_at' => 'DESC']);
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('duty_type', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('duty_types.status', $filter['status']);
        }
        // dd(getQuery($thismodel));
        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Duty Type Name","Status", "Created_at", "Updated_at",
            ];
            $thismodel->select([
                'duty_type', 'Status', 'created_at', 'updated_at',
            ]);
            $records = $thismodel->get();
            
            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Duty Types List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'duty_types.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'duty types List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,    
                ];
                $file=  'duty_types.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        // dd(getQueryWithBindings($thismodel));
        $duty_types = $thismodel->paginate($limit);
        return view('backend.duty-types.index', compact('duty_types', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('backend.duty-types.create');
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
            'duty_type' => ['required'],
        ]);

        $attributes['status'] = 1;
        DutyType::create($attributes);
        return redirect()->route('admin.duty-types.index')
            ->with('success', __('Duty Type created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DutyType $duty_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(DutyType $duty_type)
    {
        return view('backend.duty-types.edit', compact('duty_type'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DutyType $duty_type)
    {
        // dd($duty_type);
        $attributes = request()->validate([
            'duty_type' => ['required'],
        ]);


        $duty_type->update($attributes);
        return redirect()->route('admin.duty-types.index')->with('success', __('Duty Type Detail Updated successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DutyType $duty_type)
    {
        $duty_type->delete();
        return redirect()->route('admin.duty-types.index')
            ->with('success', __('Duty Type deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        DutyType::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
