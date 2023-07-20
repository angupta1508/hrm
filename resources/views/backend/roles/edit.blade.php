@extends('layouts.admin.app')

@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        {{ __('Edit Role') }}
                    </h5>
                </div>
                <div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form
                action="{{ Request::routeIs('admin.' . $routeSlug . '.edit', $role->id) ? route('admin.' . $routeSlug . '.update', $role->id) : route('admin.roles.update', $role) }}"
                method="POST">
                @method('PUT')
                @csrf

                <div>
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <div class="">
                        <input type="text" class="form-control" placeholder="Name" name="name" id="name"
                            aria-label="Name" aria-describedby="name" value="{{ $role->name }}">
                        @error('name')
                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ Request::routeIs('admin.' . $routeSlug . '.edit', $role->id) ? route('admin.' . $routeSlug . '.index', $role->id) : route('admin.roles.index') }}"
                        type="button" name="button" class="btn btn-light m-0">{{ __('BACK TO LIST') }}</a>
                    <button type="submit" name="button"
                        class="btn bg-gradient-primary m-0 ms-2">{{ __('Edit') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
