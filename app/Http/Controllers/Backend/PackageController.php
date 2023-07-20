<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Package; 
use App\Models\PackageModule;
use App\Models\PackageModulePermission;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::guest()) {
            return redirect()->route('admin.login');
        }
        $LoggedUser = Auth::user();
         $limit =  config('constants.pagination_page_limit');
        $thismodel = Package::where([['trash', 0]])->latest();
        $package = $thismodel->paginate($limit);
        return view('backend.package.index', compact('package'))->with('i', (request()->input('page', 1) - 1) * $limit);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $LoggedUser = Auth::user();
        $pack_modules   =   PackageModule::all();
        $labels = config('constants.package_label');
        // pr(getQueryWithBindings($users));die;
        return view('backend.package.create', compact('pack_modules','labels'));
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
            'name' => ['required'],
            'price' => ['required'],
            'trial_duration' => ['nullable'],
            'duration' => ['required'],
            'module' => ['required'],
            'package_type' => ['required'],
            'label' => ['required'],
            'user_limit' => ['required'],
            'description' => ['nullable'],
        ]);
        $attributes['package_uni_id'] = new_sequence_code('PACk');
        $module =   $attributes['module'];
        unset($attributes['module']);
        $package = Package::create($attributes);
        $module_count = count($module);
        for ($i = 0; $i < $module_count; $i++) {
            $arry = [
                'package_uni_id' => $package->package_uni_id,
                'module_id' => $module[$i],
            ];
            PackageModulePermission::create($arry);
        }
        return redirect()->route('admin.package.index')
            ->with('success', 'Package created successfully.');
    }

    public function modulePermission($id)
    {
        $package =  Package::where('id', $id)->first();
        $LoggedUser = Auth::user();
        $pack_modules   =   PackageModule::all();
        $pack_sel = PackageModulePermission::where([['package_uni_id', $package->package_uni_id], ['status', 1], ['trash', 0]])->get();
        $data = array();
        foreach ($pack_sel as $pack) {
            $data[] = $pack['module_id'];
        }
        $pack_sel = $data;
        return view('backend.package.module_permission', compact('package', 'pack_sel', 'pack_modules'));
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
    public function edit(Package $package)
    {
        $LoggedUser = Auth::user();
        $pack_modules   =   PackageModule::all();
        $labels = config('constants.package_label');
        $pack_sel = PackageModulePermission::where([['package_uni_id', $package->package_uni_id], ['status', 1], ['trash', 0]])->get();
        $data = array();
        foreach ($pack_sel as $pack) {
            $data[] = $pack['module_id'];
        }
        $pack_sel = $data;
        return view('backend.package.edit', compact('package', 'pack_sel', 'pack_modules','labels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $package)
    {
        $attributes = request()->validate([
            'name' => ['required'],
            'price' => ['required'],
            'duration' => ['required'],
            'trial_duration' => ['nullable'],
            'package_type' => ['required'],
            'label' => ['required'],
            'user_limit' => ['required'],
            'description' => ['nullable'],
        ]);
        unset($attributes['module']);
        $package->update($attributes);

        return redirect()->route('admin.package.index')
            ->with('success', 'Updated  successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        //
        $package->delete();

        return redirect()->route('admin.package.index')
            ->with('success', __('Pages deleted successfully.'));
    }


    public function moduleUpdate(Request $request)
    {
        $pack_id = PackageModulePermission::where([['module_id', $request->module_id], ['package_uni_id', $request->package_id]])->first();
        $arry = [
            'module_id' => $request->module_id,
            'package_uni_id' => $request->package_id,
            'status' => $request->status
        ];
        if (!empty($pack_id)) {
            $result =  PackageModulePermission::where([['module_id', $request->module_id], ['package_uni_id', $request->package_id]])->update($arry);
        } else {
            $result =  PackageModulePermission::create($arry);
        }
        if (!empty($result)) {
            $res = ['status' => 1];
        } else {
            $res = ['status' => 0];
        }
        return response()->json($res);
    }

    public function changeStatus(Request $request)
    {
        //        pr($request);die;
        $page = Package::find($request->id)->update(['status' => $request->status]);

        return response()->json(['success' => __('Status changed successfully.')]);
    }
}
