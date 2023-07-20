@extends('layouts.admin.app')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">{{__('All Package')}}</h5>
                        </div>
                        <a href="{{ route('admin.package.create') }}" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; {{__('Add New User')}}</a>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{__('S. NO.')}}	
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{__('Package Id')}}
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        {{__('Name')}}
                                    </th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                       {{__('Price')}}
                                    </th>
                                 
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                         {{__('Duration')}}
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
                                @foreach ($package as $pack)
                                <tr>
                                    <td class="text-center" style="width:50px;">
                                        <p class="text-xs font-weight-bold mb-0">{{ ++$i }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $pack->package_uni_id }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $pack->name }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $pack->price }}</p>
                                    </td>
                                    <td class="text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $pack->duration }} days</p>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block">
                                            <input class="form-check-input updateStatus" type="checkbox" role="switch" {{($pack->status) ? 'checked' : ''}}
                                            data-id="{{ $pack->id }}">
                                        </div>
                                    </td> 
                                    
                                    <td class="text-center">
                                        <form action="{{ route('admin.package.destroy', $pack->id) }}" method="Post">
                                            <a class="btn btn-info btn-sm text-white" href="{{ route('admin.package.modulepermission', $pack->id) }}" title="Permission"><i class="fas fa-lock"></i></a>
                                            <a class="btn btn-warning btn-sm text-white" href="{{ route('admin.package.edit', $pack->id) }}"><i class="fas fa-edit"></i></a>
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-danger btn-sm text-white delete_confirm"
                                                    title="Detele" data-toggle="tooltip"><i
                                                        class="cursor-pointer fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                       
                    </div>
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
                        url: "{{ route('admin.package.changeStatus') }}",
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
                                title: "{{ __('Oops!') }}",
                                text: "{{ __('Something went wrong. Please try again.') }}",
                                icon: 'error',

                            })
                        }

                    });
                })
            })
        </script>
    @endpush
@endsection



