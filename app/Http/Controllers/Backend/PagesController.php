<?php

namespace App\Http\Controllers\Backend;

use PDF;
use App\Models\Page;
use App\Models\Language;
use App\Exports\ExportUser;
use App\Models\LanguagePage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;
use Cviebrock\EloquentSluggable\Services\SlugService;

class PagesController extends Controller
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
        $local = session()->get('locale');

        $thismodel = Page::sortable(['created_at' => 'DESC']);
        if (!joined($thismodel, 'language_pages')) {
            $thismodel->leftJoin('language_pages', function ($join) {
                $join->on('language_pages.page_id', '=', 'pages.id');
            });
        }

        if (!joined($thismodel, 'languages')) {
            $thismodel->leftJoin('languages', function ($join) {
                $join->on('language_pages.language_id', '=', 'languages.id');
            });
        }

        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('pages.status', $filter['status']);
        }

        // $thismodel->where('languages.language_code', $local);

        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('pages.page_slug', 'LIKE', '%' . $keyword . '%');
            });
        }

        $thismodel->select([
            'pages.*', 'language_pages.page_name', 'language_pages.page_content', 'language_pages.page_meta_title'
        ]);

        $thismodel->groupBy('pages.id');

        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Page Name", "Page Slug", "Page Images",
                "Page Description", "Page Meta Key",
                "Page Meta Title", "Page Meta Description",
                "Status", "Created", "Updated",
            ];

            $thismodel->select([
                'pages.page_slug', 'pages.page_images',
                 'pages.page_meta_key',
                'pages.page_meta_description',
                'pages.status', 'pages.created_at', 'pages.updated_at',
            ]);

            $records = $thismodel->get();
            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Pages List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'pages.csv');
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

                $file=  'pages.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        }

        // dd(getQueryWithBindings($thismodel));
        $pages = $thismodel->paginate($limit);
        return view('backend.pages.index', compact('pages', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $language_list = getLanguages(['status' => 1, 'system_language_status' => 1])->pluck('language_name', 'id');
        return view('backend.pages.create', compact('language_list'));
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
            'page_name' => ['required'],
            'page_content' => ['required'],
            'page_meta_key' => ['nullable'],
            'page_meta_title' => ['required'],
            'page_meta_description' => ['nullable'],
        ]);

        $language_code = config('constants.default_company_lang');
        $language_id = getLanguageIdByCode($language_code);
        $attributes["page_slug"] = SlugService::createSlug(Page::class, 'page_slug', $attributes['page_name'][$language_id]);

        $page = Page::create($attributes);
        foreach ($attributes['page_name'] as $language_id => $value) {
            $saveData = [
                'page_id' => $page->id,
                'language_id' => $language_id,
                'page_name' => !empty($attributes['page_name'][$language_id])? $attributes['page_name'][$language_id] : '',
                'page_content' => !empty($attributes['page_content'][$language_id])? $attributes['page_content'][$language_id] : '',
                'page_meta_title' => !empty($attributes['page_meta_title'][$language_id])? $attributes['page_meta_title'][$language_id] : '',
            ];

            $langPage = LanguagePage::where('language_id', $language_id)->where('page_id', $page->id)->first(['language_pages.*']);
            if(!empty($langPage)){
                $langPage->update($saveData);
            }
            else{
                LanguagePage::create($saveData);
            }
        }

        return redirect()->route('admin.cms.pages.index')
            ->with('success', __('Pages created successfully.'));
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
    public function edit(Page $page)
    {
        $local = session()->get('locale');
        $language_pages = LanguagePage::where('language_pages.page_id', $page->id)->get()->keyBy('language_id');

        $language_list = getLanguages(['status' => 1, 'system_language_status' => 1])->pluck('language_name', 'id');
        return view('backend.pages.edit', compact('page', 'language_pages', 'language_list'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        //
        $attributes = request()->validate([
            'page_name' => ['required'],
            'page_content' => ['required'],
            'page_meta_key' => ['nullable'],
            'page_meta_title' => ['required'],
            'page_meta_description' => ['nullable'],
        ]);
        
        $language_code = config('constants.default_company_lang');
        $language_id = getLanguageIdByCode($language_code);
        $att['language_id'] = $language_id;
        $att['page_id'] = $page->id;
        $att['language_code'] = $language_code;
        $languagePages = getLanguagePage($att);
        
        if (!empty($attributes['page_name'][$language_id]) && $attributes['page_name'][$language_id] != $languagePages->page_name) {
            $attributes["page_slug"] = SlugService::createSlug(Page::class, 'page_slug', $attributes['page_name'][$language_id]);
        }

        // pr($attributes);die;
        $page->update($attributes);

        foreach ($attributes['page_name'] as $language_id => $value) {
            $saveData = [
                'page_id' => $page->id,
                'language_id' => $language_id,
                'page_name' => !empty($attributes['page_name'][$language_id])? $attributes['page_name'][$language_id] : '',
                'page_content' => !empty($attributes['page_content'][$language_id])? $attributes['page_content'][$language_id] : '',
                'page_meta_title' => !empty($attributes['page_meta_title'][$language_id])? $attributes['page_meta_title'][$language_id] : '',
            ];
            // pr($saveData);die;
            $langPage = LanguagePage::where('language_id', $language_id)->where('page_id', $page->id)->first(['language_pages.*']);
            if(!empty($langPage)){
                $langPage->update($saveData);
            }
            else{
                LanguagePage::create($saveData);
            }
        }

        return redirect()->route('admin.cms.pages.index')
            ->with('success', __('Pages updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.cms.pages.index')
            ->with('success', __('Pages deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        //        pr($request);die;
        $page = Page::find($request->id)->update(['status' => $request->status]);

        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
