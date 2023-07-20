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
                    <form action="{{ route('admin.paymentSummary') }}" method="GET">
                        @csrf
                        <div class="border">
                            <div class="d-flex flex-row align-content-between flex-wrap">
                                <div class="p-2 flex-fill">
                                    <input type="text" id="search" name="search" class="form-control"
                                        value="{{ !empty($filter['search']) ? $filter['search'] : '' }}" autocomplete="off"
                                        placeholder="Search">
                                </div>
                                <div class="p-2 flex-fill">
                                    <input type="text" name="start_date" class="form-control datepicker"
                                        autocomplete="off"
                                        value="{{ !empty($filter['start_date']) ? $filter['start_date'] : '' }}"
                                        placeholder="Start Date" runat="server" data-date-format="dd M, yyyy" />
                                </div>
                                <div class="p-2 flex-fill">
                                    <input type="text" name="end_date" class="form-control datepicker" autocomplete="off"
                                        value="{{ !empty($filter['end_date']) ? $filter['end_date'] : '' }}"
                                        placeholder="End Date" runat="server" data-date-format="dd M, yyyy" />
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
                    <h5 class="mb-0">{{ __('Payment Summary') }}</h5>
                </div>

            </div>
        </div>
        <div class="card-body p-3">
            <div class="table-responsive p-2">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('id', __('S. No.'))
                            </th>
                            <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('name')
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('email')
                            </th>
                            <th class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('number', 'PHONE')
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('subject')
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                @sortablelink('created_at', 'Date')
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contacts as $contact)
                            <tr>
                                <td class="" style="width:50px;">
                                    <p class="text-xs font-weight-bold mb-0">{{ ++$i }}</p>
                                </td>
                                <td class="">
                                    <p class="text-xs font-weight-bold mb-0">{{ $contact->name }}</p>
                                </td>
                                <td class="">
                                    <p class="text-xs font-weight-bold mb-0">{{ $contact->email }}</p>
                                </td>
                                <td class="">
                                    <p class="text-xs font-weight-bold mb-0">{{ $contact->number }}</p>
                                </td>
                                <td class="">
                                    <p class="text-xs font-weight-bold mb-0">{{ $contact->subject }}</p>
                                </td>
                                <td class="">
                                    <p class="text-xs font-weight-bold mb-0">
                                        {{ prettyDateFormet($contact->created_at) }}</p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $contacts->appends($filter)->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
