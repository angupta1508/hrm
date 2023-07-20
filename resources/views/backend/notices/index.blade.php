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
                        <form action="{{ route('admin.cms.notices.index') }}" method="GET">
                            @csrf
                            <div class="border">
                                <div class="d-flex flex-row align-content-between flex-wrap">

                                    <div class="p-2 flex-fill">
                                        <input type="text" id="search" name="search" class="form-control" value="{{ !empty($filter['search']) ? $filter['search'] : '' }}" autocomplete="off" placeholder="{{__('Search By Notice Name and Type')}}">
                                    </div>
                                    <div class="p-2 flex-fill">
                                        <select class="form-select" id="status" name="status">
                                            <option value="">{{__('Status')}}</option>
                                            <option value="1" {{ isset($filter['status']) && $filter['status'] == 1 ? 'selected' : '' }}>
                                                {{__('Active')}}
                                            </option>
                                            <option value="0" {{ isset($filter['status']) && $filter['status'] == 0 ? 'selected' : '' }}>
                                                {{__('Inactive')}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="p-2">
                                        <button type="submit" class="btn btn-primary shadow-primary mb-0 button" name="submit" data-toggle="tooltip" data-placement="top" title="{{__('Filter')}}">{{__('Filter')}}</button>
                                        <button type="submit" name="excel_export" value="Export" class="btn btn-primary excel_export shadow-primary mb-0 button" name="submit" data-toggle="tooltip" data-placement="top" title="{{__('Export')}}"><i class="fas fa-file-excel"></i> {{__('Export')}}</button>
                                        <button type="submit" name="pdf_export" value="Pdf" class="btn btn-primary pdf_export shadow-primary mb-0 button" name="submit" data-toggle="tooltip" data-placement="top" title="{{__('PDF')}}"><i class="fas fa-file-pdf"></i> {{__('Pdf')}}</button>
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
                        <h5 class="mb-0">{{__('All Notice')}}</h5>
                        <a href="{{ route('admin.cms.notices.create') }}" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; {{__('Add New notice')}}</a>
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

                                        @sortablelink('title', __('Title'))
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                        @sortablelink('type', __('Type'))
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                        @sortablelink('attachment', __('Attachment'))
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                        @sortablelink('description', __('Description'))
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{__('Status')}}
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{__('Action')}}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody>
                                @foreach ($notices as $notice)
                                <tr>
                                    <td class="text-center" style="width:50px;">
                                        <p class="text-xs font-weight-bold mb-0">{{ ++$i }}</p>
                                    </td>
                                    <td class="">
                                        <p class="text-xs font-weight-bold mb-0">{{ $notice->title }}</p>
                                    </td>
                                    <td class="">
                                        <p class="text-xs font-weight-bold mb-0">{{ $notice->type }}</p>
                                    </td>
                                    <td class="">
                                        <a href="{{route('admin.cms.notices.downloadimage', $notice->id)}}" alt="image">{{ $notice->attachment }}</a>
                                    </td>


                                    <td class="">
                                        <p class="text-xs font-weight-bold mb-0">
                                            {!! wordlimit($notice->description) !!}
                                        </p>
                                    </td>

                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input updateStatus" type="checkbox" role="switch" {{ $notice->status ? 'checked' : '' }} data-id="{{ $notice->id }}">
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.cms.notices.destroy', $notice->id) }}" method="Post">
                                            <a class="btn btn-warning btn-sm text-white" href="{{ route('admin.cms.notices.edit', $notice->id) }}"><i class="fas fa-edit"></i></a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm text-white delete_confirm" title="Delete" data-toggle="tooltip"><i class="cursor-pointer fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            </tbody>
                        </table>
                    </div>

                    {{ $notices->appends($filter)->links('pagination::bootstrap-5') }}
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
                url: "{{ route('admin.cms.notices.changeStatus') }}",
                type: 'post',
                data: {
                    _token: _token,
                    id: id,
                    status: status,
                },
                success: function(result) {
                    if (result.success) {
                        Swal.fire({
                            title: 'Success!',
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