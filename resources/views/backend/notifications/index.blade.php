@extends('layouts.admin.app')
@section('content')
<div>
    <div class="row">
        <div class="col-12">
            <div class="mb-4 mx-4">
                <div class="card filter_card" style="margin-bottom:20px;">
                    <div class="card-header">
                        <i class="fa fa-filter"></i> {{__('Filter')}}
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.cms.notifications.index') }}" method="GET">
                            @csrf
                            <div class="border">
                                <div class="d-flex flex-row align-content-between flex-wrap">

                                    <div class="p-2">
                                        <select class="form-select select2" name="roles_name">
                                            <option value="">{{__('Please Select Role Name')}}</option>
                                            @foreach ($getroles as $getrole)
                                            <option value="{{ !empty($getrole->name) ? $getrole->name : '' }}"
                                                {{ old('roles_name', !empty($filter['roles_name']) ? $filter['roles_name'] : '') == $getrole->name ? 'selected' : '' }}>
                                                {{ $getrole->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="p-2 flex-fill">
                                        <input type="text" id="search" name="search" class="form-control"
                                            value="{{ !empty($filter['search']) ? $filter['search'] : '' }}"
                                            autocomplete="off" placeholder="{{__('Search By Title')}}">
                                    </div>
                                    <div class="p-2">
                                        <button type="submit" class="btn btn-primary shadow-primary mb-0 button"
                                            name="submit" data-toggle="tooltip" data-placement="top" title="Filter">{{__('Filter')}}</button>
                                        <button type="submit" name="excel_export" value="Export"
                                            class="btn btn-primary excel_export shadow-primary mb-0 button"
                                            name="submit" data-toggle="tooltip" data-placement="top" title="Export"><i class="fas fa-file-excel"></i> {{__('Export')}}</button>
                                        <button type="submit" name="pdf_export" value="Pdf"
                                            class="btn btn-primary pdf_export shadow-primary mb-0 button"
                                            name="submit" data-toggle="tooltip" data-placement="top" title="PDF"><i class="fas fa-file-pdf"></i> {{__('Pdf')}}</button>
                                  </div>

                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                            <h5 class="mb-0">{{__('All Notification')}}</h5>
                        <a href="{{ route('admin.cms.notifications.create') }}"
                            class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; {{__('Add New Notification')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                       {{__('S. No.')}}
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                        @sortablelink('roles.name', __('Role Name'))
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                        @sortablelink('title', __('Title'))
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                        @sortablelink('image', __('Images'))
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                        @sortablelink('description', __('Description'))
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                       {{__('Action')}}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody>
                                @foreach ($notifications as $notification)
                                <tr>
                                    <td class="text-center" style="width:50px;">
                                        <p class="text-xs font-weight-bold mb-0">{{ ++$i }}</p>
                                    </td>
                                    <td class="">
                                        <p class="text-xs font-weight-bold mb-0">{{ $notification->name }}</p>
                                    </td>
                                    <td class="">
                                        <p class="text-xs font-weight-bold mb-0">{{ $notification->title }}</p>
                                    </td>
                                    <td class="">
                                        <div class="avatar">
                                            <img class="w-100 border-radius-sm shadow-sm" src="{{ ImageShow(config('constants.notification_image_path'), $notification->image,'icon',
                                                config('constants.default_image_path')) }}">
                                        </div>
                                    </td>
                                    <td class="">
                                        <p class="text-xs font-weight-bold mb-0">
                                            {!! wordlimit($notification->description) !!}</p>
                                    </td>


                                    <td class="text-center">
                                        <form
                                            action="{{ route('admin.cms.notifications.destroy', $notification->id) }}"
                                            method="Post">
                                            <a class="btn resend_confirm btn-info btn-sm text-white"
                                                data-id="{{ $notification->id }}" data-bs-toggle="modal"
                                                data-bs-whatever="@mdo">
                                                <i class="fas fa-redo-alt"></i>
                                             </a>
                                            <a class="btn btn-warning btn-sm text-white"
                                                href="{{ route('admin.cms.notifications.edit', $notification->id) }}"><i
                                                    class="fas fa-edit"></i></a>

                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-danger btn-sm text-white delete_confirm" title="Delete"
                                                data-toggle="tooltip"><i
                                                    class="cursor-pointer fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            </tbody>
                        </table>
                    </div>

                    {{ $notifications->appends($filter)->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>



@push('dashboard')
<script>
$(document).ready(function() {
    $('.updateStatus').on('change', function() {
        let ele = $(this);
        let _token = $('meta[name="csrf-token"]').attr('content');
        var status = $(this).prop('checked') == true ? 1 : 0;
        var id = $(this).data('id');
        //
        $.ajax({
            url: "{{ route('admin.cms.notifications.changeStatus') }}",
            type: 'post',
            data: {
                _token: _token,
                id: id,
                status: status,
            },
            success: function(result) {
                if (result.success) {
                    Swal.fire({
                        title: "{{__('Success!')}}",
                        text: result.success,
                        icon: 'success',

                    })
                } else {
                    ele.prop('checked', !status);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                ele.prop('checked', !status);
                Swal.fire({
                    title: 'Oops!',
                    text: 'Something went wrong. Please try again.',
                    icon: 'error',

                })
            }

        });
    })


})
</script>
@endpush
@endsection
