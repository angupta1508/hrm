<?php

namespace App\Http\Controllers\Backend;

use PDF;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Notice; 
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class NoticesController extends Controller
{
            /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $loggedUser = Auth::user();

        $limit =  config('constants.pagination_page_limit');
        $filter = $request->query();
        $thismodel = Notice::sortable()->latest();

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('status', $filter['status']);
        }
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('notices.title', 'LIKE', '%' . $keyword . '%')->orwhere('notices.type', 'LIKE', '%' . $keyword . '%');
            });
        }

        $thismodel->where('notices.admin_id', $loggedUser->admin_id);

        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Notice Title", "Description",
                "Status", "Created", "Updated",
            ];
            $thismodel->select([
                'notices.title', 'notices.description',
                'notices.status', 'notices.created_at', 'notices.updated_at',
            ]);
            $datas = $thismodel->get();

            $records = collect();
        
            foreach ($datas as $data) {
                $items = strip_tags($data);
                $array = json_decode($items); //Convert the string to an array
        
                $records->push($array);
            }

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Notices List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'notices.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys(get_object_vars($records[0]));
                }

                $variabls = [
                    'top_heading' => 'Notice List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,    
                ];

                $file=  'notices.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);
                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }
                return $pdf->download($file);
            }
        } else {
            $thismodel->select([
                'notices.*',
            ]);
        }
        // dd(getQueryWithBindings($thismodel));

        $notices = $thismodel->paginate($limit);
        $forattachment = Notice::latest()->get();
        
        return view('backend.notices.index', compact('notices', 'filter', 'forattachment'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('backend.notices.create');
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
            'title' => ['required'],
            'type' => ['required'],
            'description' => ['required'],
            'attachment' => ['nullable', 'mimes:pdf,doc,docx', 'max:2048'],
        ]);
              
        $attributes['admin_id'] = $loggedUser->admin_id;  
        $attributes['date'] = Config::get('current_date', date('Y-m-d'));

        if (!empty($attributes['attachment'])) {
            $img = 'attachment';
            $imgPath = public_path(config('constants.notice_image_path'));
            $filename = documentUpload($request, $imgPath, $img);
            $attributes['attachment'] = $filename;
        }
        $user =  Notice::create($attributes);
        return redirect()->route('admin.cms.notices.index')
            ->with('success', 'notices created successfully.');
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
        $notices = Notice::find($id);
        return view('backend.notices.edit', compact('notices'));
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
        $attributes = request()->validate([ 
            'title' => ['required'],
            'type' => ['required'],
            'description' => ['required'],
            'attachment' => ['nullable', 'mimes:pdf,doc,docx', 'max:2048'],
        ]);

        if (!empty($attributes['attachment'])) {
            $img = 'attachment';
            $imgPath = public_path(config('constants.notice_image_path'));
            $filename = documentUpload($request, $imgPath, $img);
            $attributes['attachment'] = $filename;
        }

        Notice::where('id', $id)->update($attributes);
        return redirect()->route('admin.cms.notices.index')
            ->with('success', 'notices created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Notice::where('id', $id)->delete();
        return redirect()->route('admin.cms.notices.index')
            ->with('success', 'Notice deleted successfully');
    }

    public function changeStatus(Request $request)
    {
        //        pr($request);die;
        $page = Notice::find($request->id)->update(['status' => $request->status]);

        return response()->json(['success' => 'Status changed successfully.']);
    }
    public function downloadAttachment($id)
    {   
        $forattachment = Notice::findOrFail($id);
        $path = public_path(config('constants.notice_image_path') . '/' . $forattachment->attachment);
        
        if (file_exists($path)) {
            return response()->download($path);
        } else {
            return abort(404);
        }
    }
    
}
