<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Support\Facades\Config;
use App\Exports\ExportUser;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use App\Models\UnregisteredUser;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $LoggedUser = Auth::user();
        $limit = config('constants.pagination_page_limit');
        $filter = $request->query();
        $getroles = Role::where('roles.id', '>', config('constants.admin_role_id'))->orderBy('id', 'ASC')->get();
        $thismodel = Notification::sortable(['created_at' => 'desc']);
        if (!joined($thismodel, 'roles')) {
            $thismodel->leftJoin('roles', function ($join) {
                $join->on('notifications.role_id', '=', 'roles.id');
            });
        }

        $thismodel->where('notifications.admin_id', $LoggedUser->admin_id);
        $keyword = '';
        if (!empty($filter['roles_name'])) {
            $thismodel->where('roles.name', $filter['roles_name']);
        }
        if (!empty($filter['search'])) {
            $keyword = $filter['search'];
            $thismodel->where(function ($query) use ($keyword) {
                $query->where('notifications.title', 'LIKE', '%' . $keyword . '%');
            });
        }
        if (isset($filter['excel_export']) || isset($filter['pdf_export'])) {
            $headings = [
                "Role Name", "Title", "Description", "Created", "Updated",
            ];
            $thismodel->select([
                'roles.name', 'notifications.title', 'notifications.description',
                'notifications.created_at', 'notifications.updated_at',
            ]);
            $records = $thismodel->get();

            $header = [
                'Company Name'  =>  Config::get('company_name'),
                'File'        =>  'Notifications List'
            ];

            if (isset($filter['excel_export'])) {
                return Excel::download(new ExportUser($records, $headings, $header), 'notifications.csv');
            } else if (isset($filter['pdf_export'])) {
                $tabel_keys = [];
                if ($records->count() > 0) {
                    $tabel_keys = array_keys($records[0]->toArray());
                }

                $variabls = [
                    'top_heading' => 'Notification List',
                    'headings' => $headings,
                    'tabel_keys' => $tabel_keys,
                    'records' => $records,
                    'header' => $header,
                ];

                $file = 'notifications.pdf';

                $pdf = PDF::loadview('pdf', $variabls);
                if (count($headings) > 6) {
                    $pdf->setPaper('a4', 'landscape');
                }
                return $pdf->download($file);
            }
        } else {
            $thismodel->select([
                'notifications.*', 'roles.name',
            ]);
        }

        $notifications = $thismodel->paginate($limit);
        // dd(getQueryWithBindings($thismodel));

        return view('backend.notifications.index', compact('notifications', 'getroles', 'filter'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $role = Role::where('roles.id', '>', config('constants.admin_role_id'))->orderBy('id', 'ASC')->get();

        // dd($unregistered);

        return view('backend.notifications.create', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $LoggedUser = Auth::user();

        $attributes = request()->validate([
            'admin_id' => ['nullable'],
            'role_id' => ['nullable'],
            'title' => ['required'],
            'image' => ['nullable'],
            'description' => ['required'],
        ]);
        $imgPath = '';
        if (!empty($attributes['image'])) {
            $imgKey     =   'image';
            $imgPath    =   public_path(config('constants.notification_image_path'));
            $filename = UploadImage($request, $imgPath, $imgKey);
            if (!empty($filename)) {
                $attributes['image'] = $filename;
            }
        }
        $attributes['admin_id'] = $LoggedUser->admin_id;
        $role_id = $attributes['role_id'];
        $title = $attributes['title'];
        $image = !empty($attributes['image']) ? $attributes['image'] : '';
        $message = $attributes['description'];


        $user = User::where([['status', 1], ['trash', 0], ['role_id', $role_id], ['admin_id', $LoggedUser->admin_id]])->WhereNotNull('user_fcm_token')->get();


        $registatoin_ids = array();
        $registatoinIos_ids = array();
        foreach ($user as $regs) {
            if (!empty($regs['user_fcm_token'])) {
                $registatoin_ids[] = $regs['user_fcm_token'];
            }
        }

        $imageUrl = '';
        if (!empty($image) && file_exists($imgPath . $image)) {
            $imageUrl = url(config('constants.notification_image_path') . $image);
        }

        $arry = [];
        $arry = [
            'title' => $title,
            'image' => $imageUrl,
            'description' => $message,
            'token' => '',
            'channelName' => '',
            'user_uni_id' => '',
            'start_tiame' => '',
            'duration' => '',
            'ctype' => '',
            'start_time' => '',
            'duration' => ''
        ];
        if (!empty($registatoin_ids)) {
            $chunk_array = array_chunk($registatoin_ids, 1000);
            foreach ($chunk_array as $key => $value) {
                // dd($val);
                $arry['type'] = 'android';
                $arry['chunk'] = $value;
                $result = pushNotification($arry);
            }
        }

        // dd($result);

        $notification = Notification::create($attributes);
        if ($user->count() > 0) {
            foreach ($user as $key => $value) {
                $array = [];
                $array = [
                    'admin_id' => $value->admin_id,
                    'user_id' => $value->id,
                    'role_id' => $value->role_id,
                    'title' => $title,
                    'image' => $image,
                    'description' => $message,
                    'status' => 0
                ];
                UserNotification::create($array);
            }
        }

        return redirect()->route('admin.cms.notifications.index')
            ->with('success', 'Notification created successfully.');
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
        $notification = Notification::find($id);

        $role = Role::where('roles.id', '!=', config('constants.superadmin_role_id'))->where('roles.id', '!=', config('constants.admin_role_id'))->orderBy('id', 'ASC')->get();
        return view('backend.notifications.edit', compact('notification', 'role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        //
        $attributes = request()->validate([
            'role_id' => ['nullable'],
            'title' => ['required'],
            'image' => ['nullable'],
            'description' => ['required'],

        ]);
        if (!empty($attributes['image'])) {
            $img = 'image';
            $imgPath = public_path(config('constants.notification_image_path'));
            $img_path = $imgPath . $notification->image;

            $filename = UploadImage($request, $imgPath, $img);
            $attributes['image'] = $filename;
        }

        // Blog::where('id',$id)->update($attributes);
        $notification->update($attributes);

        return redirect()->route('admin.cms.notifications.index')
            ->with('success', 'Notification created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Notification::where('id', $id)->delete();

        return redirect()->route('admin.cms.notifications.index')
            ->with('success', 'notifications deleted successfully');
    }

    public function changeStatus(Request $request)
    {
        //        pr($request);die;
        $page = Notification::find($request->id)->update(['status' => $request->status]);

        return response()->json(['success' => 'Status changed successfully.']);
    }
    public function resendNotifiction(Request $request)
    {

        $notification = Notification::where('id', $request->id)->first();

        $user = User::where([['status', 1], ['trash', 0], ['role_id', $notification->role_id]])->WhereNotNull('user_fcm_token')->get();

        $imgPath = public_path(config('constants.notification_image_path'));

        $registatoin_ids = array();
        foreach ($user as $regs) {
            if (!empty($regs['user_fcm_token'])) {
                $registatoin_ids[] = $regs['user_fcm_token'];
            }
        }

        $imageUrl = '';
        if (!empty($notification->image) && file_exists($imgPath . $notification->image)) {
            $imageUrl = url(config('constants.notification_image_path') . $notification->image);
        }

        $arry = [];
        $arry = ['title' => $notification->title, 'image' => $imageUrl, 'description' => $notification->description, 'token' => '', 'channelName' => '', 'user_uni_id' => '', 'start_tiame' => '', 'duration' => '', 'ctype' => '', 'start_time' => '', 'duration' => ''];
        if (!empty($registatoin_ids)) {
            $chunk_array = array_chunk($registatoin_ids, 1000);
            foreach ($chunk_array as $key => $value) {
                // dd($val);
                $arry['type'] = 'android';
                $arry['chunk'] = $value;
                $result = pushNotification($arry);
            }
        }

        foreach ($user as $key => $value) {
            $array = [];
            $array = ['user_uni_id' => $value->user_uni_id, 'image' => $notification->image, 'msg' => $notification->description, 'status' => 0];
            UserNotification::create($array);
        }
        $result = array(
            'status' => 1,
            'msg' => "Successfully Resend",
        );
        return response()->json($result);
    }

    // public function tokenUpdate(Request $request)
    // {
    //     //  dd($request);   
    //     $data = session()->get('userdetail');

    //     // dd($data);
    //     if (!empty($data)) {
    //         $token = User::where('user_uni_id', $data->user_uni_id)->update(['user_fcm_token' => $request->token]);
    //         // dd($token);
    //     } else {

    //         $demo = UnregisteredUser::where('device_id', $request->userAgent)->first();

    //         // pr(getQueryWithBindings($demo));die;
    //         // dd($demo);
    //         if (!empty($demo)) {
    //             $demo->update(['device_id' => $request->userAgent, 'user_fcm_token' => $request->token]);
    //         } else {
    //             $register = [
    //                 'device_id' => $request->userAgent,
    //                 'user_fcm_token' => $request->token
    //             ];
    //             $UnregisteredUser = UnregisteredUser::create($register);
    //         }
    //     }
    // }
}
