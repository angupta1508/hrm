@extends('layouts.front-user.app')
@section('content')



<div class="hrmlogo bgtheme w-100  mb-4 py-2">
    <a href="#" class="text-decoration-none"><img src="./assest/img/hrm LOGO WHITWE 1.png" class="" alt=""></a>

    <a href="./notification.html"> <i class="fa-solid fa-bell  fs-1 float-right me-4 mt-3 text-light position-relative">
            <span class="position-absolute notifictionsign top-0 start-100 translate-middle bg-success border border-success rounded-circle">
                <span class="visually-hidden">New alerts</span></i></a>
</div>
<!-- main screen -->
<main class="maindashatten">
    <section class="d-none d-sm-block">
        <div class="search  mx-auto d-flex mb-0">
            <!-- <span class="fa fa-search"></span> -->

            <div class="d-flex border border-2 bgtheme ms-auto settingicon fs-2 text-light fw-semibold">
                Notifications

            </div>

        </div>
    </section>
    <p class="fw-semibold fs-1 text-center d-block d-sm-none">Notifications</p>

    <div class="notice">
        <p class="fs-1 text-blacl fw-semibold d-none d-sm-block">Notifications</p>
        <div class="me-5">
            @foreach($notification as $not)
            <div class="card p-3 d-flex flex-row mt-4">
                <img class="rounded-circle birthdayimg" src="{{$not->image}}" alt="user" data-toggle="tooltip" title="" data-original-title="Sarah Smith" />
                <div class="d-flex flex-column mx-3">
                    <span class="fw-bolder fs-5">{{$not->title}}</span>
                    <p>{{$not->description}}</p>
                </div>
            </div>

            @endforeach

        </div>
    </div>

    @endsection