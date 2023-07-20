@extends('layouts.admin.app')
@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        {{ __('Edit New Department') }}
                    </h5>
                </div> 
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.administration.departments.update', $department) }}" method="POST" enctype='multipart/form-data'>
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label for="department_name" class="form-label mt-4">{{ __('Department Name') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Department Name') }}" name="department_name"
                                id="department_name"
                                value="{{ old('admin_charges', $department->department_name) }}">
                            @error('department_name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <label for="admin_charges" class="form-label mt-4">{{ __('Admin Charges') }}</label>
                        <div class="">
                            <input type="number" step="0.1" class="form-control"
                                placeholder="{{ __('Admin Charges') }}" name="admin_charges" id="admin_charges"
                                value="{{ old('admin_charges', $department->admin_charges) }}">
                            @error('admin_charges')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div> --}}
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.administration.departments.index') }}" type="button" name="button"
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
