@extends('layouts.front-user.app')

@section('content')
<main class="maindashatten">
  <section class="d-none d-sm-block">
    <div class="search  mx-auto d-flex mb-0 align-items-center">
      <!-- <span class="fa fa-search"></span> -->
      <input placeholder="Type your Keywords to search..." class="rounded-pill px-4 py-4 mb-0">
      <div class="d-flex border border-2 bgtheme ms-auto  settingicon fs-2 text-light">
        Profile Details
      </div>
    </div>
  </section>
  <p class="fw-semibold fs-1 text-center d-block d-sm-none"> Profile Details</p>

  <div class="bgtheme mt-1 pb-5">
    <p class="fs-2 mx-3 pt-3 text-light fw-semibold">Profile details</p>
    <div class="row  pb-5 mx-4 gap-4">
      <div class="col-xl-4 col-lg-5 col-md-12 card profilecard empprofile">
        <div class="mt-5 pt-5 mx-auto empimg">
          <img src="{{$authdetail->profile_image}}" class="empimg mb-2 rounded-circle" alt="" srcset="">
        </div>
        <div class="mt-5">
          <form action="{{ route('updateImg', $authdetail ) }}" method="POST" enctype='multipart/form-data'>
            @method('POST')
            @csrf
            <div class="row mt-3 text-secondry">
              <div class="col-md-2">
              </div>
              <div class="col-md-4">
                <input type="file" class="pl-3" name="profile_image" id="profile_image" aria-label="profile_image" aria-describedby="profile_image">
              </div>
                 <div class="col-md-2">
              </div>
              <div class="col-md-4">
                <button type="submit">upload image</button>
              </div> 
            
            </div>
          </form>
        </div>

        <p class="themeclr fw-semibold fs-1 mt-4 mb-0 text-center">{{$authdetail->name}}</p>
        <p class="themeclr  text-center mb-3 fs-5 mt-1 mb-4">{{$authdetail->designation_name}}<i class="fa-solid fa-circle-check mx-2 fs-5"></i></p>
        <p class="text-secondary text-center">Employee Code :-{{$authdetail->employee_code}}</p>

      </div>

      <div class="col-xl-7 col-lg-6 col-md-12 empprofile pb-4 pt-4 profilecard ">
        <div class="row px-4">
          <div class="form-group  col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <label class="themeclr fw-semibold">Mobile Number</label>
            <div class="input-group ">
              <p type="tel" class="form-control rounded-5 p-4 bgthemelight">{{ $authdetail->mobile }}</p>
            </div>
          </div>
          <div class="form-group col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <label class="themeclr fw-semibold">Martial Status </label>
            <div class="input-group ">

              <p type="tel" class="form-control rounded-5 p-4 bgthemelight">{{ $authdetail->marital_status }}</p>

            </div>
          </div>
          <div class="form-group py-2 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <label class="themeclr fw-semibold">Email</label>
            <div class="input-group ">
              <p type="tel" class="form-control rounded-5 p-4 bgthemelight">{{ $authdetail->email }}</p>
            </div>
          </div>

          <div class="form-group py-2 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <label class="themeclr fw-semibold">Designation</label>
            <div class="input-group ">
              <p type="tel" class="form-control rounded-5 p-4 bgthemelight">{{ $authdetail->designation_name }}</p>
            </div>
          </div>

          <div class="form-group py-2 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <label class="themeclr fw-semibold">Local Address</label>
            <div class="input-group ">
              <p type="tel" class="form-control rounded-5 p-4 bgthemelight">{{ $authdetail->address }}</p>
            </div>
          </div>


          <div class="form-group py-2 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <label class="themeclr fw-semibold">Department</label>
            <div class="input-group ">
              <p type="tel" class="form-control rounded-5 p-4 bgthemelight">{{ $authdetail->department_name }}</p>

            </div>
          </div>

          <div class="form-group py-2 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <label class="themeclr fw-semibold">Gender</label>
            <div class="input-group ">
              <p type="tel" class="form-control rounded-5 p-4 bgthemelight">{{ $authdetail->gender }}</p>
            </div>
          </div>


          <div class="form-group py-2 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <label class="themeclr fw-semibold">Branch</label>
            <div class="input-group ">
              <input type="text" class="form-control rounded-5 p-4 bgthemelight text-black fw-semibold" placeholder="Kota" />
            </div>
          </div>

          <div class="form-group py-2 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <label class="themeclr fw-semibold">PAN Number</label>
            <div class="input-group ">
              <p type="tel" class="form-control rounded-5 p-4 bgthemelight">{{ $authdetail->pan_no }}</p>
            </div>
          </div>

          <div class="form-group py-2 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <label class="themeclr fw-semibold">Date Of Joining</label>
            <div class="input-group ">
              <p type="tel" class="form-control rounded-5 p-4 bgthemelight">{{ $authdetail->joined_date }}</p>
            </div>
          </div>

          <div class="form-group pt-2 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <label class="themeclr fw-semibold">Aadhar Number</label>
            <div class="input-group ">
              <p type="tel" class="form-control rounded-5 p-4 bgthemelight">{{ $authdetail->aadhaar_no }}</p>
            </div>
          </div>

          <div class="form-group pt-2 col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
            <label class="themeclr fw-semibold">Reporting Manager</label>
            <div class="input-group ">
              <p type="tel" class="form-control rounded-5 p-4 bgthemelight">{{ $authdetail->author_name }}</p>
            </div>
          </div>

        </div>

      </div>
    </div>

  </div>
</main>

@endsection