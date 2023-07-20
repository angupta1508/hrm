@extends('layouts.admin.app')

@section('content')
    <div class="card card-default color-palette-bo">
        <div class="card-header">
            <div class="d-inline-block">
                <h2 class="font-weight-bolder mb-0 ">
                    <i class="fas fa-lock"></i>
                    {{ __($role->name) }} {{__('Permission Access')}}
                </h2>
            </div>
            <div class="d-inline-block float-right font-weight-bolder mb-0 text-capitalize" style="float: right;">
                <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-primary pull-right mb-0">
                    <i class="fa fa-reply mr5"></i> <?= trans('back') ?>
                </a>
            </div>
        </div>
        <div class="card-body text-sm">
            @foreach ($modules as $kk => $module)
                <div class="row">               
                    <div class="col-md-3">
                        <h6 class="font-weight-bolder mb-0">
                            <strong class="f-16">{{__(ucwords($module['module_name'])) }}</strong>
                        </h6>
                    </div>
                    <div class="col-md-9">
                        <div class="row mb-3">
                            @foreach ($module['operations'] as $k => $operation)
                                <div class="col-md-4 pb-3">
                                    <span class="pull-left form-check form-switch d-inline-block">
                                        <input type='checkbox' class='form-check-input updateStatus'
                                            data-module_id='<?= $module['id'] ?>'
                                        data-operation='<?= $operation['name'] ?>'
                                        data-role_id='<?= $role->id ?>'
                                        id='cb_<?= $kk . $k ?>'
                                        {{ !empty($operation['status']) ? 'checked' : '' }}
                                        />
                                        <label class='tgl-btn' for='cb_<?= $kk . $k ?>'></label>
                                    </span>
                                    <span class="mt-15 pl-3">
                                        {{  __(ucwords($operation['name'])) }} 
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
    @push('dashboard')
        <script>
            $(document).ready(function() {
                $('.updateStatus').on('change', function() {
                    let ele = $(this);
                    let _token = $('meta[name="csrf-token"]').attr('content');
                    var status = $(this).prop('checked') == true ? 1 : 0;
                    var operation = $(this).data('operation');
                    var role_id = $(this).data('role_id');
                    var module_id = $(this).data('module_id');
                    $.ajax({
                        url: "{{ route('admin.roles.permissionStore') }}",
                        type: 'post',
                        data: {
                            _token: _token,
                            operation: operation,
                            role_id: role_id,
                            module_id: module_id,
                            status: status,
                        },
                        success: function(result) {
                            if (result.status) {
                                toastr.success(result.msg)
                            } else {
                                toastr.error(result.msg)
                            }
                        }

                    });
                })
            })
        </script>
    @endpush
@endsection
