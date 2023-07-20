@extends('layouts.admin.app')
@section('content')
    <div class="card card-default">
        <div class="card-header pb-0">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">
                        Add New User
                    </h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data"
                action="{{ route('admin.domain.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <label for="name" class="form-label">Name</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="Name" name="name" id="name"
                                aria-label="Name" aria-describedby="name" value="{{ old('name') }}">
                            @error('name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="email" class="form-label">Email</label>
                        <div class="">
                            <input type="email" class="form-control" placeholder="Email" name="email" id="email"
                                aria-label="Email" aria-describedby="email-addon" value="{{ old('email') }}">
                            @error('email')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="mobile_intel" class="form-label">Mobile</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="Mobile" name="mobile_intel"
                                id="mobile_intel" value="{{ old('mobile_intel') }}">
                            @error('mobile_intel')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="subdomain" class="form-label">SubDomain</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="subdomain" name="subdomain"
                                id="subdomain" value="{{ old('subdomain') }}">
                            @error('subdomain')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!--Astro infromation end-->
                    <!--Category start-->
                    <!--Category end-->
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" name="button" class="btn bg-gradient-primary m-0 ms-2">CREATE
                            USER</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection