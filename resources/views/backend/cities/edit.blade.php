@extends('layouts.admin.app')
@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        {{ __('Edit New City') }}
                    </h5>
                </div>
            </div>
        </div> 
        <div class="card-body">
            <form action="{{ route('admin.cities.update', $city) }}" method="POST" enctype='multipart/form-data'>
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label for="country_id" class="form-label mt-4">{{ __('Country') }}</label>
                        <div class="">
                            {{ Form::select('country_id', ['' => __('Select Country')] + $country_list, old('country_id', $city->country_id), ['class' => 'form-select select2 country_dropdown']) }}
                            @error('country_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="state_id" class="form-label mt-4">{{ __('State') }}</label>
                        <div class="">
                            {{ Form::select('state_id', ['' => __('Select State')] + $state_list, old('state_id', $city->state_id), ['class' => 'form-select select2 state_dropdown']) }}
                            @error('state_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="form-label mt-4">{{ __('City Name') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('City Name') }}" name="name"
                                id="name" aria-label="name" aria-describedby="name"
                                value="{{ old('name', $city->name) }}">
                            @error('name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.cities.index') }}" type="button" name="button"
                        class="btn btn-light m-0">{{ __('BACK TO LIST') }}</a>
                    <button type="submit" name="button"
                        class="btn bg-gradient-primary m-0 ms-2">{{ __('Edit') }}</button>
                </div>
            </form>
        </div>
    </div>

    @push('dashboard')
        <script></script>
    @endpush
@endsection
