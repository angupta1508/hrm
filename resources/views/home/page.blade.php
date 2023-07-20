@extends('layouts.front.app')

@section('meta_title')
    {{ $page_data->page_meta_title }}
@endsection
@section('meta_kewords')
    {{ $page_data->page_meta_key }}
@endsection
@section('meta_description')
    {{ $page_data->page_meta_description }}
@endsection

@section('content')
    <section class="All PagesData pt-5 pb-5">
        <div class="container">
            <div class="row">

                <div class="col-sm-12">

                    <h2 class="text-center">
                        {!! $page_data->page_name !!}
                    </h2><br>


                    {!! $page_data->page_content !!}

                </div>
            </div>
        </div>
    </section>
@endsection
