@extends('layouts.admin.app')

@section('content')
    <?php
    $search = !empty($_GET['search']) ? $_GET['search'] : '';
    $status = !empty($_GET['status']) ? $_GET['status'] : '';
    $start_date = !empty($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = !empty($_GET['end_date']) ? $_GET['end_date'] : '';
    ?>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card" style="margin-bottom:20px;">
                        <div class="card-header">
                            <i class="fa fa-filter"></i> {{__('Filter')}}
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.recharge.index') }}" id="users_list" method="GET">
                                @csrf
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td colspan="2">
                                                <input type="text" id="search" name="search" class="form-control"
                                                    value="{{ $search }}" autocomplete="off" placeholder="{{__('Search')}}">
                                            </td>
                                            <td>
                                                <select class="form-select" id="status" name="status">
                                                    <option value="">{{__('Status')}}</option>
                                                    <option value="1" {{ $status == 1 ? 'selected' : '' }}>{{__('Success')}}
                                                    </option>
                                                    <option value="0" {{ $status == 0 ? 'selected' : '' }}>{{__('Failure')}}
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="date" name="start_date" id="start_date" class="form-control"
                                                    value="{{ $start_date }}" autocomplete="off" placeholder="{{__('Start Date')}}">
                                            </td>
                                            <td>
                                                <input type="date" name="end_date" id="end_date" class="form-control"
                                                    value="{{ $end_date }}" autocomplete="off" placeholder="{{__('End Date')}}">
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-primary shadow-primary px-5 button"
                                                    name="submit">{{__('Submit')}}</button>
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
                                <h5 class="mb-0">{{__('All Recharge')}}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{__('S. No.')}}
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{__('Recharge Id')}}
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{__('Package Id')}}
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{__('Admin Id')}}
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{__('Order Id')}}
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{__('Razorpay Id')}}
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{__('Amount')}}
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            {{__('Status')}}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recharge as $rec)
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
                                                <p class="text-xs font-weight-bold mb-0">{{ $rec->admin_id }}</p>
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
                                            <td>
                                                <!-- {{ 10 > 11 ? 'yes' : 'no' }} -->
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        
                        {{ $recharge->appends($filter)->links('pagination::bootstrap-5') }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
