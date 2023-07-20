<?php

namespace App\Http\Controllers\Backend;

use PDF;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveType;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
// use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser;



class LeaveTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $loggedUser = Auth::user();

        $limit =  config('constants.default_page_limit');
        $filter = $request->query();

        $thismodel = LeaveType::sortable(['created_at' => 'DESC']);
        if (!empty($filter['leave_type'])) {
            $keyword = $filter['leave_type'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('leave_type', 'LIKE', '%' . $keyword . '%');
            });
        }
      

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('leave_types.status', $filter['status']);
        }

        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Leave Type Name","Status", "Created_at", 
            ];
            $thismodel->select([
                'leave_type','leave_types.status', 'leave_types.created_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Leave types List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings, $header), 'leave_types.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Leave Type List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,    
                ];

                $file = 'leave_types.pdf';
                $pdf = PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download( $file);
            }
        }

        // dd(getQueryWithBindings($thismodel));
        $leave_types = $thismodel->paginate($limit);
        return view('backend.leave-types.index', compact('leave_types', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('backend.leave-types.create');
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
            'leave_type' => ['required'],
        ]);
        $attributes['status'] = 1;
        // dd($attributes);
        LeaveType::create($attributes);
        return redirect()->route('admin.leave.leave-types.index')
            ->with('success', __('Leave Type created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(LeaveType $leave_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(LeaveType $leave_type)
    {
        return view('backend.leave-types.edit', compact('leave_type' ));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LeaveType $leave_type)
    {
        // dd($request);
        $attributes = request()->validate([
            'leave_type' => ['required'],
        ]);
        $leave_type->update($attributes);
        return redirect()->route('admin.leave.leave-types.index')->with('success', __('Leave Type Detail Updated successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(LeaveType $leave_type)
    {
        $leave_type->delete();
        return redirect()->route('admin.leave.leave-types.index')
            ->with('success', __('Leave Type deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        LeaveType::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
   
}
