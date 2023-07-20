@extends('layouts.front.app')

@section('content')
    <main class="packdetail py-3 mb-5 mt-3">
        <div class="container">
            <p class="text-center fs-2 fw-bolder">Packages</p>
            @php $i = 0; @endphp
            <div class="row">
                @foreach ($packages as $key => $value)
                    @php
                        $i++;
                        $hh = $i % 2;
                        if ($hh == 0) {
                            $class = 'pricecard2';
                            $feature = 'featurepriceimg2';
                            $text = 'fw-bold  themeclr';
                            $theme = 'themeclr';
                        } else {
                            $theme = 'bgtheme';
                            $text = 'fw-bold text-light';
                            $feature = 'featurepriceimg';
                            $class = 'pricecard';
                        }
                    @endphp
                    <a href="{{ route('packagedetail', Crypt::encrypt($value->package_uni_id)) }}">
                        <div class="col-md-4">
                            <div class="{{ $class }} text-center mx-auto mt-5">
                                <div class="{{ $feature }} mx-auto">
                                    <div class="ribbon ribbon-top-left"><span>{{ $value->label }}</span></div>
                                    <p class="fw-bold fs-1 {{ $text }} mt-4 pt-4 mb-0">{{ $value->label }}</p>
                                    <p class="fs-6 {{ $text }} mx-2 mt-1">{{ $value->name }}</p>

                                </div>
                                {!! $value->description !!}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </main>
@endsection
