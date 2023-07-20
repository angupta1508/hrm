@extends('layouts.admin.app')
@section('content')

        <div class="card card-default">
            <div class="card-header pb-0">
                <div class="d-flex flex-row justify-content-between">
                    <h5 class="mb-0">
                        {{__('New notice')}}
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.cms.notices.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label for="title" class="form-label mt-4">{{__('Title')}}</label>
                            <input type="text" class="form-control" placeholder="{{__('Title')}}" name="title" id="title" aria-label="title" aria-describedby="title" value="{{ old('title') }}">
                            @error('title')
                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="type" class="form-label mt-4">{{__('Type')}}</label>
                            {{ Form::select('type', ['download' =>__('Download'), 'announce' => 'Announce', 'welcome_message' => 'Welcome Message'], old('type'), ['class' => 'form-select']) }}
                            @error('type')
                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="attachment" class="form-label mt-4">{{__('Add Attachment')}}</label>
                            <div class="">
                                <input type="file" onchange="previewImage('.filImageInput', '.diplayImage')" class="form-control filImageInput" placeholder="{{__('Add Attachment')}}" name="attachment" id="attachment">
                                @error('attachment')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label mt-4">{{__('Description')}}</label>
                                <textarea class="form-control summernote" name="description" id="description" aria-label="description" aria-describedby="description">{{ old('description') }}</textarea>
                                @error('description')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                          
                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('admin.cms.notices.index') }}" type="button" name="button" class="btn btn-light m-0">{{__('BACK TO LIST')}}</a>
                                <button type="submit" name="button" class="btn bg-gradient-primary m-0 ms-2">{{__('CREATE notice')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


@push('dashboard')
<script></script>
@endpush
@endsection