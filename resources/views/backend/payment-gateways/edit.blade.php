@extends('layouts.admin.app')

@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        {{ __('Edit Payment Gateway') }}
                    </h5>
                </div>
                <div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.administration.payment-gateways.update', $paymentGateway) }}" method="POST"
                enctype='multipart/form-data'>
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label for="gateway_name" class="form-label mt-4">{{ __('Payment Gateway') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Payment Gateway') }}"
                                name="gateway_name" id="gateway_name" aria-label="gateway_name"
                                aria-describedby="gateway_name"
                                value="{{ old('gateway_name', $paymentGateway->gateway_name) }}">
                            @error('gateway_name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="gateway_txn_charges" class="form-label mt-4">{{ __('Gateway Charge') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Gateway Charge') }}"
                                name="gateway_txn_charges" id="gateway_txn_charges"
                                value="{{ old('gateway_txn_charges', $paymentGateway->gateway_txn_charges) }}">
                            @error('gateway_txn_charges')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="description" class="form-label mt-4">{{ __('Description') }}</label>
                        <div class="">
                            <textarea type="text" class="form-control" placeholder="{{ __('Description') }}"
                                name="description" id="description">{{ old('description', $paymentGateway->description) }}</textarea>
                            @error('description')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.administration.payment-gateways.index') }}" type="button" name="button"
                        class="btn btn-light m-0">{{ __('BACK TO LIST') }}</a>
                    <button type="submit" name="button" class="btn bg-gradient-primary m-0 ms-2">Edit</button>
                </div>
            </form>
        </div>
    </div>
@endsection
