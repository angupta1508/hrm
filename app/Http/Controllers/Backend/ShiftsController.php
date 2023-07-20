<?php

namespace App\Http\Controllers\Backend;

use PDF;
use App\Models\User;
use App\Models\Shift; 
// use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Config;

class ShiftsController extends Controller
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

        $thismodel = Shift::sortable(['created_at' => 'DESC']);
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('shifts.shift_name', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (isset($filter['shift_type']) && $filter['shift_type'] != "") {
            $thismodel->where('shifts.shift_type', $filter['shift_type']);
        }

        if (!empty($filter['from_time'])) {
            $keyword = $filter['from_time'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('shifts.from_time', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (!empty($filter['to_time'])) {
            $keyword = $filter['to_time'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('shifts.to_time', 'LIKE', '%' . $keyword . '%');
            });
        }

        $thismodel->where('admin_id', Config::get('auth_detail')['admin_id']);
        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('shifts.status', $filter['status']);
        }

        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Shift Name", "Shift Type", "From Time", "To Time", "Status", "Created_at", "Updated_at",
            ];
            $thismodel->select([
                'shifts.shift_name', 'shifts.shift_type', 'shifts.from_time', 'shifts.to_time', 'shifts.status', 'shifts.created_at', 'shifts.updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Shifts List'
            ];
            
            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'shifts.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Shifts List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,
                ];

                $file=  'shifts.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        // dd(getQueryWithBindings($thismodel));
        $shifts = $thismodel->paginate($limit);
        return view('backend.shifts.index', compact('shifts', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('backend.shifts.create');
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
            'shift_name' => ['required'],
            'shift_type' => ['required'],
            'from_time' => ['required'],
            'to_time' => ['required'],
        ]);

        $attributes['admin_id'] = Config::get('auth_detail')['id'];
        $attributes['status'] = 1;
        Shift::create($attributes);
        return redirect()->route('admin.administration.shifts.index')
            ->with('success', __('Shift created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Shift $shift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Shift $shift)
    {
        return view('backend.shifts.edit', compact('shift'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shift $shift)
    {
        // dd($shift);
        $attributes = request()->validate([
            'shift_name' => ['required'],
            'shift_type' => ['required'],
            'from_time' => ['required'],
            'to_time' => ['required'],
        ]);

        $shift->update($attributes);
        return redirect()->route('admin.administration.shifts.index')->with('success', __('Shift Detail Updated successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shift $shift)
    {
        $shift->delete(); 
        return redirect()->route('admin.administration.shifts.index')
            ->with('success', __('Shift deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        Shift::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
 
}
