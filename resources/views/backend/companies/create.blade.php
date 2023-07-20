@extends('layouts.admin.app')
@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        {{ __('Add New Company') }}
                    </h5>
                </div>
            </div>
        </div> 
        <div class="card-body">
            <form action="{{ route('admin.administration.companies.store') }}" method="POST" enctype='multipart/form-data'>
                @csrf  
                <div class="row">
                    <div class="col-md-6">
                        <label for="name" class="form-label mt-4">{{ __('Company Name') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Company Name') }}" name="name"
                                id="name" value="{{ old('name') }}">
                            @error('name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.administration.companies.index') }}" type="button" name="button"
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
