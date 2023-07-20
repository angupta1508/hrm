<?php

namespace App\Http\Controllers\Backend;

use App\Models\Enquiry;
// use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class EnquiriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->query();
        $limit = config('constants.default_page_limit');

        $thismodel = Enquiry::sortable(['created_at' => 'DESC']);
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('enquiries.name', 'LIKE', '%' . $keyword . '%')->orwhere('enquiries.email', 'LIKE', '%' . $keyword . '%')->orwhere('enquiries.mobile', 'LIKE', '%' . $keyword . '%');;
            });
        }
        
        if (!empty($filter['start_date'])) {
            $start_date_format = mysqlDateFormat($filter['start_date']);
            $thismodel->whereDate('enquiries.created_at', '>=', $start_date_format);
        }

        if (!empty($filter['end_date'])) {
            $end_date_format = mysqlDateFormat($filter['end_date']);
            $thismodel->whereDate('enquiries.created_at', '>=', $end_date_format);
        }


        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Name", "Email", "Phone",
                "Subject", "Message",
                "Status", "Created", "Updated",
            ];
            $thismodel->select([
                'enquiries.name', 'enquiries.email', 'enquiries.mobile',
                'enquiries.subject', 'enquiries.message', 'enquiries.status',
                'enquiries.created_at', 'enquiries.updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Enquiries List'
            ];
            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'enquiries.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Enquiries List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,    
                ];
                $file=  'enquiries.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        $enquiries = $thismodel->paginate($limit);
        return view('backend.enquires.index', compact('enquiries', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function show(Enquiry $enquiry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function edit(Enquiry $enquiry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Enquiry $enquiry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Enquiry $enquiry)
    {
        //
    }
}
