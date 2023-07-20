@extends('layouts.admin.app')
@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        {{ __('Add New Holiday') }}
                    </h5>
                </div> 
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.leave.holidays.store') }}" method="POST" enctype='multipart/form-data'>
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label for="holiday_name" class="form-label mt-4">{{ __('Holiday Name') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Holiday Name') }}"
                                name="holiday_name" id="holiday_name" value="{{ old('holiday_name') }}">
                            @error('holiday_name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="date" class="form-label mt-4">{{ __('Date') }}</label>
                        <div class="">
                            <input type="date"  class="form-control"
                                placeholder="{{ __('Date') }}" name="date" id="date"
                                value="{{ old('date') }}">
                            @error('date')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <label for="holiday_type" class="form-label mt-4">{{ __('Holiday Type') }}</label>
                        <div class="">
                            <input type="text"  class="form-control"
                                placeholder="{{ __('Holiday Type') }}" name="holiday_type" id="holiday_type"
                                value="{{ old('holiday_type') }}">
                            @error('holiday_type')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div> -->
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.leave.holidays.index') }}" type="button" name="button"
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
