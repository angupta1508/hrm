@extends('layouts.admin.app')

@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        {{ __('Edit') }} {{__('Template')}}
                    </h5>
                </div>
                <div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sms-template.update', $templates->id) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <label for="title" class="form-label mt-4">{{__('Title')}}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{__('Title')}}" name="title" id="title"
                                value="{{ $templates->title }}">
                            @error('title')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="content" class="form-label mt-4">{{__('Content')}}</label>
                        <div class="">
                            <textarea class="form-control " name="content" id="content" placeholder="{{__('Content')}}">{{ $templates->content }}</textarea>
                            @error('content')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('admin.sms-template.index') }}" type="button" name="button"
                            class="btn btn-light m-0">{{ __('BACK TO LIST') }}</a>
                        <button type="submit" name="button"
                            class="btn bg-gradient-primary m-0 ms-2">{{ __('Edit') }}</button>
                    </div>
            </form>
        </div>
    </div>
@endsection
