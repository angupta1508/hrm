<?php

namespace App\Http\Controllers\Backend;

use PDF;
use App\Models\Company; 
use App\Models\User;
// use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser; 
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller; 
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class CompaniesController extends Controller
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

        $thismodel = Company::sortable(['created_at' => 'DESC']);
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('companies.name', 'LIKE', '%' . $keyword . '%');
            });
        } 

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('companies.status', $filter['status']);
        }

        $thismodel -> where('companies.admin_id', $loggedUser -> admin_id);

        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Company Name", "Status", "Created_at", "Updated_at",
            ];
            $thismodel->select([
                'companies.name', 'companies.status', 'companies.created_at', 'companies.updated_at',
            ]); 
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Companies List'
            ];
            
            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'companies.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Company List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,    
                ];

                $file = 'companies.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        // dd(getQueryWithBindings($thismodel));
        $companies = $thismodel->paginate($limit);
        return view('backend.companies.index', compact('companies', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('backend.companies.create');
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
        Company::create($attributes);
        return redirect()->route('admin.administration.companies.index')
            ->with('success', __('Company created successfully.'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        return view('backend.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        // dd($company);
        $attributes = request()->validate([
            'name' => ['required'],
        ]);

        $company->update($attributes);
        return redirect()->route('admin.administration.companies.index')->with('success', __('Company Detail Updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete(); 
        return redirect()->route('admin.administration.companies.index')
            ->with('success', __('Company deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        Company::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
