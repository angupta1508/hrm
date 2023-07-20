<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Config;
use App\Models\Mood;  
use App\Models\User;
use Barryvdh\DomPDF\PDF; 
use App\Exports\ExportUser; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller; 
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;


class MoodsController extends Controller
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

        $getemployees = getRoleWiseUserData(['role_type'=>[config('constants.role_type_employee')]]);
        // dd($getusers);


        $thismodel = Mood::leftJoin('users', function($join){
          $join->on('moods.user_id', '=', 'users.id'); //uses the on() method to specify the join condition between the moods and users tables in the database.
        })->leftJoin('mood_types', function($join){
            $join->on('moods.type_id', '=', 'mood_types.id'); //uses the on() method to specify the join condition between the moods and users tables in the database.
          })
        ->select(['moods.*','users.name as user_name','mood_types.name as mood_type']);
       
        // pr(getQuery($result));die;
        // dd($result);
        // $thismodel = Mood::sortable(['created_at' => 'DESC']);
        // if (!empty($filter['search'])) {
        //     $keyword = $filter['search'];
        //     $thismodel->where(function ($query) use ($keyword) {
        //         $query->where('moods.name', 'LIKE', '%' . $keyword . '%');
        //     });
        // }

        if (isset($filter['user_id']) && $filter['user_id'] != "") {
            $thismodel->where('moods.user_id', $filter['user_id']);
        }


        if (isset($filter['mood_type']) && $filter['mood_type'] != "") {
            $thismodel->where('mood_types.name', $filter['mood_type']);
        }


        // $thismodel->where('moods.admin_id', $loggedUser->admin_id);

        // dd(getQuery($thismodel));

        // excel_export
        // if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
        //     $headings = [
        //         "Mood Name", "Mood Logo", "Status", "Created_at", "Updated_at",
        //     ];
        //     $thismodel->select([
        //         'moods.name', 'moods.status', 'moods.created_at', 'moods.updated_at',
        //     ]);
        //     $records = $thismodel->get();
        // $header = [
        //     'Company Name'  =>  Config::get('company_name'),
        //     'File'        =>  'Moods List'
        // ];
            
        //     if (isset($filter['excel_export'])) {
        //         return Excel::download(new ExportUser($records, $headings,$header), 'moods.csv');
        //     } else if (isset($filter['pdf_export'])) {
        //         $tabel_keys = [];
        //         if ($records->count() > 0) {
        //             $tabel_keys = array_keys($records[0]->toArray());
        //         }

        //         $variabls = [
        //             'top_heading' => 'Moods List',
        //             'headings' => $headings,
        //             'tabel_keys' => $tabel_keys,
        //             'records' => $records,
        //             'header' => $header,    
        //         ];

        //         $pdf =  PDF::loadview('pdf', $variabls);

        //         if (count($headings) > 6) {
        //             $pdf->setPaper('a4', 'landscape');
        //         }

        //         return $pdf->download();
        //     }
        // }
 
        // dd(getQuery($thismodel));    
        
        $moods = $thismodel->paginate($limit);
        // dd($moods);
        return view('backend.moods.index', compact('moods', 'filter', 'getemployees'))->with('i', (request()->input('page', 1) - 1) * $limit);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('backend.moods.create');
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
            'type_id' => ['required'],
            'remark' => ['required'],
        ]); 

        
        $attributes['admin_id'] = $loggedUser->admin_id;
        Mood::create($attributes);
        return redirect()->route('admin.moods.moods.index')
            ->with('success', __('Mood created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Mood $mood)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Mood $mood)
    {
        return view('backend.moods.edit', compact('mood'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mood $mood)
    {
        // dd($mood);
        $attributes = request()->validate([
            'name' => ['required'],
        ]);


        $mood->update($attributes);
        return redirect()->route('admin.moods.moods.index')->with('success', __('Mood Detail Updated successfully.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mood $mood)
    {
        $mood->delete();
        return redirect()->route('admin.moods.moods.index')
            ->with('success', __('Mood deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        Mood::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
