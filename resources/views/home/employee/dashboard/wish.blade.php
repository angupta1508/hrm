@extends('layouts.front-user.app')
@section('content')


<!-- main screen -->
<main class="maindashatten">
  <section class="d-none d-sm-block">
    <div class="search  mx-auto d-flex mb-0">
      <!-- <span class="fa fa-search"></span> -->
      <input placeholder="Type your Keywords to search..." class="rounded-pill px-4 py-4 mt-2 mb-0">

      <div class="d-flex border border-2 bgtheme ms-auto settingicon fs-2 text-light fw-semibold">
        Wishes

      </div>

    </div>
  </section>
  <p class="fw-semibold fs-1 text-center d-block d-sm-none">Wishes</p>
  <div class="row ">
    <div class="card  px-3 my-3 py-3 rounded-4 col-11 col-lg-6 mx-auto">
      <p class="fs-5 mt-3 themeclr text-center fw-semibold">Today's Birthday/CELEBRATION</p>

      @foreach ($todayCelebrationList as $list)
      <div class="row mt-2">
        <div class="col-2">
          <img class="rounded-circle birthdayimg" src="{{ $list->profile_image }}" alt="user" data-toggle="tooltip" title="" data-original-title="Sarah Smith" />
        </div>
        <div class="col-8">
          <p class="fs-6">Wish <b>{{ $list->name }}</b>

            @php $type = !empty($list->type) ? $list->type : '' @endphp
            @if ($type == 'birthday')
            Happy Birthday
            @php $bd = !empty($list->bd) ? $list->bd : '' @endphp
            <b class="text-success fs-6"> {{$bd}}</b>
            @elseif($type == 'anniversary')
            Happy Anniversary
            @php $anniv = !empty($list->anniv) ? $list->anniv : '' @endphp
            <b class="text-success fs-6"> {{$anniv}}</b>
            @endif

          </p>
        </div>
        <div class="col-2">
          <button class="btn-outline-danger px-3 py-2 rounded-2 ">Wish</button>
        </div>
      </div>
      @endforeach


      <!-- <div class="row mt-2">
        <div class="col-2">
          <img class="rounded-circle birthdayimg" src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="user" data-toggle="tooltip" title="" data-original-title="Sarah Smith" />
        </div>
        <div class="col-7">
          <p class="fs-6">Wish <b>Tejendra Singh Rajawat</b> Happy
            Birthday Today</p>
        </div>
        <div class="col-3">
          <button class="btn-outline-danger px-3 py-2 rounded-2">Wish</button>
        </div>
      </div> -->
    </div>

    <div class="card  px-3 my-3 py-3 rounded-4 col-11 col-lg-5  mx-auto">
      <p class="fs-5 mt-3 themeclr text-center fw-semibold">Upcoming Birthday/CELEBRATION</p>
      @foreach ($celebrationList as  $list)
      <div class="row mt-2">
        <div class="col-2">
          <img class="rounded-circle birthdayimg" src="{{ $list->profile_image }}" alt="user" data-toggle="tooltip" title="" data-original-title="Sarah Smith" />
        </div>
        <div class="col-10">
          <p class="fs-6">Wish <b>{{ $list->name }}</b>
            {{$list->wish_msg}}
            <b class="text-success fs-6"> {{prettyDateFormet($list->wish_date,'date')}}</b>
          </p>
        </div>
      </div>
      @endforeach

      <div class="row mt-2">

      </div>
    </div>

</main>
<!-- new added Swiper JS -->
<script src="./assest/package/swiper-bundle.min.js"></script>

<!-- new added bootstrap JS -->
<script src="./assest/bootstrapjs/bootstrap.min.js"></script>

<script src="./assest/js/index.js"></script>







@endsection