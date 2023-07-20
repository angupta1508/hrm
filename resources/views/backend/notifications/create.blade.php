@extends('layouts.admin.app')
@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                    <h5 class="mb-0">
                        {{__('New Notifications')}}
                    </h5>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cms.notifications.store') }}" method="POST" enctype='multipart/form-data'>
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label for="role_id" class="form-label mt-4">{{__('Role Name')}}</label>
                            <select class="form-select" id="role_id" name="role_id" aria-label="role_id"
                                aria-describedby="role_id">

                                <option value="">{{__('Select Role')}} </option>
                                @foreach ($role as $rol)
                                    <option value="{{ $rol->id }} "
                                        {{ old('role_id') == $rol->id ? 'selected' : '' }}>{{ $rol->name }}
                                    </option>
                                @endforeach
                            </select>
                    </div>
                    <div class="col-md-6">
                        <label for="title" class="form-label mt-4">{{__('Title')}}</label>
                            <input type="text" class="form-control" placeholder="{{__('Title')}}" name="title" id="title"
                                aria-label="title" aria-describedby="title" value="{{ old('title') }}">
                            @error('title')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="image" class="form-label mt-4">{{__('Images')}}</label>
                            <input type="file" class="form-control filImageInput" name="image" id="image"
                                onchange="previewImage('.filImageInput', '.diplayImage')">
                            @php
                                $imagUrl = url(config('constants.default_image_path'));
                                
                            @endphp
                            <img src="{{ $imagUrl }}" style="height: 50px; width: 50px; margin-top: 10px;" ; class="diplayImage">
                            @error('image')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror

                    </div>
                    <div class="col-md-6">
                        <label for="description" class="form-label mt-4">{{__('description')}}</label>
                            <textarea class="form-control  " name="description" id="description" aria-label="description"
                                aria-describedby="description">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('admin.cms.notifications.index') }}" type="button" name="button"
                            class="btn btn-light m-0">{{__('BACK TO LIST')}}</a>
                        <button type="submit" name="button" class="btn bg-gradient-primary m-0 ms-2" data-toggle="tooltip" data-placement="top" title="Create Notifications">{{__('CREATE Notifications')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
