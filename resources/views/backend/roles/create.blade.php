@extends('layouts.admin.app')

@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        {{ __('Add New Role') }}
                    </h5>
                </div>
                <div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form
                action="{{ Request::routeIs('admin.' . $routeSlug . '.create') ? route('admin.' . $routeSlug . '.store') : route('admin.roles.store') }}"
                method="POST">
                @csrf
                <input type="hidden" value="{{ $routeId }}" name="role_type">
                <div>
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <div class="">
                        <input type="text" class="form-control" placeholder="{{__('Name')}}" name="name" id="name"
                            aria-label="Name" aria-describedby="name" value="{{ old('name') }}">
                        @error('name')
                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ Request::routeIs('admin.' . $routeSlug . '.create') ? route('admin.' . $routeSlug . '.index') : route('admin.roles.index') }}"
                        type="button" name="button" class="btn btn-light m-0">{{ __('BACK TO LIST') }}</a>
                    <button type="submit" name="button"
                        class="btn bg-gradient-primary m-0 ms-2">{{ __('SUBMIT') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
