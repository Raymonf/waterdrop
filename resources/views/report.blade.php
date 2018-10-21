@extends('layouts.app')

@section('header')
    <style>
        main.py-4 {
            padding-top: 0 !important;
        }
    </style>
@endsection

@section('basic_content')
    <div class="container">
        <h3 class="mb-4">Report a Fire</h3>

        <form method="post" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="form-group mb-2">
                <label for="city"><strong>Location of Wildfire</strong></label>
                <p><button class="btn btn-outline-primary" type="button" id="useCurrentLocation">Use Current Location</button></p>
                <input type="search" id="city" class="form-control{{ $errors->has('location') ? ' is-invalid' : '' }}" name="location" placeholder="Where was the fire spotted?" />
                @if ($errors->has('location'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('location') }}</strong>
                    </span>
                @endif
            </div>

            <label for="photo" class="mt-4"><strong>Upload Photo of Wildfire</strong></label>
            <div class="form-group">
                <input type="file" class="form-control{{ $errors->has('photo') ? ' is-invalid' : '' }}" name="photo" id="photo">
                @if ($errors->has('photo'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('photo') }}</strong>
                    </span>
                @endif
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
@endsection

@section('footer')
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.11.0"></script>
    <script>
        var options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        };

        function success(pos) {
            var crd = pos.coords;

            axios.post('/lookup/' + crd.latitude + '/' + crd.longitude)
                .then((response) => {
                    console.log('Setting value to ' + response.data.name)
                    document.getElementById('city').value = response.data.name;
                })
                .catch(function (error) {
                    alert("Unable to reverse geocode")
                });
        }

        function error(err) {
            console.warn(`ERROR(${err.code}): ${err.message}`);
            alert("Unable to get current location. Please accept the permission.");
        }

        document.getElementById('useCurrentLocation').onclick = function () {
            navigator.geolocation.getCurrentPosition(success, error, options);
        };

        (function() {
            var placesAutocomplete = places({
                container: document.querySelector('#city'),
                type: 'city',
                apiKey: '{{ config('app.algolia_key') }}',
                useDeviceLocation: true,
                templates: {
                    value: function(suggestion) {
                        return suggestion.name + ", " + suggestion.county + ", " + suggestion.administrative;
                    }
                }
            });
        })();
    </script>
@endsection