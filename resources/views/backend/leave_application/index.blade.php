@extends('layouts.admin.app')
@section('content')

<div class="accordion card filter_card mb-4" id="accordionFilter">
    <div class="accordion-item mb-3">
        <h5 class="accordion-header card-header p-3" id="headingFilter">
            <button class="accordion-button p-0 font-weight-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                <i class="fa fa-filter"></i> {{ __('Filter') }}
                <i class="collapse-close fa fa-plus pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
                <i class="collapse-open fa fa-minus pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
            </button>
        </h5>
        <div id="collapseFilter" class="accordion-collapse collapse @if (!empty($filter)) show @endif" aria-labelledby="headingFilter" data-bs-parent="#accordionFilter">
            <div class="accordion-body card-body p-3 text-sm opacity-8">
                <form action="{{ route('admin.leave.leaves.index') }}" method="GET">
                    @csrf
                    <div class="border">
                        <div class="d-flex flex-row align-content-between flex-wrap">
                            <div class="p-2 flex-fill">
                                <input type="text" id="search" name="search" class="form-control" value="{{ !empty($filter['search']) ? $filter['search'] : '' }}" autocomplete="off" placeholder="{{ __('Search By User Name,Leave Type') }}">
                            </div>
                            <div class="p-2 flex-fill">
                                {{ Form::select('user_id', ['' => __('Select By Employee')] + $user_list, old('user_id',!empty($filter['user_id']) ? $filter['user_id'] : ''),  ['class' => 'form-select select2']) }}
                                @error('user_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="p-2 flex-fill">
                                {{ Form::select('authorised_person_id', ['' => __('Select Manager')] + $managers, old('authorised_person_id',!empty($filter['authorised_person_id']) ? $filter['authorised_person_id'] : ''), ['class' => 'form-select select2']) }}

                                </select>
                                @error('authorised_person_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="p-2 flex-fill">
                                {{ Form::select('status', ['' => __('Select Leave Status')] + getListTranslate(config('constants.default_leave_status')), isset($filter['status']) ? $filter['status'] : '', ['class' => 'form-select']) }}
                            </div>
                        </div>
                        <div class="d-flex flex-row align-content-between flex-wrap">
                        <div class="p-2 flex-fill">
                                <input type="text" name="start_request_date" class="form-control datepicker" autocomplete="off" value="{{ !empty($filter['start_request_date']) ? $filter['start_request_date'] : '' }}" placeholder="{{__('Search From Start Request Date')}}"/>
                        </div>
                        <div class="p-2 flex-fill">
                                <input type="text" name="end_request_date" class="form-control datepicker" autocomplete="off" value="{{ !empty($filter['end_request_date']) ? $filter['end_request_date'] : '' }}" placeholder="{{__('Search From End Request Date')}}" />
                        </div>
                            <!-- <div class="p-2 flex-fill">
                                <input type="date" name="start_date" class="form-control" autocomplete="off" value="{{ !empty($filter['start_date']) ? $filter['start_date'] : '' }}" placeholder="{{__('From Date')}}" />
                            </div>
                            <div class="p-2 flex-fill ">
                                <input type="date" name="end_date" class="form-control" autocomplete="off" value="{{ !empty($filter['end_date']) ? $filter['end_date'] : '' }}" placeholder="{{__('To Date')}}" />
                            </div> -->

                            <div class="p-2">
                                <button type="submit" name="submit" class="btn btn-primary shadow-primary mb-0 button">
                                    {{ __('Filter') }}
                                </button>
                                <button type="submit" name="excel_export" class="btn btn-primary excel_export shadow-primary mb-0 button" value="{{ __('Export') }}">
                                    <i class="fas fa-file-excel"></i>
                                    {{ __('Export') }}
                                </button>
                                <button type="submit" name="pdf_export" value="{{ __('PDF') }}" class="btn btn-primary pdf_export shadow-primary mb-0 button">
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
                <h5 class="mb-0">{{ __('Leave Application') }}</h5>
            </div>
            <a href="{{ route('admin.leave.leaves.create') }}" class="btn bg-gradient-primary btn-sm mb-0">
                <i class="fa fa-plus" aria-hidden="true"></i>
                {{ __('Add New Leave Application') }}
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
                            @sortablelink('user_id', __('Employe Id'))
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('user_id', __('Name'))
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('leave_type_id', __('Leave Type'))
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('request_date', __('Apply Date'))
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('request_start_date', __('From Date'))
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('request_end_date', __('To Date'))
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('request_remark', __('Request Remark'))
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('approved_by', __('Approved By'))
                        </th>

                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('approve_date', __('Approve Date'))
                        </th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('status', __('Leave Status'))
                        </th>
                        <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            {{ __('Action') }}
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaves as $leav)

                    <tr>
                        <td class="text-center" style="width:50px;">
                            <p class="text-xs font-weight-bold mb-0">{{ ++$i }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $leav->user_id }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $leav->name }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $leav->leave_type }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $leav->request_date }}</p>
                        </td>
                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $leav->request_start_date }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $leav->request_end_date }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $leav->request_remark }}</p>
                        </td>

                        <td class="text-capitalize">
                            @if ($leav->status == 1)
                            <p class="text-xs font-weight-bold mb-0">{{ $leav->authorise_person }}</p>
                            @endif
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $leav->approve_date }}</p>
                        </td>

                        <td class="text-center">
                            <p class="text-xs font-weight-bold mb-0">
                                @if ($leav->status == 0)
                                <span class="updateStatus badge badge-pill bg-warning">Pending</span>
                                @elseif($leav->status == 1)
                                <span class="badge badge-pill  bg-success">Approve</span>
                                @elseif($leav->status == 2)
                                <span class="badge badge-pill bg-danger">Cancel</span>
                                @endif
                            </p>
                        </td>

                        @if($leav->status != 1)
                        <td class="text-end">
                            <form action="{{ route('admin.leave.leaves.destroy', $leav->id) }}" method="Post">
                                <a class="btn btn-info btn-sm text-white" title="Approve Leave" data-toggle="tooltip" href="{{ route('admin.leave.leaves.show', $leav->id) }}"><i class="fa fa-thumbs-up" aria-hidden="true"></i></a>
                                <a class="btn btn-warning btn-sm text-white" title="{{ __('Edit') }}" data-toggle="tooltip" href="{{ route('admin.leave.leaves.edit', $leav->id) }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm text-white delete_confirm" title="{{ __('Delete') }}" data-toggle="tooltip"><i class="cursor-pointer fas fa-trash"></i></button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>


            </table>
        </div>
        <div class="mt-2">
            {{ $leaves->appends($filter)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

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
                url: "{{ route('admin.leave.leaves.changeStatus') }}",
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