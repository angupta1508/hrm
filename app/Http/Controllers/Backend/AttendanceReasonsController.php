<?php

namespace App\Http\Controllers\Backend;

use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceReason;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;
// use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser;



class AttendanceReasonsController extends Controller
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

        $thismodel = AttendanceReason::sortable(['created_at' => 'DESC']);
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('attendance_reasons.name', 'LIKE', '%' . $keyword . '%');
            });
        }

       if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('status', $filter['status']);
        }

        $thismodel->where('attendance_reasons.admin_id' , $loggedUser->admin_id);

        // dd(getQuery($thismodel));
        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Attendance Reason Name","Status", "Created_at", "Updated_at",
            ];
            $thismodel->select([
                'attendance_reasons.name', 'attendance_reasons.status', 'attendance_reasons.created_at', 'attendance_reasons.updated_at',
            ]);
            $records = $thismodel->get();
            
            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Attendance Reasons List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'attendance_reasons.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Attendance Reason List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,    
                ];

                $file = 'Attendance Reason.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        // dd(getQueryWithBindings($thismodel));
        $attendance_reasons = $thismodel->paginate($limit);
        return view('backend.attendance-reasons.index', compact('attendance_reasons', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('backend.attendance-reasons.create');
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
        $loggedUser = Auth::user();

        $attributes = request()->validate([
            'name' => ['required'],
        ]);

        $attributes['admin_id'] = $loggedUser->admin_id;

        $attributes['status'] = 1;
        AttendanceReason::create($attributes);
        return redirect()->route('admin.attendence.attendance-reasons.index')
            ->with('success', __('Attendance Reason created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AttendanceReason $attendance_reason)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AttendanceReason $attendance_reason)
    {
        return view('backend.attendance-reasons.edit', compact('attendance_reason'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AttendanceReason $attendance_reason)
    {
        // dd($attendance_reason);
        $attributes = request()->validate([
            'name' => ['required'],
        ]);


        $attendance_reason->update($attributes);
        return redirect()->route('admin.attendence.attendance-reasons.index')->with('success', __('Attendance Reason Detail Updated successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttendanceReason $attendance_reason)
    {
        $attendance_reason->delete();
        return redirect()->route('admin.attendence.attendance-reasons.index')
            ->with('success', __('Attendance Reason deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        AttendanceReason::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
