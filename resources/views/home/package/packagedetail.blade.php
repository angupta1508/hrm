@extends('layouts.front.app')

@section('content')
    <main class="packdetail py-3 mb-5 mt-3">
        <div class="container">
            <p class="text-center fs-2 fw-bolder">Package Detail</p>
            <div class="d-flex align-items-center">
                <div class="mx-4 px-2">
                    <p class="fw-bold fs-1 mb-0">{{ $packages[1]['label'] }}</p>
                    <p class="themeclr fs-4 fw-bolder mt-0">{{ $packages[1]['name'] }}</p>
                </div>
                {{-- 
                <div class="ms-auto me-3 text-center">
                    <p class="themeclr fs-1 fw-bold mt-0 mb-0">₹ {{ $packages[1]['price'] }}</p>
                    <button class="text-light bgtheme border rounded-5 py-2 px-5 fw-bold mt-0 package_buy"
                        data-package_uni_id={{ $packages[1]['package_uni_id'] }} style="width: 250px;">Pay
                        now</button>
                </div> --}}
            </div>

            <div class="d-lg-flex mx-auto  justify-content-evenly d-md-block">
                @php $i = 0; @endphp
                @foreach ($packages as $key => $val)
                    @php
                        $i++;
                        $hh = $i % 2;
                        if ($hh == 0) {
                            $class = 'pricecard2';
                            $feature = 'featurepriceimg2';
                            $text = 'fw-bold  themeclr';
                            $theme = "themeclr";
                          } else {
                            $theme = "bgtheme";
                            $text = 'fw-bold text-light';
                            $feature = 'featurepriceimg';
                            $class = 'pricecard';
                        }
                    @endphp

                    <div class="{{ $class }} text-center mx-auto mt-5">
                        <div class="{{$feature}} mx-auto">
                            {{-- <div class="ribbon ribbon-top-left"><span>FREE</span></div> --}}
                            <p class="fs-1 {{$text}} mt-4 pt-4 mb-0">₹{{ $val['price'] }}</p>
                            <p class="fs-6 {{$text}} mx-2 mt-1">{{ $val['name'] }}</p>

                        </div>
                        {!! $val['description'] !!}
                        <button class="border border-2 {{$text}}t fw-bold {{$theme}} py-2 mb-2 px-5 rounded-5 package_buy"
                            data-package_uni_id={{ $val['package_uni_id'] }} data-type={{ $i }}>Buy Now
                        </button>
                    </div>
                @endforeach
            </div>

        </div>
    </main>
@endsection
