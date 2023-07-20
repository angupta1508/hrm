@extends('layouts.admin.app')

@section('content')
    <div class="row">
        <div class="card card-default">
            <div class="card-header pb-0">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <h5 class="mb-0">
                            {{ __('Add New Package') }}
                        </h5>
                    </div>
                    <div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.package.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <label for="name" class="form-label">{{ __('Package Name') }}</label>
                            <input type="text" id="name" class="form-control" placeholder="{{ __('Package Name') }}"
                                aria-label="Name" aria-describedby="name" name="name" value="{{ old('name') }}">
                            @error('name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-4 mt-3">
                            <label for="price" class="form-label">{{ __('Package price') }}</label>
                            <input type="text" id="price" class="form-control"
                                placeholder="{{ __('Package price') }}" aria-label="price" aria-describedby="price"
                                name="price" value="{{ old('price') }}">
                            @error('price')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-4 mt-3">
                            <label for="package_type" class="form-label">{{ __('Package Type') }}</label>
                            <select class="form-select" id="package_type" name="package_type" aria-label="package_type"
                                aria-describedby="package_type">
                                <option value="">{{ __('Please Select Type') }}</option>
                                <option value="free" {{ old('package_type') == 'free' ? 'selected' : '' }}>
                                    {{ __('Free') }}
                                </option>
                                <option value="paid" {{ old('package_type') == 'paid' ? 'selected' : '' }}>
                                    {{ __('Paid') }}
                                </option>
                            </select>
                            @error('package_type')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-3 mt-3">
                            <label for="trial_duration" class="form-label">{{ __('Trial Duration') }}</label>
                            <input type="number" id="trial_duration" class="form-control"
                                placeholder="{{ __('Trial Duration') }}" aria-label="trial_duration"
                                aria-describedby="trial_duration" name="trial_duration"
                                value="{{ old('trial_duration') }}">
                            @error('trial_duration')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-3 mt-3">
                            <label for="duration" class="form-label">{{ __('Duration') }}</label>
                            <input type="number" id="duration" class="form-control" placeholder="{{ __('Duration') }}"
                                aria-label="duration" aria-describedby="duration" name="duration"
                                value="{{ old('duration') }}">
                            @error('duration')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-3 mt-3">
                            <label for="user_limit" class="form-label">{{ __('User Limit') }}</label>
                            <input type="number" id="user_limit" class="form-control" placeholder="{{ __('User Limit') }}"
                                aria-label="user_limit" aria-describedby="user_limit" name="user_limit"
                                value="{{ old('user_limit') }}">
                            @error('user_limit')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-3 mt-3">
                            <label for="label">{{ __('Label') }}</label>
                            {{ Form::select('label', ['' => __('Select label')] + getListTranslate($labels), old('label'), ['class' => 'form-select']) }}
                        </div>

                        <div class="col-md-12 mt-3">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea name="description" id="description" class="form-control summernote">{{ old('page_meta_description') }}</textarea>
                            @error('description')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <label for="description" class="form-label">{{ __('Select Module') }}</label>
                            <div class="row form-group module-in-package">
                                @foreach ($pack_modules as $module)
                                    <div class="col-md-4 pb-3">
                                        <span class="pull-left form-check form-switch d-inline-block"
                                            style="position: relative; top: 10px;">
                                            <input type='checkbox' name="module[]" class='form-check-input updateStatus'
                                                value="{{ $module->id }}" />
                                        </span>
                                        <span class="mt-15 pl-3">
                                            <?= __(ucwords($module->module_name)) ?>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>








                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" name="button"
                            class="btn bg-gradient-primary m-0 ms-2">{{ __('CREATE') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
