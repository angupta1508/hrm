@extends('layouts.admin.app')
@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        {{ __('New Language') }}
                    </h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.administration.languages.store') }}" method="POST" enctype='multipart/form-data'>
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label for="language_name" class="form-label mt-4">{{ __('Language Name') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Name') }}"
                                name="language_name" id="language_name" value="{{ old('language_name') }}">
                            @error('language_name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="language_code" class="form-label mt-4">{{ __('Language Code') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Language Code') }}"
                                name="language_code" id="language_code" value="{{ old('language_code') }}">
                            @error('language_code')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="flag_icon" class="form-label">{{ __('Flag Icon') }} </label>
                        <div class="">
                            <input type="file" class="form-control filImageInput" placeholder="{{ __('Flag Icon') }}"
                                name="flag_icon" onchange="previewImage('.filImageInput', '.diplayImage')" id="flag_icon">
                            @error('flag_icon')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror

                            <div class="avatar mt-3">
                                <img class="w-100 border-radius-sn shadow-sm diplayImage"
                                    src="{{ ImageShow(config('constants.language_image_path'), '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="type" class="form-label">{{ __('Type') }}</label>
                        <div class="d-flex mt-2">
                            <div class="form-check form-switch me-4">
                                <input class="form-check-input" name="system_language_status" type="checkbox"
                                    id="system_language_status" value="1"
                                    @if (old('system_language_status') == 1) checked @endif>
                                <label class="form-check-label"
                                    for="system_language_status">{{ __('System Languages') }}</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" name="tongue_language_status" type="checkbox"
                                    id="tongue_language_status" value="1"
                                    @if (old('tongue_language_status') == 1) checked @endif>
                                <label class="form-check-label"
                                    for="tongue_language_status">{{ __('Tongue Languages') }}</label>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.administration.languages.index') }}" type="button" name="button"
                        class="btn btn-light m-0">{{ __('BACK TO LIST') }}</a>
                    <button type="submit" name="button"
                        class="btn bg-gradient-primary m-0 ms-2">{{ __('SUBMIT') }}</button>
                </div>
            </form>
        </div>
    </div>
    
@endsection
