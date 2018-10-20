@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
          integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
          crossorigin=""/>
    <style>
        #map {
            height: calc(100vh - 55px);
        }
        main.py-4 {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>
@endsection

@section('footer')
    <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
            integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
            crossorigin=""></script>
    <script>
        var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            osmAttrib = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            osm = L.tileLayer(osmUrl, {maxZoom: 18, attribution: osmAttrib});

        var map = L.map('map').setView([51.505, -0.159], 15).addLayer(osm);

        L.marker([51.504, -0.159])
            .addTo(map)
            .bindPopup('A pretty CSS3 popup.<br />Easily customizable.')
            .openPopup();
    </script>
@endsection