@extends('layouts.admin.app')
@section('content')
    <div class="accordion card filter_card mb-4" id="accordionFilter">
        <div class="accordion-item mb-3">
            <h5 class="accordion-header card-header p-3" id="headingFilter">
                <button class="accordion-button p-0 font-weight-bold collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                    <i class="fa fa-filter"></i> {{ __('Filter') }}
                    <i class="collapse-close fa fa-plus pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
                    <i class="collapse-open fa fa-minus pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
                </button>
            </h5>
            <div id="collapseFilter" class="accordion-collapse collapse @if (!empty($filter)) show @endif"
                aria-labelledby="headingFilter" data-bs-parent="#accordionFilter">
                <div class="accordion-body card-body p-3 text-sm opacity-8">
                    <form action="{{ route('admin.user-bankers.index') }}" method="GET">
                        @csrf
                        <div class="border">
                            <div class="d-flex flex-row align-content-between flex-wrap">
                                <!-- <div class="p-2 flex-fill">
                                    <input type="text" id="search" name="search" class="form-control"
                                        value="{{ !empty($filter['search']) ? $filter['search'] : '' }}"
                                        autFfffocomplete="off" placeholder="{{ __('Search By User Name') }}">
                                </div> -->
                                <div class="p-2 flex-fill">
                        <div class="">
                            {{ Form::select('user_id', ['' => __('Select Employee')] + $user_list, old('user_id',!empty($filter['user_id']) ? $filter['user_id'] : ''),  ['class' => 'form-select select2']) }}
                            @error('user_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                                <div class="p-2 flex-fill">
                                    <input type="text" id="account_no" name="account_no" class="form-control"
                                        value="{{ !empty($filter['account_no']) ? $filter['account_no'] : '' }}" autocomplete="off"
                                        placeholder="{{ __('Search By Account Number') }}">
                                </div> <div class="p-2 flex-fill">
                                    <input type="text" id="ifsc_code" name="ifsc_code" class="form-control"
                                        value="{{ !empty($filter['ifsc_code']) ? $filter['ifsc_code'] : '' }}" autocomplete="off"
                                        placeholder="{{ __('Search By Ifsc Code') }}">
                                </div>
                                <div class="p-2 flex-fill">
                                    {{ Form::select('status', ['' => __('Select Status')] + getListTranslate(config('constants.default_status')), isset($filter['status']) ? $filter['status'] : '', ['class' => 'form-select']) }}
                                </div>
                                <div class="p-2">
                                    <button type="submit" name="submit"
                                        class="btn btn-primary shadow-primary mb-0 button">
                                        {{ __('Filter') }}
                                    </button>
                                    <button type="submit" name="excel_export"
                                        class="btn btn-primary excel_export shadow-primary mb-0 button"
                                        value="{{ __('Export') }}">
                                        <i class="fas fa-file-excel"></i>
                                        {{ __('Export') }}
                                    </button>
                                    <button type="submit" name="pdf_export" value="{{ __('PDF') }}"
                                        class="btn btn-primary pdf_export shadow-primary mb-0 button">
                                        <i class="fas fa-file-pdf"></i> {{ __('PDF') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header p-3">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">{{ __('User Bankers') }}</h5>
                </div>
                <a href="{{ route('admin.user-bankers.create') }}" class="btn bg-gradient-primary btn-sm mb-0">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    {{ __('Add New User Bankers') }}
                </a>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('id', __('S. No.'))
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('user_id', __('User Name'))
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('bank_id', __('Bank Name'))
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('account_no', __('Account Number'))
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('ifsc_code', __('IFSC Code'))
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('account_name', __('Account Holder Name'))
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('created_at', __('Date'))
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('status', __('Status'))
                            </th>
                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                {{ __('Action') }}
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($user_bankers as $user_banker)
                            <tr>
                                <td class="text-center" style="width:50px;">
                                    <p class="text-xs font-weight-bold mb-0">{{ ++$i }}</p>
                                </td>
                                <td class="">
                                    <p class="text-xs font-weight-bold mb-0 user_info"
                                        data-user_uni_id="{{ $user_banker->user_uni_id }}">
                                        <span class="text-capitalize">{{ $user_banker->name }}</span><br>
                                    </p> 
                                </td>
                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ wordlimit($user_banker->bank_name) }}
                                    </p>
                                </td>
                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $user_banker->account_no }}</p>
                                </td>
                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $user_banker->ifsc_code }}</p>
                                </td>
                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $user_banker->account_name }}</p>
                                </td>
                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $user_banker->created_at }}</p>
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input updateStatus" type="checkbox" role="switch"
                                            {{ $user_banker->status ? 'checked' : '' }} data-id="{{ $user_banker->id }}">
                                    </div>
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('admin.user-bankers.destroy', $user_banker->id) }}"
                                        method="Post">
                                        <a class="btn btn-warning btn-sm text-white" title="{{ __('Edit') }}"
                                            data-toggle="tooltip"
                                            href="{{ route('admin.user-bankers.edit', $user_banker->id) }}"><i
                                                class="fas fa-edit"></i></a>
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm text-white delete_confirm"
                                            title="{{ __('Delete') }}" data-toggle="tooltip"><i
                                                class="cursor-pointer fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $user_bankers->appends($filter)->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection

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
                    url: "{{ route('admin.user-bankers.changeStatus') }}",
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
