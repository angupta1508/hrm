@extends('layouts.admin.app')
@section('content')
<style>
    .active{
        background: #cb0c9f;
        padding: 10px;
    }
</style>
    <div class="accordion card filter_card mb-4" id="accordionFilter">
        <div class="accordion-item mb-3">
            <h5 class="accordion-header card-header p-3" id="headingFilter">
                <button class="accordion-button p-0 font-weight-bold collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                    <i class="fa fa-filter"></i> {{ __('Filter') }}
                    <i class="collapse-close fa fa-plus pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
                    <i class="collapse-open fa fa-minus pt-1 position-absolute end-0 me-3" aria-hidden="true"></i>
                </button>
            </h5>
            <div id="collapseFilter" class="accordion-collapse collapse @if (!empty($filter)) show @endif"
                aria-labelledby="headingFilter" data-bs-parent="#accordionFilter">
                <div class="accordion-body card-body p-3 text-sm opacity-8">
                    <form action="{{ Request::route('admin.users.userlocation') }}" method="GET">
                        @csrf
                        <div class="border">
                            <div class="d-flex flex-row align-content-between flex-wrap">
                                <div class="p-2 flex-fill">
                                    <input type="date" name="date" class="form-control" autocomplete="off"
                                        value="{{ !empty($filter['date']) ? $filter['date'] : '' }}">
                                </div>
                                <div class="p-2">
                                    <button type="submit" id="submit" name="submit"
                                        class="btn btn-primary shadow-primary mb-0 button">
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

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header p-3">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">{{ __('User Location Track') }} </h5>
                        </div>
                        {{-- <div>
                            <button class="btn btn-sm btn-primary shadow-primary mb-0 button p-2"
                                onClick="window.location.reload();">Map Reset</button>
                        </div> --}}

                    </div>
                    <div class="card-body p-3">
                        <div id="map" style="width: 100%; height: 500px;"></div>
                        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDzltJV1UcXAkH-mMzzG0OwBiNH9iWba2s"></script>
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>User Location Track Timeline</h6>
                </div>

                <div class="card-body p-3">
                    <div class="timeline timeline-one-side" data-timeline-axis-style="dotted"
                        style="height: 400px; scroll-behavior: smooth; overflow-y: scroll;">
                        @foreach ($userLocationArray as $key => $item)
                            <div class="timeline-block mb-3 setZoom" data-key={{ $key }}
                                onclick="zoomToMarker({{ $key }})">
                                <span class="timeline-step">
                                    <i class="fa fa-circle text-primary text-gradient"></i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">
                                        {{ !empty($item['location']) ? $item['location'] : '' }}</h6>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('dashboard')
        <script>
            var map;
            var markers = [];

            var lineCoordinates = @json($userLocationArray);
            //Your map
            async function initMap() {
                map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 14,
                    center: {
                        lat: 25.1681749,
                        lng: 75.8481951
                    },
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    scrollwheel: true,
                    draggable: true,
                });
                var lineSymbol = {
                    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
                };
                var line = new google.maps.Polyline({
                    path: lineCoordinates,
                    icons: [{
                        icon: lineSymbol,
                        repeat: '35px',
                        offset: '100%'
                    }],
                    geodesic: true,
                    strokeColor: lineCoordinates[0].colour,
                    strokeOpacity: 1.0,
                    strokeWeight: 2,
                    map: map
                });

                lineCoordinates.forEach(function(location, index) {
                    var marker = new google.maps.Marker({
                        position: location,
                        map: map
                    });

                    // Create an info window for each marker
                    var infoWindow = new google.maps.InfoWindow({
                        content: location.content
                    });

                    // Store the marker and info window in the markers array
                    markers.push({
                        index: index,
                        marker: marker,
                        infoWindow: infoWindow
                    });

                    // Add a click event listener to each marker
                    marker.addListener('click', function() {
                        // Close any open info windows
                        closeInfoWindows();

                        // Open the info window at the marker's position
                        infoWindow.open(map, marker);
                        map.setCenter(marker.getPosition());
                        map.setZoom(16);
                    });

                });
            }

            function zoomToMarker(index) {
                // Retrieve the marker from the markers array using the provided index
                var marker = markers.find(function(markerData) {
                    return markerData.index === index;
                });
                if (marker) {
                    // Close any open info windows
                    closeInfoWindows();

                    // Zoom in on the marker
                    map.setCenter(marker.marker.getPosition());
                    map.setZoom(15);

                    // Open the info window at the marker's position
                    marker.infoWindow.open(map, marker.marker);
                    setActiveCoordinate(index);
                }
            }

            function closeInfoWindows() {
                // Close all open info windows
                markers.forEach(function(markerData) {
                    markerData.infoWindow.close();
                });
            }

            function setActiveCoordinate(index) {
                // Get all coordinate items
                var coordinateItems = document.getElementsByClassName('timeline-content');

                // Remove active class from all items
                for (var i = 0; i < coordinateItems.length; i++) {
                    coordinateItems[i].classList.remove('active');
                    coordinateItems[i].getElementsByTagName('h6')[0].classList.remove('text-white');
                    coordinateItems[i].getElementsByTagName('h6')[0].classList.add('text-dark');
                }
                // console.log(coordinateItems[index].getElementsByTagName('p'));
                // console.log(coordinateItems[index].getElementsByTagName('p')[0]);
                // Add active class to the clicked coordinate item
                coordinateItems[index].classList.add('active');
                coordinateItems[index].getElementsByTagName('h6')[0].classList.remove('text-dark');
                coordinateItems[index].getElementsByTagName('h6')[0].classList.add('text-white');
            }

            google.maps.event.addDomListener(window, 'load', initMap);
        </script>
    @endpush
@endsection
