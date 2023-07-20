<?php

namespace App\Http\Controllers\Backend;

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;

class CitiesController extends Controller
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

        $country_id = old('country_id');
        $state_id = old('state_id');
        $state_list = $city_list = '';
        $country_list = getCountrylist();
        $state_list = getStatelist($country_id);

        $thismodel = City::sortable(['created_at' => 'DESC']);
        
        if (!joined($thismodel, 'states')) {
            $thismodel->leftJoin('states', function ($join) {
                $join->on('cities.state_id', '=', 'states.id');
            });
        }

        if (!joined($thismodel, 'countries')) {
            $thismodel->leftJoin('countries', function ($join) {
                $join->on('cities.country_id', '=', 'countries.id');
            });
        }

        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('cities.name', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('cities.status', $filter['status']);
        }

        $thismodel->select([
            'cities.*', 'states.name as state_name'
        ]);

        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "City Name", "City Logo", "Status", "Created_at", "Updated_at",
            ];
            $thismodel->select([
                'cities.name', 'cities.state_id', 'cities.status', 'cities.created_at', 'cities.updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Cities List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'cities.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Banker List',     
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,    
                ];

                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download();
            }
        }

        // dd(getQueryWithBindings($thismodel));
        $cities = $thismodel->paginate($limit);
        return view('backend.cities.index', compact('cities', 'country_list', 'state_list', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $country_id = old('country_id');
        $state_id = old('state_id');
        $state_list = $city_list = '';
        $country_list = getCountrylist();
        $state_list = getStatelist($country_id);
        return view('backend.cities.create', compact('country_list', 'state_list'));
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
            'state_id' => ['required'],
            'country_id' => ['required'],
        ]);

        $attributes['status'] = 1;
        City::create($attributes);
        return redirect()->route('admin.cities.index')
            ->with('success', __('City created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        $country_id = old('country_id', $city->country_id);
        $state_id = old('state_id', $city->state_id);
        $country_list = getCountrylist();
        $state_list = getStatelist($country_id);
        return view('backend.cities.edit', compact('city', 'country_list', 'state_list'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, City $city)
    {
        $attributes = request()->validate([
            'name' => ['required'],
            'state_id' => ['required'],
            'country_id' => ['required'],
        ]);

        $city->update($attributes);

        return redirect()->route('admin.cities.index')->with('success', __('City Detail Updated successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        $city->delete();
        return redirect()->route('admin.cities.index')
            ->with('success', __('City deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        City::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
