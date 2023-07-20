@extends('layouts.admin.app')
@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        {{ __('Add New Bank') }}
                    </h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.user-bankers.store') }}" method="POST" enctype='multipart/form-data'>
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label for="bank_id" class="form-label mt-4">{{ __('Bank Name') }}</label>
                        <div class="">
                            {{ Form::select('bank_id', ['' => __('Select Bank')] + $bank_list, old('bank_id'), ['class' => 'form-select']) }}
                            @error('bank_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="user_id" class="form-label mt-4">{{ __('User') }}</label>
                        <div class="">
                            {{ Form::select('user_id', ['' => __('Select User')] + $user_list, old('user_id'), ['class' => 'form-select select2']) }}
                            @error('user_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="account_no" class="form-label mt-4">{{ __('Account Number') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Account Number') }}"
                                name="account_no" id="account_no" value="{{ old('account_no') }}">
                            @error('account_no')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="account_type" class="form-label mt-4">{{ __('Account Type') }}</label>
                        <div class="">
                            {{ Form::select('account_type', ['' => __('Select Account Type')] + getListTranslate(config('constants.account_type')), old('account_type'), ['class' => 'form-select']) }}
                            @error('account_type')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="ifsc_code" class="form-label mt-4">{{ __('IFSC Code') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('IFSC Code') }}"
                                name="ifsc_code" id="ifsc_code" value="{{ old('ifsc_code') }}">
                            @error('ifsc_code')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="account_name" class="form-label mt-4">{{ __('Account Holder Name') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Account Holder Name') }}"
                                name="account_name" id="account_name" value="{{ old('account_name') }}">
                            @error('account_name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.user-bankers.index') }}" type="button" name="button"
                        class="btn btn-light m-0">{{ __('BACK TO LIST') }}</a>
                    <button type="submit" name="button" class="btn bg-gradient-primary m-0 ms-2">
                        {{ __('Submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @push('dashboard')
    @endpush
@endsection
