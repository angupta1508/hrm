<?php

namespace App\Http\Controllers\Backend;

use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Config;
use App\Exports\ExportUser;
use Illuminate\Http\Request;
use App\Models\WalletStatement;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class WalletStatementsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        $limit =  config('constants.default_page_limit');

        $filter = $request->query();

        $thismodel = WalletStatement::sortable(['created_at' => 'DESC']);
        if (!joined($thismodel, 'users')) {
            $thismodel->leftJoin('users', function ($join) {
                $join->on('wallet_statements.user_id', '=', 'users.id');
            });
        }

        //  dd(getQueryWithBindings($thismodel));
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('users.user_uni_id', 'LIKE', '%' . $keyword . '%')->orwhere('users.name', 'LIKE', '%' . $keyword . '%')->orwhere('users.email', 'LIKE', '%' . $keyword . '%')->orwhere('users.mobile', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (isset($filter['main_type']) && $filter['main_type'] != "") {
            $thismodel->where('wallet_statements.main_type', $filter['main_type']);
        }

        if (!empty($filter['user_id'])) {
            $thismodel->where('wallet_statements.user_id', $filter['user_id']);
        }

        if (!empty($filter['start_date'])) {
            $start_date_format = mysqlDateFormat($filter['start_date']);
            $thismodel->whereDate('wallet_statements.created_at', '>=', $start_date_format);
        }

        if (!empty($filter['end_date'])) {
            $end_date_format = mysqlDateFormat($filter['end_date']);
            $thismodel->whereDate('wallet_statements.created_at', '<=', $end_date_format);
        }

        $thismodel->select([
            'wallet_statements.*', 'users.user_uni_id', 'users.name as user_name'
        ]); 

        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {

            $headings = [
                "User Id", "Name", "Email", "Phone", "Payment Id", "Transaction Type", 
                "Opening Wallet Balance", "Amount Type", "Amount", "Reference Number",
                "Narration", "Status", "Created", "Updated",
            ];
            $thismodel->select([
                'wallet_statements.user_id', 'users.name', 'users.email', 'users.mobile',
                'wallet_statements.payment_id', 'wallet_statements.transation_type', 'wallet_statements.opening_wallet_balance', 'wallet_statements.amount_type', 'wallet_statements.amount', 'wallet_statements.reference_number', 'wallet_statements.narration', 'wallet_statements.status', 'wallet_statements.created_at', 'wallet_statements.updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Wallet statements List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'wallet statements.csv');
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

        $walletStatements = $thismodel->paginate($limit);
        return view('backend.wallet-statements.index', compact('walletStatements', 'user_list', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        return view('backend.wallet-statements.create', compact('user_list'));
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
            'user_id' => ['required'],
            'amount_type' => ['required'],
            'amount' => ['required'],
            'narration' => ['required'],
        ]);

        if ($attributes['amount_type'] == '1') {
            $attributes['amount'] = $attributes['amount'];
            $attributes['narration'] = 'Wallet Amount Add by admin for ' . $attributes['wallet_history_description'] . ' # RS. ' . $attributes['amount'];
        } else {
            $attributes['amount'] = $attributes['amount'];
            $attributes['narration'] = 'Wallet Amount Remove by admin for ' . $attributes['narration'] . ' # RS. ' . $attributes['amount'];
        }

        $attributes['status'] = 1;
        $attributes['created_at'] = date('Y-m-d H:i:s');
        $attributes['updated_at'] = date('Y-m-d H:i:s');
        WalletStatement::create($attributes);
        return redirect()->route('admin.wallet-statements.index')
            ->with('success', __('Wallet created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WalletStatement  $walletStatement
     * @return \Illuminate\Http\Response
     */
    public function show(WalletStatement $walletStatement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WalletStatement  $walletStatement
     * @return \Illuminate\Http\Response
     */
    public function edit(WalletStatement $walletStatement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WalletStatement  $walletStatement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WalletStatement $walletStatement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WalletStatement  $walletStatement
     * @return \Illuminate\Http\Response
     */
    public function destroy(WalletStatement $walletStatement)
    {
        //
    }
}
