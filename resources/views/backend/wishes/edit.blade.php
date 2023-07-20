@extends('layouts.admin.app')
@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        {{ __('Edit New Wish') }}
                    </h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.moods.Wishes.update', $Wish) }}" method="POST" enctype='multipart/form-data'>
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <label for="user_id" class="form-label mt-4">{{ __('User Id') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('User Id') }}" name="user_id"
                                id="user_id" value="{{ old('user_id', $Wish->user_id) }}">
                            @error('user_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="sender_id" class="form-label mt-4">{{ __('Sender Id') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Sender Id') }}" name="sender_id"
                                id="sender_id" value="{{ old('sender_id', $Wish->sender_id) }}">
                            @error('sender_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="remark" class="form-label mt-4">{{ __('Remark') }}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{ __('Remark') }}" name="remark"
                                id="remark" value="{{ old('remark', $Wish->remark) }}">
                            @error('remark')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>


                </div>
                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('admin.moods.Wishes.index') }}" type="button" name="button"
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
