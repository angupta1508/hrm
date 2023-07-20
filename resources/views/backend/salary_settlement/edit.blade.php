@extends('layouts.admin.app')
@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        {{ __('Edit Salary Settlement') }}
                    </h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.payroll.salary-settlement.update', $salary_settlement) }}" method="POST" enctype='multipart/form-data'>
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label for="settlement_month" class="form-label mt-4">{{ __('Salary Settlement Month') }}</label>
                        <div class="">
                            <input type="month" class="form-control" placeholder="{{ __('Salary Settlement Name') }}"
                                name="settlement_month" id="settlement_month" value="{{ old('settlement_month',$salary_settlement->settlement_month) }}">
                            @error('settlement_month')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="state_id" class="form-label mt-4">{{ __('User') }}</label>
                        <div>
                            {{ Form::select('user_id', ['name' => __('Select User')] + $user_list, old('user_id',$salary_settlement->user_id), ['class' => 'form-select select2 User_dropdown']) }}
                            @error('user_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="type" class="form-label mt-4">{{ __('Type') }}</label>
                        <div>
                            {{ Form::select('type', ['name' => __('Select Payement Type')] + config('constants.payment_type'), old('type',$salary_settlement->type), ['class' => 'form-select']) }}
                            @error('type')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="amount" class="form-label mt-4">{{ __('Amount') }}</label>
                        <div class="">
                            <input type="number" class="form-control" placeholder="{{ __('Amount') }}" name="amount"
                                id="amount" value="{{ old('amount',$salary_settlement->amount) }}">
                            @error('amount')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="description" class="form-label mt-4">{{ __('Description') }}</label>
                        <div class="">
                            <textarea class="form-control" placeholder="{{ __('Description') }}" name="description" id="description">{{ old('description',$salary_settlement->description) }}</textarea>
                            @error('description')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('admin.payroll.salary.index') }}" type="button" name="button"
                            class="btn btn-light m-0">{{ __('BACK TO LIST') }}</a>
                        <button type="submit" name="button"
                            class="btn bg-gradient-primary m-0 ms-2">{{ __('Update') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('dashboard')
        <script></script>
    @endpush
@endsection
