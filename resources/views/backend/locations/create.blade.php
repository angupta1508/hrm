@extends('layouts.admin.app')
@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        {{ __('Add New Location') }}
                    </h5>
                </div>
            </div> 
        </div> 
        <div class="card-body">
            <form action="{{ route('admin.administration.locations.store') }}" method="POST" enctype='multipart/form-data'>
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label for="location_name" class="form-label mt-4">{{ __('Location Name') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Location Name') }}" name="location_name"
                                id="location_name" value="{{ old('location_name') }}">
                            @error('location_name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="latitude" class="form-label mt-4">{{ __('Latitude') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Latitude') }}" name="latitude"
                                id="latitude" value="{{ old('latitude') }}">
                            @error('latitude')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="longitude" class="form-label mt-4">{{ __('Longitude') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Longitude') }}" name="longitude"
                                id="longitude" value="{{ old('longitude') }}">
                            @error('longitude')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="ip" class="form-label mt-4">{{ __('IP') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('IP') }}" name="ip"
                                id="ip" value="{{ old('ip') }}">
                            @error('ip')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="acceptable_range" class="form-label mt-4">{{ __('Acceptable Range (In Meter)') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Acceptable Range') }}" name="acceptable_range"
                                id="acceptable_range" value="{{ old('acceptable_range') }}">
                            @error('acceptable_range')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label for="weekly_holiday" class="form-label mt-4">{{ __('Weekly Holiday') }}</label>
                        <div class="">
                            {{ Form::select('weekly_holiday', ['' => __('Select Weekly Holiday')] + getListTranslate(config('constants.default_weekly_holiday')), null , ['class' => 'form-select']) }}
                            @error('weekly_holiday')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                     
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.administration.locations.index') }}" type="button" name="button"
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
