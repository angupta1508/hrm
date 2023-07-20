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
                    <form action="{{ route('admin.payroll.salary-settlement.index') }}" method="GET">
                        @csrf
                        <div class="border">
                            <div class="d-flex flex-row align-content-between flex-wrap">
                                <div class="p-2 flex-fill">
                                    <input type="text" id="search" name="search" class="form-control"
                                        value="{{ !empty($filter['search']) ? $filter['search'] : '' }}" autocomplete="off"
                                        placeholder="{{ __('Search By Settlement month') }}">
                                </div>
                                <div class="p-2 flex-fill">
                                    {{ Form::select('user_id', ['' => __('Select Employee')] + $user_list, old('user_id', !empty($filter['user_id']) ? $filter['user_id'] : ''), ['class' => 'form-select select2']) }}
                                    @error('user_id')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="p-2 flex-fill">
                                    {{ Form::select('status', ['' => __('Select Payment Type')] + getListTranslate(config('constants.payment_type')), isset($filter['type']) ? $filter['type'] : '', ['class' => 'form-select']) }}
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
                    <h5 class="mb-0">{{ __('Salary Settlement') }}</h5>
                </div>
                <a href="{{ route('admin.payroll.salary-settlement.create') }}"
                    class="btn bg-gradient-primary btn-sm mb-0">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    {{ __('Add Salary Settlement') }}
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
                                @sortablelink('settlement_month', __('Settlement Month'))
                            </th>

                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('name', __('Name'))
                            </th>

                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('type', __('Type'))
                            </th>

                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('amount', __('Amount'))
                            </th>

                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                {{ __('Action') }}
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salary_settlements as $settlement)
                            <tr>
                                <td class="text-center" style="width:50px;">
                                    <p class="text-xs font-weight-bold mb-0">{{ ++$i }}</p>
                                </td>

                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $settlement->settlement_month }}</p>
                                </td>


                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $settlement->name }}</p>
                                </td>

                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $settlement->type }}</p>
                                </td>

                                <td class="text-capitalize">
                                    <p class="text-xs font-weight-bold mb-0">{{ $settlement->amount }}</p>
                                </td>

                                <td class="text-end">
                                    <form action="{{ route('admin.payroll.salary-settlement.destroy', $settlement->id) }}"
                                        method="Post">

                                        <a class="btn btn-warning btn-sm text-white" title="{{ __('Edit') }}"
                                            data-toggle="tooltip"
                                            href="{{ route('admin.payroll.salary-settlement.edit', $settlement->id) }}">
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
            <div class="mt-2">
                {{ $salary_settlements->appends($filter)->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    </div>
    </div>
@endsection

@push('dashboard')
@endpush
