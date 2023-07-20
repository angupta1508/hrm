
@extends('layouts.admin.app')
@section('content')


<div class="container-fluid">
    <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('../assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
        <span class="mask bg-gradient-primary opacity-6"></span>
    </div>
    <div class="card card-body blur shadow-blur mx-4 mt-n6">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <img src="{{ url(config('constants.setting_image_path') . config('logo')) }}" alt="..." class="w-100 border-radius-lg shadow-sm">
                    <a href="javascript:;" class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2">
                        <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }} Image"></i>
                    </a>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">
                        {{ auth()->user()->name }}
                    </h5>
                    <p class="mb-0 font-weight-bold text-sm">
                        {{ auth()->user()->email }}
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                
            </div>
        </div>
    </div>
</div>

    <div class="container-fluid my-3 py-3">
        <div class="col-lg-12 mt-lg-0 mt-4">
            <div class="card mt-4" id="basic-info">
                <div class="card-header">
                    <h5>Profile Information</h5>
                </div>
                <div class="card-body p-3">
                    <p class="text-sm">
                    Hi, I’m Alec Thompson, Decisions: If you can’t decide, the answer is no. If two equally difficult paths, choose the one more painful in the short term (pain avoidance is creating an illusion of equality).
                    </p>
                    <hr class="horizontal gray-light my-4">
                    <ul class="list-group">
                    <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Full Name:</strong> &nbsp; {{$users->username  }}</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Mobile:</strong> &nbsp; {{$users->mobile }}</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Email:</strong> &nbsp; {{$users->email  }}</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Location:</strong> &nbsp; IND</li>
                    <li class="list-group-item border-0 ps-0 pb-0">
                    <strong class="text-dark text-sm">Social:</strong> &nbsp;
                    <a class="btn btn-facebook btn-simple mb-0 ps-1 pe-2 py-0" href="javascript:;">
                    <i class="fab fa-facebook fa-lg" aria-hidden="true"></i>
                    </a>
                    <a class="btn btn-twitter btn-simple mb-0 ps-1 pe-2 py-0" href="javascript:;">
                    <i class="fab fa-twitter fa-lg" aria-hidden="true"></i>
                    </a>
                    <a class="btn btn-instagram btn-simple mb-0 ps-1 pe-2 py-0" href="javascript:;">
                    <i class="fab fa-instagram fa-lg" aria-hidden="true"></i>
                    </a>
                    </li>
                    </ul>
                    </div>
                
            </div>
        </div>
    </div>
 @endsection
