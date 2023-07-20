@extends('layouts.front.app')

@section('meta_title'){{ $page_data->page_meta_title }}@endsection
@section('meta_kewords'){{ $page_data->page_meta_key }}@endsection
@section('meta_description'){{ $page_data->page_meta_description }}@endsection

@section('content')
    <div class="lowhead text-center">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="al-breadcrumb container ">
                        <h1 class="fs-2 fw-bold mb-1 themeclr p-3">Contact US</h1>
                        <p class="text-black fw-600 subhead fw-semibold fs-3">Always Ready for Innovation</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #map {
            height: 550px;
        }
    </style>
    <!--cards-->

    <section class="contactmap mt-1 mb-1 px-5">
        <!--Contact heading-->
        <div class="row">
            <div class="col-sm-12 text-center mb-4"></div>
            <!--Grid column-->
            <div class="col-sm-12 mb-4 col-md-5">
                <!--Form with header-->
                <div class="card border-none rounded-0">
                    <div class="card-header p-0">
                        <div class="topcontact text-white text-center py-2">
                            <h3 class="text-warning"><i class="fa fa-envelope"></i> Contact us:</h3>
                            <p class="text-black m-0">We Have The Best Astrologers in our team.</p>
                        </div>
                    </div>
                    <form action="{{ route('enquiry') }}" method="POST">
                        @csrf
                        <div class="card-body p-3">
                            <div class="form-group py-2">
                                <label>Name </label>
                                <div class="input-group">
                                    <input value="{{ old('name') }}" type="text" name="name" class="form-control"
                                        id="inlineFormInputGroupUsername" placeholder="Name" />
                                    @error('name')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group py-2">
                                <label>Email</label>
                                <div class="input-group mb-2 mb-sm-0">
                                    <input type="email" value="{{ old('email') }}" name="email" class="form-control"
                                        id="inlineFormInputGroupUsername" placeholder="Email" />
                                    @error('email')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group py-2">
                                <label>Phone No.</label>
                                <div class="input-group mb-2 mb-sm-0">
                                    <input type="text" value="{{ old('number') }}" name="number" class="form-control intlinput"
                                        id="inlineFormInputGroupUsername" placeholder="Phone No." />
                                    @error('number')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group py-2">
                                <label>Subject</label>
                                <div class="input-group mb-2 mb-sm-0">
                                    <input type="text" name="subject" class="form-control"
                                        id="inlineFormInputGroupUsername" placeholder="Subject"
                                        value="{{ old('Subject') }}" />
                                    @error('subject')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group py-2">
                                <label>Message</label>
                                <div class="input-group mb-2 mb-sm-0">
                                    <textarea class="rounded form-control" name="message" cols="60" rows="10"></textarea>
                                    @error('message')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-center py-3">
                                <button type="submit" class="btn btn-warning btn-block rounded py-2">
                                    {{ __('SUBMIT') }}
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!--Grid column-->

            <!--Grid column-->
            <div class="col-sm-12 col-md-7">
                <!--Google map-->
                <div class="mb-4">
                    <div id="map">
                        
                    </div>
                   
                </div>
                <!--Buttons-->
                <div class="row text-center">
                    <div class="col-md-4">
                        <a class="bg-warning px-3 py-2 rounded text-white mb-2 d-inline-block"><i
                                class="fa fa-map-marker"></i></a>
                        <?php $address = Config::get('address'); ?>
                        <p>{{ $address }}</p>
                    </div>
                    <div class="col-md-4">
                        <a class="bg-warning px-3 py-2 rounded text-white mb-2 d-inline-block"><i
                                class="fa fa-phone"></i></a>
                        <?php $mob = Config::get('mobile_no');
                        $tel = Config::get('telephone'); ?>
                        <p>{{ $mob }}</p>
                        <p>{{ $tel }}</p>
                    </div>
                    <div class="col-md-4">
                        <a class="bg-warning px-3 py-2 rounded text-white mb-2 d-inline-block"><i
                                class="fa fa-envelope"></i></a>
                        <?php $email = Config::get('email'); ?>
                        <p>{{ $email }}</p>
                    </div>
                </div>
            </div>
            <!--Grid column-->
        </div>
    </section>
@endsection
