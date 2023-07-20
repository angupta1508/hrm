<?php

namespace App\Http\Controllers\Backend;

use PDF;
use App\Models\Bank;  
use App\Models\User;
// use Barryvdh\DomPDF\PDF;    
use App\Exports\ExportUser; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller; 
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
 
class BanksController extends Controller
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

        $thismodel = Bank::sortable(['created_at' => 'DESC']);
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('banks.bank_name', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('banks.status', $filter['status']);
        }


        $thismodel->where('banks.admin_id', $loggedUser->admin_id);

        // dd(getQuery($thismodel));

        // excel_export
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Bank Name", "Bank Logo", "Status", "Created_at", "Updated_at",
            ];
            $thismodel->select([
                'banks.bank_name', 'banks.bank_logo', 'banks.status', 'banks.created_at', 'banks.updated_at',
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
                    'top_heading' => 'Bank List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,    
                ];

                $file = 'Banks.pdf';
                $pdf = PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        // dd(getQueryWithBindings($thismodel));
        $banks = $thismodel->paginate($limit);
        return view('backend.banks.index', compact('banks', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('backend.banks.create');
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
            'bank_name' => ['required'],
            'bank_logo' => ['required'],
        ]); 

        if (!empty($attributes['bank_logo'])) {
            $imgKey     =   'bank_logo';
            $imgPath    =   public_path(config('constants.bank_image_path'));
            $filename = UploadImage($request, $imgPath, $imgKey);
            if (!empty($filename)) {
                $attributes['bank_logo'] = $filename;
            }
        }
        
        $attributes['admin_id'] = $loggedUser->admin_id;

        $attributes['status'] = 1;
        Bank::create($attributes);
        return redirect()->route('admin.administration.banks.index')
            ->with('success', __('Bank created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        return view('backend.banks.edit', compact('bank'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bank $bank)
    {
        // dd($bank);
        $attributes = request()->validate([
            'bank_name' => ['required'],
            'bank_logo' => ['nullable'],
        ]);

        if (!empty($attributes['bank_logo'])) {
            $imgKey     =   'bank_logo';
            $imgPath    =   public_path(config('constants.bank_image_path'));
            $filename = UploadImage($request, $imgPath, $imgKey, $bank->bank_logo);
            if (!empty($filename)) {
                $attributes['bank_logo'] = $filename;
            }
        } 

        $bank->update($attributes);
        return redirect()->route('admin.administration.banks.index')->with('success', __('Bank Detail Updated successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        $bank->delete();
        return redirect()->route('admin.administration.banks.index')
            ->with('success', __('Bank deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        Bank::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
} 
