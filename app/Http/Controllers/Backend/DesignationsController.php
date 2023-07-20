<?php

namespace App\Http\Controllers\Backend;

use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Models\Designation;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
// use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser;

class DesignationsController extends Controller
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

        $thismodel = Designation::sortable(['created_at' => 'DESC']);
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('designations.name', 'LIKE', '%' . $keyword . '%');
                $query->where('designations.details', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('designations.status', $filter['status']);
        }

        $thismodel->where('designations.admin_id', $loggedUser->admin_id);


        // dd($thismodel->get());
        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Designation Name", "Designation Details", "Status", "Created_at", "Updated_at",
            ];
            $thismodel->select([
                'designations.name', 'designations.details', 'designations.status',  'designations.created_at', 'designations.updated_at'
            ]);

            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Designations List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'designations.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Designation List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,    
                ];
                $file = 'designations.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        // dd(getQueryWithBindings($thismodel));
        $designations = $thismodel->paginate($limit);
        return view('backend.designations.index', compact('designations', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('backend.designations.create');
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
            'details' => ['required'],
        ]);

        $attributes['admin_id'] = $loggedUser->admin_id;

        $attributes['status'] = 1;
        Designation::create($attributes);
        return redirect()->route('admin.administration.designations.index')
            ->with('success', __('Designation created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Designation $designation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Designation $Designation)
    {
        return view('backend.designations.edit', compact('Designation'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Designation $Designation)
    {
        // dd($designation);
        $attributes = request()->validate([
            'name' => ['required'],
            'details' => ['required'],
        ]);


        $Designation->update($attributes);
        return redirect()->route('admin.administration.designations.index')->with('success', __('Designation Detail Updated successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Designation $Designation)
    {

        $Designation->delete();
        return redirect()->route('admin.administration.designations.index')
            ->with('success', __('Designation deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        Designation::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
