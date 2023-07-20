<?php

namespace App\Http\Controllers\Backend;

use PDF;
use Illuminate\Support\Facades\Config;
// use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class PaymentGatewaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    {
        $filter = $request->query();
        $limit =  config('constants.default_page_limit');

        $thismodel = PaymentGateway::sortable(['created_at' => 'DESC']);
        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('status', $filter['status']);
        }
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('payment_gateways.language_name', 'LIKE', '%' . $keyword . '%');
            });
        }
        // dd(getQueryWithBindings($thismodel));

        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Language Name", "Status", "Created", "Updated",
            ];
            $thismodel->select([
                'payment_gateways.language_name',
                'payment_gateways.status', 'payment_gateways.created_at', 'payment_gateways.updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Payment gateways List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings, $header), 'payment_gateways.csv');
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

        $paymentGateways = $thismodel->paginate($limit);
        return view('backend.payment-gateways.index', compact('paymentGateways', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.payment-gateways.create');
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
            'gateway_name' => ['required'],
            'gateway_txn_charges' => ['required'],
            'description' => ['nullable'],
        ]);

        $attributes['status'] = 1;

        PaymentGateway::create($attributes);
        return redirect()->route('admin.administration.payment-gateways.index')
            ->with('success', __('Language created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentGateway $paymentGateway)
    {
        return view('backend.payment-gateways.edit', compact('paymentGateway'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentGateway $paymentGateway)
    {
        $attributes = request()->validate([
            'gateway_name' => ['required'],
            'gateway_txn_charges' => ['required'],
            'description' => ['nullable'],
        ]);

        $paymentGateway->update($attributes);
        return redirect()->route('admin.administration.payment-gateways.index')
            ->with('success', __('Language created successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentGateway $paymentGateway)
    {
        $paymentGateway->delete();
        return redirect()->route('admin.administration.payment-gateways.index')
            ->with('success', __('Language deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        PaymentGateway::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
