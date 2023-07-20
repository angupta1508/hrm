<?php

namespace App\Http\Controllers\Backend;

use PDF;
use App\Models\Bank;
use App\Models\User;
use Illuminate\Support\Facades\Config;
// use Barryvdh\DomPDF\PDF;
use App\Models\UserBanker;
use App\Exports\ExportUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;


class UserBankersController extends Controller
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
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        $bank_list = Bank::where([['status', '1'],['banks.admin_id', $loggedUser->admin_id]])->pluck("bank_name", "id");
        $filter = $request->query();
        $thismodel = UserBanker::sortable(['created_at' => 'DESC']);
        if (!joined($thismodel, 'banks')) {
            $thismodel->leftJoin('banks', function ($join) {
                $join->on('user_bankers.bank_id', '=', 'banks.id');
            });
        }

        if (!joined($thismodel, 'users')) {
            $thismodel->leftJoin('users', function ($join) {
                $join->on('user_bankers.user_id', '=', 'users.id');
            });
        }

        $thismodel->select([
            'user_bankers.*', 'banks.bank_name', 'users.user_uni_id','users.name', 'users.username', 'users.email', 'users.mobile',
        ]);

        $thismodel->where('user_bankers.admin_id',$loggedUser->admin_id);
        

        if (!empty($filter['user_id'])) {
            $thismodel->where('user_bankers.user_id', $filter['user_id']);
        }
        if (!empty($filter['account_no'])) {
            $thismodel->where('user_bankers.account_no', $filter['account_no']);
        } if (!empty($filter['ifsc_code'])) {
            $thismodel->where('user_bankers.ifsc_code', $filter['ifsc_code']);
        }
        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('user_bankers.status', $filter['status']);
        }
     

        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {

            $headings = [
                "User Id", "Username", "Name", "Email", "Mobile",
                "User Bank Name", "Account No", "Account Type",
                "Ifsc Code", "Account Holder Name", "Status", "Created_at", "Updated_at",
            ];
            $thismodel->select([
                'user_bankers.user_id', 'users.username', 'users.name', 'users.email', 'users.mobile', 'banks.bank_name', 'user_bankers.account_no', 'user_bankers.account_type', 'user_bankers.ifsc_code', 'user_bankers.account_name',
                'user_bankers.status', 'user_bankers.created_at', 'user_bankers.updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Banks List'
            ];
            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'Banks.csv');
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

                $file = 'Banks.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        // excel_export

        $thismodel->where('users.trash', 0);
        // dd(getQuery($thismodel));
        $user_bankers = $thismodel->paginate($limit);

        // dd(getQueryWithBindings($thismodel));
        return view('backend.user-bankers.index', compact('user_bankers', 'user_list', 'bank_list', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $loggedUser = Auth::user();
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        $bank_list = Bank::where([['status', '1'],['banks.admin_id', $loggedUser->admin_id]])->pluck("bank_name", "id")->toArray();
        return view('backend.user-bankers.create', compact('user_list', 'bank_list'));
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
            'user_id' => ['required'],
            'bank_id' => ['required'],
            'account_no' => ['required'],
            'account_type' => ['required'],
            'ifsc_code' => ['required'],
            'account_name' => ['required'],
        ]);

        $attributes['status'] = 1;
        $attributes['admin_id'] = $loggedUser->admin_id;
        $dd = UserBanker::create($attributes);
        // dd($dd);
            return redirect()->route('admin.user-bankers.index')
            ->with('success', __('User Banker created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, UserBanker $user_banker)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, UserBanker $user_banker)
    {
        $loggedUser = Auth::user();
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        $bank_list = Bank::where([['status', '1'],['banks.admin_id', $loggedUser->admin_id]])->pluck("bank_name", "id")->toArray();
        return view('backend.user-bankers.edit', compact('user_banker', 'user_list', 'bank_list'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserBanker $user_banker)
    {
        // dd($user_banker);
        $attributes = request()->validate([
            'user_id' => ['required'],
            'bank_id' => ['required'],
            'account_no' => ['required'],
            'account_type' => ['required'],
            'ifsc_code' => ['required'],
            'account_name' => ['required'],
        ]);

        $user_banker->update($attributes);

        return redirect()->route('admin.user-bankers.index')->with('success', __('User Banker Detail Updated successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserBanker $user_banker)
    {
        $user_banker->delete();

        return redirect()->route('admin.user-bankers.index')
            ->with('success', __('User Banker deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        UserBanker::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
