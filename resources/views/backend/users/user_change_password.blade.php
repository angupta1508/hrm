
@extends('layouts.admin.app')

@section('content')
    <div class="container-fluid my-3 py-3">
        <div class="col-lg-12 mt-lg-0 mt-4">
            
            <div class="card mt-4" id="basic-info">
                <div class="card-header">
                    <h5>{{__('Change Password')}}</h5>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('admin.user-update-password',$user) }}" method="POST" role="form text-left">
                        @csrf
                        <div class="row">
                             <div class="col-12">
                                <label class="form-label">{{__('Old Password')}}</label>
                                <div class="input-group">
                                   
                                <input class="form-control" value="" type="text" placeholder="{{__('Old Password')}}" id="password" name="old_password">
                                  
                            </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{__('New Password')}}</label>
                                <div class="input-group">
                                    <input class="form-control" value="" type="text" placeholder="{{__('New Password')}}" id="password" name="password">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{__('Confirm Password')}}</label>
                                <div class="input-group">
                                    <input class="form-control" value="" type="text" placeholder="{{__('Confirm Password')}}" id="confirm_password" name="confirm_password">
                                  
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                
                                <button type="submit" name="button" class="btn bg-gradient-primary m-0 ms-2">{{ __('Edit') }} {{__('Password')}}</button>
                            </div>
                            
                        </div>

                    </form> 
                </div>
            </div>
        </div>
    </div>
 @endsection
