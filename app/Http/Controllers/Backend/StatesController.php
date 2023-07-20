<?php

namespace App\Http\Controllers\Backend;

use App\Models\State;
use Illuminate\Support\Facades\Config;
use App\Models\Country;
use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class StatesController extends Controller
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

        $country_list = Country::where(['status' => '1'])->pluck("name", "id")->toArray();


        $thismodel = State::sortable(['created_at' => 'DESC']);
        if (!joined($thismodel, 'countries')) {
            $thismodel->leftJoin('countries', function ($join) {
                $join->on('states.country_id', '=', 'countries.id');
            });
        }

        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('states.name', 'LIKE', '%' . $keyword . '%');
            });
        } 

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('states.status', $filter['status']);
        }

        $thismodel->select([
            'states.*', 'countries.name as country_name',
        ]);

        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "State", "Country", "Status", "Created_at", "Updated_at",
            ];
            $thismodel->select([
                'states.name', 'countries.name as country_name', 'states.status', 'states.created_at', 'states.updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'States List'
            ];
            
            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'states.csv');
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

        $states = $thismodel->paginate($limit);
        return view('backend.states.index', compact('states', 'country_list', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $country_list = Country::where(['status' => '1'])->pluck("name", "id")->toArray();
        return view('backend.states.create', compact('country_list'));
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
            'country_id' => ['required'],
            'name' => ['required'],
        ]);

        $attributes['status'] = 1;
        State::create($attributes);
        return redirect()->route('admin.states.index')
            ->with('success', __('Country created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(State $state)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(State $state)
    {
        $country_list = Country::where(['status' => '1'])->pluck("name", "id")->toArray();
        return view('backend.states.edit', compact('state', 'country_list'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, State $state)
    {
        $attributes = request()->validate([
            'country_id' => ['required'],
            'name' => ['required'],
        ]);

        $state->update($attributes);
        return redirect()->route('admin.states.index')->with('success', __('Country Detail Updated successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(State $state)
    {
        $state->delete();
        return redirect()->route('admin.states.index')
            ->with('success', __('Country deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        State::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
