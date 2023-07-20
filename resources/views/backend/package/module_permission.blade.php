@extends('layouts.admin.app')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="card card-default color-palette-bo">
                <div class="card-header">
                    <div class="d-inline-block">
                        <h6 class="font-weight-bolder mb-0 text-capitalize"> <i class="fa fa-edit"></i>
                            &nbsp; {{__('Modules Permissions')}}</h6>
                    </div>
                    <div class="d-inline-block float-right font-weight-bolder mb-0 text-capitalize" style="float: right;">
                        <a href="#" onclick="window.history.go(-1); return false;"
                            class="btn btn-primary pull-right"><i class="fa fa-reply mr5"></i> <?= trans('back') ?></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <h6 class="font-weight-bolder mb-0 text-capitalize">
                            <span class="mr5">{{__('Permission Access')}} : </span>
                            {{__('Modules')}}
                        </h6>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <label for="description" class="form-label">{{__('Select Module')}}</label>
                            <div class="row form-group module-in-package">
                                @foreach ($pack_modules as $module)
                                    <div class="col-md-4 pb-3">
                                        <span class="pull-left form-check form-switch d-inline-block"
                                            style="position: relative; top: 10px;">
                                            <input type='checkbox' name="module[{{ $module->id }}]"
                                                class='form-check-input updateStatus' value="{{ $module->id }}"
                                                <?php if (in_array($module->id, $pack_sel)) {
                                                    echo 'checked="checked"';
                                                } ?> data-package_id={{ $package->package_uni_id }} />
                                        </span>
                                        <span class="mt-15 pl-3">
                                            <?= __(ucwords(str_replace('-',' ',$module->module_name))) ?>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
    @push('dashboard')
        <script>
            $(document).ready(function() {
                $('.updateStatus').on('change', function() {
                    let ele = $(this);
                    var status = $(this).prop('checked') == true ? 1 : 0;
                    var package_id = $(this).data('package_id');
                    var module_id = $(this).val();
                    $.ajax({
                        url: "{{ route('admin.package.moduleUpdate') }}",
                        type: 'post',
                        data: {
                            package_id: package_id,
                            module_id: module_id,
                            status: status,
                        },
                        success: function(result) {
                            if (result.status == 1) {
                                toastr.success('Module Permission Updated');
                            } else {
                                toastr.error('Something went Wrong');
                            }
                        }
                    });
                })
            })
        </script>
    @endpush
@endsection
