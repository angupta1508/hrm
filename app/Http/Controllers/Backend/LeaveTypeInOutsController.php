<?php

namespace App\Http\Controllers\Backend;

use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Models\LeaveTypeInOut;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
// use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser;



class LeaveTypeInOutsController extends Controller
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

        $thismodel = LeaveTypeInOut::sortable(['created_at' => 'DESC']);
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('leave_type_in_out.name', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('leave_type_in_out.status', $filter['status']);
        }
        // dd(getQuery($thismodel));
        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Leave Type In Out Name", "Status", "Created_at", "Updated_at",
            ];
            $thismodel->select([
                'name', 'status', 'created_at', 'updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Leave type in outs List'
            ];  

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings, $header), 'leave_type_in_outs.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0
                ) {
                    $tabel_keys =
                        array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Leave Type in Outs List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,    
                ];
                $file=  'Leave Type in Outs.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        // dd(getQueryWithBindings($thismodel));
        $leave_type = $thismodel->paginate($limit);
        return view('backend.leave-type-in-outs.index', compact('leave_type', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('backend.leave-type-in-outs.create');
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
        LeaveTypeInOut::create($attributes);
        return redirect()->route('admin.leave-type-in-outs.index')
            ->with('success', __('Leave Type In Out created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(LeaveTypeInOut $leave_type_in_out)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(LeaveTypeInOut $leave_type_in_out)
    {
        return view('backend.leave-type-in-outs.edit', compact('leave_type_in_out'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaveTypeInOut $leave_type_in_out)
    {
        // dd($leave_type_in_out);
        $attributes = request()->validate([
            'name' => ['required'],
        ]);


        $leave_type_in_out->update($attributes);
        return redirect()->route('admin.leave-type-in-outs.index')->with('success', __('Leave Type In Out Detail Updated successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaveTypeInOut $leave_type_in_out)
    {
        $leave_type_in_out->delete();
        return redirect()->route('admin.leave-type-in-outs.index')
            ->with('success', __('Leave Type In Out deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        LeaveTypeInOut::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
