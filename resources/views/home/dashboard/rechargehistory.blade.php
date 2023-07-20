@extends('layouts.front-admin.app')

@section('content')
    <div class="col-lg-8 me-auto card border packround py-4 rounded-5 gap-4 b-5">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card" style="margin-bottom:20px;">
                        <div class="card-header">
                            <i class="fa fa-filter"></i> Filter
                        </div>
                        <div class="card-body">
                            <form action="{{ route('rechargehistory') }}" id="users_list" method="GET">
                                @csrf
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td colspan="2">
                                                <input type="text" id="search" name="search" class="form-control"
                                                    value="{{ !empty($filter['search']) ? $filter['search'] : '' }}" autocomplete="off" placeholder="Search">
                                            </td>
                                            <td>
                                                <select class="form-control" id="status" name="status">
                                                    <option value="">Status</option>
                                                    <option value="1" {{ isset($filter['status']) && $filter['status'] == 1 ? 'selected' : '' }}>Success
                                                    </option>
                                                    <option value="0" {{ isset($filter['status']) && $filter['status'] == 0 ? 'selected' : '' }}>Failure
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="date" name="start_date" id="start_date" class="form-control"
                                                    value="{{ !empty($filter['start_date']) ? $filter['start_date'] : '' }}" autocomplete="off" placeholder="Start Date">
                                            </td>
                                            <td>
                                                <input type="date" name="end_date" id="end_date" class="form-control"
                                                    value="{{ !empty($filter['end_date']) ? $filter['end_date'] : '' }}" autocomplete="off" placeholder="End Date">
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-primary shadow-primary px-5 button"
                                                    name="submit">Submit</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
                            <div>
                                <h5 class="mb-0">All Recharge</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-0">           
                        <table class="table table-light table-striped table-sm table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        S. No.
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Recharge Id
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Package Id
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Order Id
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Razorpay Id
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Amount
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rechargeHistory as $rec)
                                    <tr>
                                        <td class="text-center" style="width:50px;">
                                            <p class="text-xs font-weight-bold mb-0">{{ ++$i }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $rec->recharge_uni_id }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $rec->package_uni_id }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $rec->order_id }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $rec->razorpay_id }}</p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ $rec->amount }}</p>
                                        </td>
                                        <td class="text-center">
                                            @if ($rec->status == 1)
                                                <button class="btn btn-success">Success</button>
                                            @else
                                                <button class="btn btn-danger">Failure</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $rechargeHistory->appends($filter)->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
