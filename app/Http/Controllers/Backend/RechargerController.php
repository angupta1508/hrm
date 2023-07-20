<?php

namespace App\Http\Controllers\Backend;
use PDF;
use App\Models\User;
use Razorpay\Api\Api;
use App\Models\Package;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RechargeHistory;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportUser;

class RechargerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit =  config('constant.pagination_page_limit');
        $filter = $request->query();
        $thismodel = RechargeHistory::Sortable()->latest();
        $status     =   $request->status;
        if (isset($status) && $status != "") {
            $thismodel->where('status', $status);
        }
        if (!empty($request->search)) {
            $keyword    =   $request->search;
            $thismodel->where(function ($query) use ($keyword) {
                $query->Where('recharge_uni_id', 'LIKE', '%' . $keyword . '%')->orWhere('package_uni_id', 'LIKE', '%' . $keyword . '%')->orWhere('admin_id', 'LIKE', '%' . $keyword . '%')->orWhere('order_id', 'LIKE', '%' . $keyword . '%')->orWhere('razorpay_id', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (!empty($request->start_date) ) {
            $thismodel->whereDate('recharge_histories.created_at', '>=', $request->start_date);
        }
        if (!empty($request->end_date)) {
            $thismodel->whereDate('recharge_histories.created_at', '<=', $request->end_date);
        }


        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {

            $headings = [
                'Recharge uni id','Package uni id', 'Amount','Admin id',
                "Order id","Razorpay Id","Pay Method",
                "Status",
                "Created", "Updated",
            ];
            $thismodel->select([
                'recharge_histories.recharge_uni_id','recharge_histories.package_uni_id',
                'recharge_histories.amount','recharge_histories.admin_id','recharge_histories.order_id',
                'recharge_histories.razorpay_id','recharge_histories.pay_method','recharge_histories.status',
                'recharge_histories.created_at', 'recharge_histories.updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Recharge histories List'
            ];
    
            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'recharge histories.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }
    
                $variabls = [
                    'top_heading' => 'Recharge histories  List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'header' => $header,             
                    'records' => $records,
                    
                ];
    
                $pdf =  PDF::loadview('pdf', $variabls);
                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }
                return $pdf->download();
            }
        } 
        $thismodel->orderBy('recharge_histories.id', 'desc');
        $recharge = $thismodel->paginate($limit);
        return view('backend.recharge.index', compact('recharge','filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }  
        

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // dd($request->session()->get('result'));
        $package = Package::where([['status', 1], ['trash', 0]])->get();
        $result = '';
        $result = $request->session()->get('result');
        if(!empty($result)){
            if($result['status'] == 0){
                return view('backend.recharge.recharge', compact('package'))->with(['error' => $result['message']]);
            }
        } else{
            return view('backend.recharge.recharge', compact('package'));
            
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $packageData = Package::where('package_uni_id', $request->package)->first();
        $userData  = User::where('phone', $request->phone)->first();
        $email =   !empty($userData->email) ? $userData->email : '';
        // dd($userData);
        $razorpayId = Setting::where('setting_name', 'razorpay_id')->first();
        $razorpayKey = Setting::where('setting_name', 'razorpay_Key')->first();
        $api = new Api($razorpayId->setting_value, $razorpayKey->setting_value);
        $receiptId = Str::random(20);
        $order = $api->order->create(
            array(
                'receipt' => $receiptId,
                'amount' => $packageData->price * 100,
                'currency' => 'INR'
            )
        );
        // dd($order->amount);
        $array = array('package_id' => $request->package, 'phone' => $request->phone, 'amount' => $order->amount, 'order_id' => $order->id,  'razorpayId' => $razorpayId->setting_value, 'razorpayKey' => $razorpayKey->setting_value, 'duration' => $packageData->duration,'email'=>$email);
        if (!empty($order)) {
            $data = array(
                'package_uni_id'     => $request->package,
                'order_id'           => $order->id,
                'admin_id'           => $userData->user_uni_id,
                'amount'             => $packageData->price,
                'recharge_uni_id'    => new_sequence_code('REC'),
            );
            RechargeHistory::create($data);
            $res = array('status' => 1, 'data' => $array);
        } else {
            $res = array('status' => 0, 'msg' => 'Something went Wrong');
        }
        return response()->json($res);
    }

  

    public function payment(Request $request)
    {
        // die;
        $userData  = User::where('phone', $request->number)->first();
        $data = array();
        $data = array(
            'razorpay_id'        => $request->razorpay_id,
            'status'              => 1,
            // 'pay_method '         => $request->pay_method,
        );
        if ($userData->role_id == 2) {
            $result =  RechargeHistory::where('order_id', $request->order_id)->update($data);
            $packageData = Package::where('package_uni_id', $request->package_id)->first();

            if($userData->package_valid_date > Config::get('current_date')){
                $date = date('Y-m-d', strtotime($userData->package_valid_date . ' +' . $request->duration . 'day'));
            }else{
                $date = date('Y-m-d', strtotime(Config::get('current_date') . ' +' . $request->duration . 'day'));
            }
            $result =  User::where('phone', $request->number)->update(['package_valid_date' => $date]);
            if ($result) {
                $response = array(
                    'status' => 1,
                    'msg' => "Payment Successfully",
                    'data' => $data,
                );
            } else {
                $response = array(
                    'status' => 0,
                    'msg' => "Something went wrong",
                );
            }
        } else {
            $response = array(
                'status' => 0,
                'msg' => "Something went wrong",
            );
        }
        return response()->json($response);
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
