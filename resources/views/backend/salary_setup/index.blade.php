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
                <form action="{{ route('admin.payroll.salary-setup.index') }}" method="GET">
                    @csrf
                    <div class="border">
                        <div class="d-flex flex-row align-content-between flex-wrap">
                            <div class="p-2 flex-fill">
                                <input type="text" id="search" name="search" class="form-control" value="{{ !empty($filter['search']) ? $filter['search'] : '' }}" autocomplete="off" placeholder="{{ __('Search By User Name, Basic Salary') }}">
                            </div>
                            <div class="p-2 flex-fill">
                                {{ Form::select('user_id', ['' => __('Select Employee')] + $user_list, old('user_id',!empty($filter['user_id']) ? $filter['user_id'] : ''),  ['class' => 'form-select select2']) }}
                                @error('user_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="p-2 flex-fill">
                                <input type="text" id="salary_type_id" name="salary_type_id" class="form-control" value="{{ !empty($filter['salary_type_id']) ? $filter['salary_type_id'] : '' }}" autocomplete="off" placeholder="{{ __('Search By Salary Type') }}">
                            </div>

                            <!-- <div class="p-2 flex-fill">
                                    {{ Form::select('status', ['' => __('Select Status')] + config('constants.default_status'), isset($filter['status']) ? $filter['status'] : '', ['class' => 'form-select']) }}
                                </div>
                                               -->
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
                <h5 class="mb-0">{{ __('Salary Setup') }}</h5>
            </div>
            <a href="{{ route('admin.payroll.salary-setup.create') }}" class="btn bg-gradient-primary btn-sm mb-0">
                <i class="fa fa-plus" aria-hidden="true"></i>
                {{ __('Add New Salary Setup') }}
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
                            @sortablelink('user_id', __('Name'))
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('salary_type_id', __('Salary Type'))
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 400px;">
                            @sortablelink('basic_salary', __('Basic   Salary'))
                        </th>


                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('dearness_allowance', __('Dearness Allowance'))
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('per_hour_overtime_amount', __('Per Hour Overtime Amount'))
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('washing_allowance', __('Washing Allowance'))
                        </th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('house_rant_allowance', __('House Rent Allowance'))
                        </th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('conveyance_allowance', __('Conveyance Allowance'))
                        </th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('medical_allowance', __('Medical Allowance'))
                        </th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('other_allowance', __('Other Allowance'))
                        </th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('fix_incentive', __('Fix Incentive'))
                        </th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('variable_incentive', __('Variable Incentive'))
                        </th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('deductions', __('Deductions'))
                        </th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('welfare_fund', __('Welfare Fund'))
                        </th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            @sortablelink('affected_date', __('Affected Date'))
                        </th>

                        <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                            {{ __('Action') }}
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($setup as $set)
                    <tr>
                        <td class="text-center" style="width:50px;">
                            <p class="text-xs font-weight-bold mb-0">{{ ++$i }}</p>
                        </td>
                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $set->name }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $set->salary_type }}</p>
                        </td>

                        <td class="text-capitalize mt-5">
                            <p class="text-xs font-weight-bold mb-0">
                                @if ($set->salary_based_on == 0)
                                <p class="text-xs font-weight-bold mt-3">{{ $set->basic_salary }}/Month</p>
                                @elseif($set->salary_based_on == 1)
                                <p class="text-xs font-weight-bold mt-3">{{ $set->basic_salary }}/Hours</p>
                                @endif
                            </p>
                         </td>


                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $set->dearness_allowance }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $set->per_hour_overtime_amount }}</p>
                        </td>

                     

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $set->washing_allowance }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $set->house_rant_allowance }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $set->conveyance_allowance }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $set->medical_allowance }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $set->other_allowance }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $set->fix_incentive }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $set->variable_incentive }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $set->deductions }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $set->welfare_fund }}</p>
                        </td>

                        <td class="text-capitalize">
                            <p class="text-xs font-weight-bold mb-0">{{ $set->affected_date }}</p>
                        </td>

                        <td class="text-end">
                            <form action="{{ route('admin.payroll.salary-setup.destroy', $set->id) }}" method="Post">

                                <a class="btn btn-warning btn-sm text-white" title="{{ __('Edit') }}" data-toggle="tooltip" href="{{ route('admin.payroll.salary-setup.edit', $set->id) }}">
                                    <i class="fas fa-edit"></i>
                                </a>

                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm text-white delete_confirm" title="{{ __('Delete') }}" data-toggle="tooltip"><i class="cursor-pointer fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        <div class="mt-2">
            {{ $setup->appends($filter)->links('pagination::bootstrap-5') }}
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
                url: "{{ route('admin.payroll.salary-setup.changeStatus') }}",
                type: 'post',
                data: {
                    _token: _token,
                    id: id,
                    status: status,
                },
                success: function(result) {
                    if (result.success) {
                        Swal.fire({
                            title: "{{ __('Success!') }}",
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