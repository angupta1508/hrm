@extends('layouts.admin.app')

@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        {{ __('Edit Pages') }}
                    </h5>
                </div>
                <div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.cms.pages.update', $page) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="row">
                    @foreach ($language_list as $key => $language)
                        <div class="col-md-12">
                            <label for="page_name[{{ $key }}]" class="form-label mt-4">{{ __('Page Name') }}
                                ({{ $language }})
                            </label>
                            <div class="">
                                <input type="text" class="form-control"
                                    placeholder="{{ __('Page Name') }} ({{ $language }})"
                                    name="page_name[{{ $key }}]" value="{{ !empty($language_pages[$key]->page_name)? $language_pages[$key]->page_name : '' }}">
                                @error('page_name[{{ $key }}]')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="page_content" class="form-label mt-4">{{ __('Page Description') }}
                                ({{ $language }})</label>
                            <div class="">
                                <textarea class="form-control summernote" placeholder="{{ __('Page Description') }} ({{ $language }})"
                                    name="page_content[{{ $key }}]">{{ !empty($language_pages[$key]->page_content)? $language_pages[$key]->page_content : '' }}</textarea>
                                @error('page_content[{{ $key }}]')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="page_meta_title[{{ $key }}]"
                                class="form-label mt-4">{{ __('Page Meta Title') }}
                                ({{ $language }})</label>
                            <div class="">
                                <input type="text" class="form-control"
                                    placeholder="{{ __('Page Meta Title') }} ({{ $language }})"
                                    name="page_meta_title[{{ $key }}]" value="{{ !empty($language_pages[$key]->page_meta_title)? $language_pages[$key]->page_meta_title :'' }}">
                                @error('page_meta_title[{{ $key }}]')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                    <div class="col-md-12">
                        <label for="page_meta_key" class="form-label mt-4">{{ __('Page Meta Key') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Page Meta Key') }}"
                                name="page_meta_key" value="{{ $page->page_meta_key }}">
                            @error('page_meta_key')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="page_meta_description"
                            class="form-label mt-4">{{ __('Page Meta Description') }}</label>
                        <div class="">
                            <textarea class="form-control" name="page_meta_description" placeholder="{{ __('Page Meta Description') }}">{{ $page->page_meta_description }}</textarea>
                            @error('page_meta_description')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.cms.pages.index') }}" type="button" name="button"
                        class="btn btn-light m-0">{{ __('BACK TO LIST') }}</a>
                    <button type="submit" name="button"
                        class="btn bg-gradient-primary m-0 ms-2">{{ __('Edit') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
