@extends('layouts.admin.app')
@section('content')
    <div class="row">
        <div class="col-11 mx-auto">
            <div class="card card-default">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                            <h5 class="mb-0">
                            {{__('Import User Data')}}
                            </h5>
                    </div>
                </div>
                <div class="d-flex flex-row justify-content-end mx-4">
                    <div class="d-flex flex-row justify-content-between ">
                        <h5 class="mb-0  ">
                            <a href="/{{ config('constants.default_user_csv') }}" class="btn btn-info">
                                <i class="fas fa-download"></i> {{__('Download Sample File')}}
                            </a>
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{  Request::routeIs('admin.' . $routeSlug . '.importView') ? route('admin.' . $routeSlug . '.import') : route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <label for="file" class="">{{__('Select CSV File')}}</label>
                        <input type="file" name="file" class="form-control">
                        <br>
                        <button type="submit" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="{{__('Import User Data')}}">
                        {{__('Import User Data')}}
                        </button>
                        <a href="{{  Request::routeIs('admin.' . $routeSlug . '.importView') ? route('admin.' . $routeSlug . '.index') : route('admin.users.index') }}" type="button" name="button"
                            class="btn btn-light">{{__('BACK TO LIST')}}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
