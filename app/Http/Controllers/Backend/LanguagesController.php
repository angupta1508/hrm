<?php

namespace App\Http\Controllers\Backend;

use PDF;
use App\Models\Language;
// use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class LanguagesController extends Controller
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

        $thismodel = Language::sortable(['created_at' => 'DESC']);
        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('status', $filter['status']);
        }
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('languages.language_name', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (!empty($filter['language_code'])) {
            $keyword = $filter['language_code'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('languages.language_code', 'LIKE', '%' . $keyword . '%');
            });
        }
        // dd(getQueryWithBindings($thismodel));

        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Language Name", "Status", "Created", "Updated",
            ];
            $thismodel->select([
                'languages.language_name',
                'languages.status', 'languages.created_at', 'languages.updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Languages List'
            ];
            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'languages.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Languages List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,    
                ];

                $file = 'languages.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        } 
        
        $languages = $thismodel->paginate($limit);
        return view('backend.languages.index', compact('languages', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.languages.create');
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
            'language_name' => ['required'],
            'language_code' => ['required'],
            'flag_icon' => ['nullable'],
            'system_language_status' => ['nullable'],
            'tongue_language_status' => ['nullable'],
        ]);

        if (!empty($attributes['flag_icon'])) {
            $imgKey     = 'flag_icon';
            $imgPath    = public_path(config('constants.language_image_path'));
            $filename   = UploadImage($request, $imgPath, $imgKey);
            if(!empty($filename)){
                $attributes['flag_icon'] = $filename;
            }
        }

        Language::create($attributes);
        return redirect()->route('admin.administration.languages.index')
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
    public function edit(Language $language)
    {
        return view('backend.languages.edit', compact('language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Language $language)
    {
        $attributes = request()->validate([
            'language_name' => ['required'],
            'language_code' => ['required'],
            'flag_icon' => ['nullable'],
            'system_language_status' => ['nullable'],
            'tongue_language_status' => ['nullable'],
        ]);

        // pr($attributes);die;

        if (!empty($attributes['flag_icon'])) {
            $imgKey     = 'flag_icon';
            $imgPath    = public_path(config('constants.language_image_path'));
            $filename   = UploadImage($request, $imgPath, $imgKey);
            if(!empty($filename)){
                $attributes['flag_icon'] = $filename;
            }
        }

        $language->update($attributes);
        return redirect()->route('admin.administration.languages.index')
            ->with('success', __('Language created successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $language)
    {
        $language->delete();
        return redirect()->route('admin.administration.languages.index')
            ->with('success', __('Language deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        Language::find($request->id)->update(['status' => $request->status]);
        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
