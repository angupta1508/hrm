@extends('layouts.front-user.app')
@section('content')


<main class="maindashatten">
    <section class="">
        <div class="search  mx-auto d-flex mb-0 align-items-center">
            <!-- <span class="fa fa-search"></span> -->
            <input placeholder="Type your Keywords to search..." class="d-none d-sm-block rounded-pill px-4 py-4  mb-0">

            <div class="d-flex border border-2 d-none d-sm-block bgtheme ms-auto  settingicon fs-2 text-light">
                User Location Track

            </div>

        </div>
        <p class="fw-semibold fs-1 text-center d-block d-sm-none"> User Location Track</p>

    </section>



    <!--filter start from here-->
    <div class="col-sm-12  my-3">
        <div class="accordion" id="accordionExamplewallet">
            <div class="accordion-item border-0">
                <h2 class="accordion-header" id="headingOnewallet">
                    <button class="accordion-button text-black bgthemelight rounded-4 border fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOnewallet" aria-expanded="true" aria-controls="collapseOnewallet">
                        <i class="fa-solid fa-filter mx-1 themeclr"></i><b class="themeclr fw-semibold"> Filter</b>
                    </button>
                </h2>


                        <form action="{{ route('getUserLocation',$id) }}" method="GET">
                            @csrf
                            <div class="row border border-2 rounded-2 p-2 align-items-center">
                                <div class="w-25">
                                    <input type="date" name="date" class="form-control" autocomplete="off" value="{{ !empty($filter['date']) ? $filter['date'] : '' }}">
                                </div>

                                <div class="col-sm-3">
                                    <button type="submit" id="submit" class="btn w-100  rounded-5 bgtheme  button text-light mx-auto approvebtn " name="submit">
                                        Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
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
                    <div class="timeline timeline-one-side" data-timeline-axis-style="dotted" style="height: 400px; scroll-behavior: smooth; overflow-y: scroll;">
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
</main>

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

@endsection