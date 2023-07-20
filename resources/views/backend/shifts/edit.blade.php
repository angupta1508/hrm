@extends('layouts.admin.app')
@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        {{ __('Edit New Shift') }}
                    </h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.administration.shifts.update', $shift) }}" method="POST" enctype='multipart/form-data'>
                @method('PUT') 
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label for="shift_name" class="form-label mt-4">{{ __('Shift Name') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Shift Name') }}" name="shift_name"
                                id="shift_name" value="{{ old('shift_name', $shift->shift_name) }}">
                            @error('shift_name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="shift_type" class="form-label mt-4">{{ __('Shift Type') }}</label>
                        <div class="">
                                <div class="flex-fill">
                                    {{ Form::select('shift_type', ['' => __('Select Shift Type')] + getListTranslate(config('constants.default_shift_type')), $shift->shift_type, ['class' => 'form-select']) }}
                                </div>
                            @error('shift_type')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="from_time" class="form-label mt-4">{{ __('From Time') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('From Time') }}" name="from_time"
                                id="from_time" value="{{ old('from_time', $shift->from_time) }}">
                            @error('from_time')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="to_time" class="form-label mt-4">{{ __('To Time') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('To Time') }}" name="to_time"
                                id="to_time" value="{{ old('to_time', $shift->to_time) }}">
                            @error('from_time')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.administration.shifts.index') }}" type="button" name="button"
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
