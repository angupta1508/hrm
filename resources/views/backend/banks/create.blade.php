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
            <form action="{{ route('admin.administration.banks.store') }}" method="POST" enctype='multipart/form-data'>
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label for="bank_name" class="form-label mt-4">{{ __('Bank Name') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Bank Name') }}" name="bank_name"
                                id="bank_name" value="{{ old('bank_name') }}">
                            @error('bank_name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="bank_logo" class="form-label mt-4">{{ __('Bank Logo') }}</label>
                        <div class="row">
                            <div class="col-md-6">
                                <input type="file" onchange="previewImage('.filImageInput', '.diplayImage')"
                                    class="form-control filImageInput" name="bank_logo" id="bank_logo">
                                @error('bank_logo')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <div class="avatar">
                                    <img class="w-100 border-radius-sn shadow-sm diplayImage"
                                        src="{{ ImageShow(config('constants.bank_image_path'), '') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.administration.banks.index') }}" type="button" name="button"
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
