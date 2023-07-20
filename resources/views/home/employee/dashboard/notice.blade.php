@extends('layouts.front-user.app')
@section('content')

<!-- main screen -->
<main class="maindashatten">
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-sm" style="background-color: #fff5ee;">
                        <label class="mt-2">
                            <h5 class="fw-bolder fs-5">FORMS & TEMPLATE</h5>
                        </label>
                      
                        @foreach($note as $not)
                        @if ($not->type === 'download')
                        <div class="card row  d-flex flex-row mx-0  mb-2 mt-3 justify-content-start"  style="background-color: #dcdcdc;">
                            <div class="col-sm-9 mt-2">
                                <div class="row mb-0">
                                    <div class="col-sm-2">
                                        <i class="fa-regular fa-circle-check fs-3 text-light border bg-primary text-light rounded-circle  justify-content-start" style="padding: 13px 13px;"></i>
                                    </div>
                                    <div class="col-sm-10" >
                                        <span class="fw-bolder fs-5">{{$not->title}}</span>
                                        <p> {!! $not->description !!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 mt-1 text-end">
                                <a href="{{ $not->attachment }}" target="blank" download>Download
                             </a>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-sm" style="background-color: #fff5ee;">
                        <label class="mt-2">
                            <h5 class="fw-bolder fs-5">HR ANNOUNCEMENT</h5>
                        </label>
                        @foreach($note as $not)
                        @if ($not->type === 'announce')
                        <div class="card row  d-flex flex-row mx-0  mb-2 mt-3 justify-content-start" style="background-color: #dcdcdc;">
                            <div class="row mt-1 mb-1">
                                <span class="fw-bolder fs-5">{!!$not->title!!}</span>
                                <details class="mt-3">
                                    <summary>
                                        <span id="open">View More</span>
                                        <span id="close">Close</span>
                                    </summary>
                                    <p class="moretext"> {!! $not->description !!}</p>
                                </details>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<script>

</script>


@endsection