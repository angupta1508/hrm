<?php

namespace App\Http\Controllers\Backend;


use PDF;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
// use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser;



class HolidaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $LoggedUser = Auth::user();

        $filter = $request->query();
        $limit =  config('constants.default_page_limit');

        $thismodel = Holiday::sortable(['' => 'DESC']);
        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('status', $filter['status']);
        }

        if (!empty($filter['holiday_name'])) {
            $keyword = $filter['holiday_name'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('holiday_name', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (!empty($filter['holiday_type'])) {
            $keyword = $filter['holiday_type'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('holiday_type', 'LIKE', '%' . $keyword . '%');
            });
        }

        $thismodel->where('holidays.admin_id', $LoggedUser->admin_id);

        // dd(getQueryWithBindings($thismodel));

        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Holiday Name","Status", "created_at", "updated_at",
            ];
            $thismodel->select([

                'holiday_name', 'Status', 'created_at', 'updated_at',
            ]);
            $records = $thismodel->get();
            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Holidays List'
            ];
            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'Holidays.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Holiday Type List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,    
                ];

                $file = 'Holidays.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }


        $thismodel->where('holidays.admin_id', $LoggedUser->admin_id);

        $thismodel->orderBy('holidays.id', 'desc');

        $holidays = $thismodel->paginate($limit);
        return view('backend.holidays.index', compact('holidays', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.holidays.create');
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
            'holiday_name' => ['required'],
            // 'holiday_type' => ['required'],
            'date' => ['required'],

        ]);

        $attributes['admin_id'] = $LoggedUser->admin_id;


        Holiday::create($attributes);
        return redirect()->route('admin.leave.holidays.index')
            ->with('success', __('Holiday created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function show(Holiday $holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function edit(Holiday $holiday)
    {
        return view('backend.holidays.edit', compact('holiday'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Holiday $holiday)
    {
        $LoggedUser = Auth::user();

        $attributes = request()->validate([
            'holiday_name' => ['required'],
            // 'holiday_type' => ['required'],
            'date' => ['required'],
            // 'updated_by' => ['required'],
        ]);

        $attributes['admin_id'] = $LoggedUser->id;


        $holiday->update($attributes);

        return redirect()->route('admin.leave.holidays.index')
            ->with('success', __('Holiday created successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('admin.leave.holidays.index')
            ->with('success', __('Holiday deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        Holiday::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
