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
                    <form action="{{ route('admin.administration.locations.index') }}" method="GET">
                        @csrf
                        <div class="border">
                            <div class="d-flex flex-row align-content-between flex-wrap">
                                <div class="p-2 flex-fill">
                                    <input type="text" id="search" name="search" class="form-control"
                                        value="{{ !empty($filter['search']) ? $filter['search'] : '' }}" autocomplete="off"
                                        placeholder="{{ __('Search By Location') }}">
                                </div>
                                <div class="p-2 flex-fill">
                                    {{ Form::select('status', ['' => __('Select Status')] + getListTranslate(config('constants.default_status')), isset($filter['status']) ? $filter['status'] : '', ['class' => 'form-select']) }}
                                </div>
                                <div class="p-2 flex-fill">
                                    {{ Form::select('weekly_holiday', ['' => __('Select Weekly Holiday')] + getListTranslate(config('constants.default_weekly_holiday')), isset($filter['weekly_holiday']) ? $filter['weekly_holiday'] : '', ['class' => 'form-select']) }}
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
                    <h5 class="mb-0">{{ __('locations') }}</h5>
                </div>
                <a href="{{ route('admin.administration.locations.create') }}" class="btn bg-gradient-primary btn-sm mb-0">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    {{ __('Add New Location') }}
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
                                @sortablelink('location_name', __('Location'))
                            </th>
                            
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('location_name', __('Latitude'))
                            </th>

                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('location_name', __('Longitude'))
                            </th>

                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('location_name', __('Acceptable Range (In Meter)'))
                            </th>

                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('location_name', __('Weekly Holiday'))
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
                        @foreach ($locations as $location)
                            <tr>
                                <td class="text-center" style="width:50px;">
                                    <p class="text-xs font-weight-bold mb-0">{{ ++$i }}</p>
                                </td>
                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ wordlimit($location->location_name) }}
                                    </p>
                                </td>
                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ wordlimit($location->latitude) }}
                                    </p>
                                </td>
                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ wordlimit($location->longitude) }}
                                    </p>
                                </td>
                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ wordlimit($location->acceptable_range) }}
                                    </p>
                                </td>
                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ wordlimit($location->weekly_holiday) }}
                                    </p>
                                </td>
                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $location->created_at }}</p>
                                </td>
                                <td class="text-center">
                                    <div class="form-check form-switch d-inline-block">
                                        <input class="form-check-input updateStatus" type="checkbox" role="switch"
                                            {{ $location->status ? 'checked' : '' }} data-id="{{ $location->id }}">
                                    </div>
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('admin.administration.locations.destroy', $location->id) }}" method="Post">
                                        <a class="btn btn-warning btn-sm text-white" title="{{ __('Edit') }}"
                                            data-toggle="tooltip" href="{{ route('admin.administration.locations.edit', $location->id) }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
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
            {{ $locations->appends($filter)->links('pagination::bootstrap-5') }}
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
                    url: "{{ route('admin.administration.locations.changeStatus') }}",
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
