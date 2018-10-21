@extends('layouts.app')

@section('header')
    <style>
        #map {
            height: calc(100vh - 55px);
        }
        main.py-4 {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
        .epic-popup{
            max-width: 300px;
            max-height: 2.5em;
            text-align: center;
        }
    </style>

    <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v0.50.0/mapbox-gl.css' rel='stylesheet' />
@endsection


@section('basic_content')
    <div id="map"></div>
@endsection

@section('footer')
    <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v0.50.0/mapbox-gl.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
    <script>
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
    </script>
    <script>
        mapboxgl.accessToken = '{{ config('app.mapbox_key') }}';
        var map = new mapboxgl.Map({
            container: 'map', // container id
            style: 'mapbox://styles/mapbox/satellite-streets-v9', // stylesheet location
            center: [-95.7129, 37.0902], // starting position [lng, lat]
            zoom: 2 // starting zoom
        });

        let inciFires = [
            @foreach($inciWebData as $i=>$fire)
            {!! json_encode(collect($fire)->only(['title', 'date', 'lat', 'long', 'geoname', 'link'])) !!} {{ $i != count($inciWebData) - 1 ? ',' : '' }} {{--$fire['long'] . ', ' . $fire['lat']--}}
            @endforeach
        ];

        let crowdsourced = [
            @foreach($crowdsourced as $i=>$fire)
            {!! json_encode($fire->only(['id', 'location', 'lat', 'long'])) !!},
            @endforeach
        ];

        // First, checks if it isn't implemented yet.
        // https://stackoverflow.com/a/4673436
        if (!String.format) {
            String.format = function(format) {
                var args = Array.prototype.slice.call(arguments, 1);
                return format.replace(/{(\d+)}/g, function(match, number) {
                    return typeof args[number] != 'undefined'
                        ? args[number]
                        : match
                        ;
                });
            };
        }

        let markerHeight = 50, markerRadius = 10, linearOffset = 25;
        let popupOffsets = {
            'top': [0, 0],
            'top-left': [0,0],
            'top-right': [0,0],
            'bottom': [0, -markerHeight],
            'bottom-left': [linearOffset, (markerHeight - markerRadius + linearOffset) * -1],
            'bottom-right': [-linearOffset, (markerHeight - markerRadius + linearOffset) * -1],
            'left': [markerRadius, (markerHeight - markerRadius) * -1],
            'right': [-markerRadius, (markerHeight - markerRadius) * -1]
        };

        inciFires.forEach(function(element){
            let newMarker = new mapboxgl.Marker()
                .setLngLat([element['long'], element['lat']])
                .addTo(map);

            newMarker.setPopup(new mapboxgl.Popup({offset: popupOffsets, className: element['title']})
                .setLngLat([element['long'], element['lat']])
                .setHTML(String.format("<div class='epic-popup'><a href='{0}'><h5>{1}</h5></a><p>{2}</p></div>", element['link'], element['title'], element['geoname']))
                .addTo(map)
            ).togglePopup();
        });

        crowdsourced.forEach(function(element){
            let newMarker = new mapboxgl.Marker({color: 'red'})
                .setLngLat([element['long'], element['lat']])
                .addTo(map);

            newMarker.setPopup(new mapboxgl.Popup({offset: popupOffsets, className: element['title']})
                .setLngLat([element['long'], element['lat']])
                .setHTML(String.format("<div class='epic-popup'><p>User Report</p><p><a href='#' data-remote='/image/{1}' data-type='image' data-toggle='lightbox'>{0}</a></p></div>", element['location'], element['id']))
                .addTo(map)
            ).togglePopup();
        });

        map.on('load', function() {
            map.addSource('fires', {
                type: 'geojson',
                data: "/heatmap"
            });

            // add heatmap layer here
            map.addLayer({
                id: 'fires-heat',
                type: 'heatmap',
                source: 'fires',
                maxzoom: 15,
                paint: {
                    // increase weight as diameter breast height increases
                    'heatmap-weight': {
                        property: 'dbh',
                        type: 'exponential',
                        stops: [
                            [1, 0],
                            [16, 1]
                        ]
                    },
                    // increase intensity as zoom level increases
                    'heatmap-intensity': {
                        stops: [
                            [11, 1],
                            [15, 3]
                        ]
                    },
                    // assign color values be applied to points depending on their density
                    'heatmap-color': [
                        'interpolate',
                        ['linear'],
                        ['heatmap-density'],
                        0, 'rgba(236,0,0,0)',
                        0.2, 'rgb(208,0,0)',
                        0.4, 'rgb(195,0,0)',
                        0.6, 'rgb(180,0,0)',
                        0.8, 'rgb(166,0,0)'
                    ],
                    // increase radius as zoom increases
                    'heatmap-radius': {
                        stops: [
                            [11, 15],
                            [15, 20]
                        ]
                    },
                    // decrease opacity to transition into the circle layer
                    'heatmap-opacity': {
                        default: 1,
                        stops: [
                            [14, 1],
                            [15, 0]
                        ]
                    },
                }
            }, 'waterway-label');

            // add circle layer here
            map.addLayer({
                id: 'fires-point',
                type: 'circle',
                source: 'fires',
                minzoom: 14,
                paint: {
                    // increase the radius of the circle as the zoom level and dbh value increases
                    'circle-radius': {
                        property: 'dbh',
                        type: 'exponential',
                        stops: [
                            [{ zoom: 15, value: 1 }, 5],
                            [{ zoom: 15, value: 62 }, 10],
                            [{ zoom: 22, value: 1 }, 20],
                            [{ zoom: 22, value: 62 }, 50],
                        ]
                    },
                    'circle-color': {
                        property: 'dbh',
                        type: 'exponential',
                        stops: [
                            [0, 'rgba(236,0,0,0)'],
                            [10, 'rgb(236,0,0)'],
                            [20, 'rgb(208,0,0)'],
                            [30, 'rgb(166,0,0)'],
                            [40, 'rgb(150,0,0)'],
                            [50, 'rgb(128,0,0)'],
                            [60, 'rgb(103,0,0)']
                        ]
                    },
                    'circle-stroke-color': 'white',
                    'circle-stroke-width': 1,
                    'circle-opacity': {
                        stops: [
                            [14, 0],
                            [15, 1]
                        ]
                    }
                }
            }, 'waterway-label');
        });
    </script>
@endsection
