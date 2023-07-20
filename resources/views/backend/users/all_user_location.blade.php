@extends('layouts.admin.app')
@section('content')
<div class="accordion card filter_card mb-4" id="accordionFilter">
    <div class="accordion-item mb-3">
        <h5 class="accordion-header card-header p-3" id="headingFilter">
            <button class="accordion-button p-0 font-weight-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                <i class="fa fa-filter"></i> {{ __('Filter') }}
                <i class="collapse-close fa fa-plus pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
                <i class="collapse-open fa fa-minus pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
            </button>
        </h5>
        <div id="collapseFilter" class="accordion-collapse collapse @if (!empty($filter)) show @endif" aria-labelledby="headingFilter" data-bs-parent="#accordionFilter">
            <div class="accordion-body card-body p-3 text-sm opacity-8">
                <form action="{{ Request::route('admin.users.userlocation') }}" method="GET">
                    @csrf
                    <div class="border">
                        <div class="d-flex flex-row align-content-between flex-wrap">
                            <div class="p-2">
                                {{ Form::select('user_id', ['' => __('Select Employee')] + $user_list, old('user_id',!empty($filter['user_id']) ? $filter['user_id'] : ''),  ['class' => 'form-select form-control']) }}
                                @error('user_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="p-2 flex-fill">
                                <input type="date" name="date" class="form-control" autocomplete="off" value="{{ !empty($filter['date']) ? $filter['date'] : '' }}">
                            </div>
                            <div class="p-2">
                                <button type="submit" id="submit" name="submit" class="btn btn-primary shadow-primary mb-0 button">
                                    {{ __('Filter') }}
                                </button>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header p-3">
        <div class="d-flex flex-row justify-content-between">
            <div>
                <h5 class="mb-0">{{__('All User Location Track')}} </h5>
            </div>
        </div>

    </div>
    <div class="card-body p-3">
        <div id="map" style="width: 100%; height: 500px;"></div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDzltJV1UcXAkH-mMzzG0OwBiNH9iWba2s"></script>
        <div id="map"></div>
    </div>
</div>


@endsection
@push('dashboard')

<script>
    var bikearray = @json($allUserLocationArray);

    //Your map
    function initMap() {
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 13,
            center: new google.maps.LatLng(25.141083, 75.867722),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        });
        var lineSymbol = {
            path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
        };

        for (i = 0; i < bikearray.length; i++) {
            var from_lat = parseFloat(bikearray[i].from_lat);
            var from_long = parseFloat(bikearray[i].from_long);
            var startMarker = new google.maps.Marker({
                map: map,
                position: {
                    lat: from_lat,
                    lng: from_long
                }
            });
            var to_lat = ''
            var to_long = '';
            if (bikearray[i + 1].from_lat != '' && bikearray[i + 1].from_long != '') {
                var to_lat = parseFloat(bikearray[i + 1].from_lat);
                var to_long = parseFloat(bikearray[i + 1].from_long);
            }
            var endMarker = new google.maps.Marker({
                map: map,
                position: {
                    lat: to_lat,
                    lng: to_long
                }
            });
            var time = bikearray[i].time;
            var infowindow = new google.maps.InfoWindow();
            google.maps.event.addListener(endMarker, 'click', (function(marker, time, infowindow) {
                return function(evt) {
                    infowindow.setContent(time);
                    infowindow.open(map, marker);
                }
            })(endMarker, time, infowindow));

            google.maps.event.trigger(endMarker, 'click');
            // var linecolor = bikearray[i].colour;
            console.log(bikearray[i].from_lat);
            var bikePath = new google.maps.Polyline({
                path: [

                    {
                        lat: from_lat,
                        lng: from_long
                    },
                    {
                        lat: to_lat,
                        lng: to_long
                    }
                ],
                icons: [{
                    icon: lineSymbol,
                    repeat: '35px',
                    offset: '100%'
                }],
                geodesic: true,
                strokeColor: linecolor,
                strokeOpacity: 1.0,
                strokeWeight: 2,
                map: map
            });
            bikePath.setMap(map);
        }
    }

    google.maps.event.addDomListener(window, 'load', initMap);
</script>
@endpush