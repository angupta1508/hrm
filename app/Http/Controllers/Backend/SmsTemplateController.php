<?php

namespace App\Http\Controllers\Backend;

use PDF;
use Illuminate\Support\Facades\Config;
// use Barryvdh\DomPDF\PDF;
use App\Exports\ExportUser;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class SmsTemplateController extends Controller
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
        
        $thismodel = SmsTemplate::sortable(['created_at' => 'DESC']);
        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('status', $filter['status']);
        }
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('sms_templates.title', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Email Templates Title", "Templates Code", "Content",
                "Status", "Created", "Updated",
            ];
            $thismodel->select([
                'sms_templates.title', 'sms_templates.template_code', 'sms_templates.content',
                'sms_templates.status', 'sms_templates.created_at', 'sms_templates.updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'SMS templates List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'sms templates.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'SMS Template List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,
                ];

                $file = 'sms_templates.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);
                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }
                return $pdf->download($file);
            }
        } 
        
        $templates = $thismodel->paginate($limit);
        return view('backend.smstemplates.index', compact('templates', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.smstemplates.create');
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
            'title' => ['required'],
            'content' => ['required'],
            'template_code' => ['nullable'],
        ]);
        $template_code = str_replace(' ', '-', strtolower($attributes["title"]));
        $attributes["template_code"]    =    $template_code;
        SmsTemplate::create($attributes);
        return redirect()->route('admin.sms-template.index')
            ->with('success', __('Template created successfully.'));
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
        $templates  =   SmsTemplate::where('id', $id)->first();
        return view('backend.smstemplates.edit', compact('templates'));
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
        $attributes = request()->validate([
            'title' => ['required'],
            'content' => ['required'],
        ]);
        SmsTemplate::where('id', $id)->update($attributes);
        return redirect()->route('admin.sms-template.index')
            ->with('success', __('Template updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SmsTemplate::where('id', $id)->delete();

        return redirect()->route('admin.sms-template.index')
            ->with('success', __('Template deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        SmsTemplate::find($request->id)->update(['status' => $request->status]);

        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
