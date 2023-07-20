@extends('layouts.admin.app')
@section('content')
<div class="card card-default">
    <div class="card-header pb-0">
        <div class="d-flex flex-row justify-content-between">
            <div>
                <h5 class="mb-0">
                    {{ __('Pay') }}
                </h5>
            </div>  
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.payroll.salary.salarypayout') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <label for="pay_month" class="form-label mt-4">{{ __('Pay Month') }}</label>
                    <div class="">
                        <input type="month"  class="form-control" placeholder="{{ __('Pay Month') }}" name="pay_month" id="pay_month" value="{{ old('pay_month') }}">
                        @error('pay_month')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.payroll.salary.index') }}" type="button" name="button" class="btn btn-light m-0">{{ __('BACK TO LIST') }}</a>
                    <button type="submit" name="button" class="btn bg-gradient-primary m-0 ms-2">
                        {{__('Pay')}}
                    </button>
                </div>
        </form>
    </div>
</div>
@push('dashboard')
@endpush
@endsection