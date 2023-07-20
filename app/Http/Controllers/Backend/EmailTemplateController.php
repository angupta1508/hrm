<?php

namespace App\Http\Controllers\Backend;

use PDF;
// use Barryvdh\DomPDF\PDF;
use App\Models\Astrologer;
use App\Exports\ExportUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Models\EmailTemplates;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;


class EmailTemplateController extends Controller
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

        $thismodel = EmailTemplates::sortable(['created_at' => 'DESC']);
        if (isset($filter['status']) && $filter['status'] != "") {
            $thismodel->where('status', $filter['status']);
        }
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('email_templates.title', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Email Templates Title","Templates Code", "Content",
                "Status", "Created", "Updated",
            ];
            $thismodel->select([
                'email_templates.title', 'email_templates.template_code', 'email_templates.content',
                'email_templates.status', 'email_templates.created_at', 'email_templates.updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Email Templates List'
            ];
            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings,$header), 'email_templates.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'email_templates List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,    
                ];

                $file = 'email_templates.pdf';
                $pdf =  PDF::loadview('pdf', $variabls);

                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }

                return $pdf->download($file);
            }
        } 

        $templates = $thismodel->paginate($limit);
        return view('backend.emailtemplates.index', compact('templates', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.emailtemplates.create');
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
        EmailTemplates::create($attributes);
        return redirect()->route('admin.email-template.index')
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
        $templates  =   EmailTemplates::where('id', $id)->first();
        return view('backend.emailtemplates.edit', compact('templates'));
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
        EmailTemplates::where('id', $id)->update($attributes);
        return redirect()->route('admin.email-template.index')
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
        EmailTemplates::where('id', $id)->delete();

        return redirect()->route('admin.email-template.index')
            ->with('success', __('Template deleted successfully.'));
    }

    public function changeStatus(Request $request)
    {
        EmailTemplates::find($request->id)->update(['status' => $request->status]);

        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
