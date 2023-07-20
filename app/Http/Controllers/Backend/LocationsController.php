<?php

namespace App\Http\Controllers\Backend;

use PDF;
use App\Models\Location;  
use Illuminate\Support\Facades\Config;
use App\Models\User;
// use Barryvdh\DomPDF\PDF;  
use App\Exports\ExportUser; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller; 
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class LocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $loggedUser = Auth::user();
        // dd($loggedUser);

        $attributes = request()->validate([
        ]); 

        
        $attributes['admin_id'] = $loggedUser->admin_id;


        $limit =  config('constants.default_page_limit');
        $filter = $request->query();

        $thismodel = Location::sortable(['created_at' => 'DESC']);
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('locations.location_name', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('locations.status', $filter['status']);
        }

        if (isset($filter['weekly_holiday']) && $filter['weekly_holiday'] != "") {
            $thismodel->where('locations.weekly_holiday', $filter['weekly_holiday']);
        }

 

        $thismodel->where('locations.admin_id', $loggedUser->admin_id);

        // dd(getQuery($thismodel));

        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Location Name", "Latitude", "Longitude", "Acceptable Range (In Meter)", "Weekly Holiday", "Status", "Created_at", "Updated_at",
            ];
            $thismodel->select([
                'locations.location_name', 'locations.latitude', 'locations.longitude', 'locations.acceptable_range', 'locations.weekly_holiday', 'locations.status', 'locations.created_at', 'locations.updated_at',
            ]);
            $records = $thismodel->get();
            
            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Locations List'
            ];
            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'locations.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Location List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,    
                ];

                $file = 'locations.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        // dd(getQueryWithBindings($thismodel));
        $locations = $thismodel->paginate($limit);
        return view('backend.locations.index', compact('locations', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('backend.locations.create');
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
        // dd($loggedUser);

        $attributes = request()->validate([
            'location_name' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
            'ip' => ['required'],
            'acceptable_range' => ['required'],
            'weekly_holiday' => ['required'],
        ]); 

        // dd($attributes);
        
        $attributes['admin_id'] = $loggedUser->admin_id;

        // dd($attributes);
        $attributes['status'] = 1;
        Location::create($attributes);

        return redirect()->route('admin.administration.locations.index')
            ->with('success', __('Location created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        return view('backend.locations.edit', compact('location'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        // dd($location);
        $attributes = request()->validate([
            'location_name' => ['required'],
            'latitude' => ['required'],
            'longitude' => ['required'],
            'ip' => ['required'],
            'acceptable_range' => ['required'],
            'weekly_holiday' => ['required'],
        ]);


        $location->update($attributes);
        return redirect()->route('admin.administration.locations.index')->with('success', __('Location Detail Updated successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('admin.administration.locations.index')
            ->with('success', __('Location deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        Location::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
