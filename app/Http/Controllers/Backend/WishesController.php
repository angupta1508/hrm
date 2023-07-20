<?php

namespace App\Http\Controllers\Backend;

use PDF;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wish;
use Maatwebsite\Excel\Facades\Excel;
// use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Auth;
use App\Exports\ExportUser;



class WishesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $loggedUser = Auth::user();

        $filter = $request->query();
        $limit =  config('constants.default_page_limit');

        $thismodel = Wish::sortable(['' => 'DESC']);
        // if (isset($filter['status']) && $filter['status'] != "") {
        //     $thismodel->where('status', $filter['status']);
        // }

        if (!empty($filter['user_id'])) {
            $keyword = $filter['user_id'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('user_id', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (!empty($filter['sender_id'])) {
            $keyword = $filter['sender_id'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('sender_id', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (!empty($filter['remark'])) {
            $keyword = $filter['remark'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('remark', 'LIKE', '%' . $keyword . '%');
            });
        }

        $thismodel->where('wishes.admin_id' , $loggedUser->admin_id);

        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Admin Id", "User Id", "Sender Id", "Remark", "created_at", "updated_at",
            ];
            $thismodel->select([
                'admin_id', 'user_id', 'sender_id', 'remark', 'created_at', 'updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Wishes List'
            ];
            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'wishes.csv');
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

                $file=  'wishes.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }


        $thismodel->where('wishes.admin_id', $loggedUser->admin_id);


        $thismodel->orderBy('wishes.id', 'desc');

        $wish = $thismodel->paginate($limit);
        return view('backend.wishes.index', compact('wish', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.wishes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $loggedUser = Auth::user();
        $attributes = request()->validate([
            'user_id' => ['required'],
            'sender_id' => ['required'],
            'remark' => ['required'],

        ]);

        $attributes['admin_id'] = $loggedUser->admin_id;
        // $attributes['user_id'] = $loggedUser->user_uni_id;
        // $attributes['admin_id'] = $loggedUser->admin_id;



        Wish::create($attributes);


        return redirect()->route('admin.moods.Wishes.index')
            ->with('success', __('Wish created successfully.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wish  $wish
     * @return \Illuminate\Http\Response
     */
    public function show(Wish $Wish)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wish  $wish
     * @return \Illuminate\Http\Response
     */
    public function edit(Wish $Wish)
    {
        return view('backend.wishes.edit', compact('Wish'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wish  $wish
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wish $Wish)
    {

        $attributes = request()->validate([
            'user_id' => ['required'],
            'sender_id' => ['required'],
            'remark' => ['required'],

        ]);



        $Wish->update($attributes);

        return redirect()->route('admin.moods.Wishes.index')
            ->with('success', __('Wish updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wish  $wish
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wish $Wish)
    {
        $Wish->delete();
        return redirect()->route('admin.moods.Wishes.index')
            ->with('success', __('Wish deleted successfully.'));
    }
}
