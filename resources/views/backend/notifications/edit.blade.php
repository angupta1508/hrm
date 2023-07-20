@extends('layouts.admin.app')
@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <h5 class="mb-0">
                    {{__('Edit Notification')}}
                </h5>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cms.notifications.update', $notification) }}" method="POST"
                enctype='multipart/form-data'>
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label for="role_id" class="form-label mt-4" >{{__('Role Name')}}</label>
                        <select class="form-select" id="role_id" name="role_id" aria-label="role_id"
                            aria-describedby="role_id">
                            <option value="">{{__('Select Role')}} </option>
                            @foreach ($role as $country)
                                <option value="{{ $country->id }} "
                                    {{ old('role_id' , $notification->role_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                            <option value="unuser" {{ old('role_id') == 'unuser' ? 'selected' : '' }}>Unregistered Users
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="title" class="form-label mt-4">{{__('Title')}}</label>
                        <input type="text" class="form-control" placeholder="{{__('Title')}}" name="title" id="title"
                            aria-label="title" aria-describedby="title" value="{{ old('title' , $notification->title )}}">
                        @error('title')
                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="image" class="form-label mt-4">{{__('Images')}}</label>
                        <input type="file" class="form-control" placeholder="{{__('Images')}}" name="image" id="image"
                            aria-label="image" aria-describedby="image">
                        <div class="avatar mt-3">
                            <img class="w-100 border-radius-sm shadow-sm"
                                src="{{ ImageShow(
                                    config('constants.notification_image_path'),
                                    $notification->image,
                                    'icon',
                                    config('constants.default_image_path'),
                                ) }}"
                                class="diplayImage">
                        </div>
                        @error('image')
                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="description" class="form-label mt-4">{{__('Description')}}</label>
                        <textarea class="form-control" name="description" id="description" aria-label="description"
                            aria-describedby="description">{{ $notification->description }}</textarea>
                        @error('description')
                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>


                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('admin.cms.notifications.index') }}" type="button" name="button"
                            class="btn btn-light m-0">{{__('BACK TO LIST')}}</a>
                        <button type="submit" name="button" class="btn bg-gradient-primary m-0 ms-2" data-toggle="tooltip"
                            data-placement="top" title="Edit">{{__('Edit')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
