<?php

namespace App\Http\Controllers\Backend;

use App\Exports\ExportUser;
use Illuminate\Http\Request;
use App\Models\SalarySettlement;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;

class SalarySettlementController extends Controller
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

        $thismodel = SalarySettlement::sortable(['created_at' => 'DESC']);
        $thismodel->leftJoin('users', function ($join) {
            $join->on('salary_settlements.user_id', '=', 'users.id');
        });
        if (!empty($filter['type'])) {
            $thismodel->where('salary_settlements.type', $filter['type']);
        }
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('salary_settlements.settlement_month', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (isset($filter['user_id']) && $filter['user_id'] != "") {
            $thismodel->where('users.id', $filter['user_id']);
        }

        // dd(getQueryWithBindings($thismodel));

        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Settlement Month", "Name", "Type", "Amount", "Description", "Created"
            ];
            $thismodel->select([
                'salary_settlements.settlement_month',
                'users.name',
                'salary_settlements.type',
                'salary_settlements.amount',
                'salary_settlements.description',
                'salary_settlements.created_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Salary Settlement List'
            ];
            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings, $header), 'salarysettlement.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Salary Settlement List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,
                ];

                $file = 'Salary Settlement.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }
        $thismodel->select(['salary_settlements.*',
        'users.name',]);
        $salary_settlements = $thismodel->paginate($limit);
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        return view('backend.salary_settlement.index', compact('salary_settlements','user_list', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        return view('backend.salary_settlement.create',compact('user_list'));
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
            'settlement_month'   => ['required'],
            'user_id'            => ['required'],
            'type'               => ['required'],
            'amount'             => ['required'],
            'description'        => ['nullable'],
        ]);
        $attributes['status'] = 1;
        SalarySettlement::create($attributes);
        return redirect()->route('admin.payroll.salary-settlement.index')
            ->with('success', __('Salary Settlement created successfully.'));
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
    public function edit(SalarySettlement $salary_settlement)
    {
        $user_list = getRoleWiseUserData(['role_type' => ['User']]);
        return view('backend.salary_settlement.edit', compact('salary_settlement','user_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalarySettlement $salary_settlement)
    {
        $attributes = request()->validate([
            'settlement_month'   => ['required'],
            'user_id'            => ['required'],
            'type'               => ['required'],
            'amount'             => ['required'],
            'description'        => ['nullable'],
        ]);
        $salary_settlement->update($attributes);
        return redirect()->route('admin.payroll.salary-settlement.index')
        ->with('success', __('Salary Settlement updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalarySettlement $salary_settlement)
    {
        $salary_settlement->delete();
        return redirect()->route('admin.payroll.salary-settlement.index')
            ->with('success', __('Salary Settlement deleted successfully.'));
    }
}
