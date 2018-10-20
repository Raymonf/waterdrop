@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>InciWeb Fires</h4>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Location</th>
                <th scope="col">Last Update</th>
            </tr>
            </thead>
            <tbody>
            @if (count($inciWebData) < 1)
                <p>No data</p>
            @endif
            @foreach($inciWebData as $fire)
                <tr>
                    <th scope="row">{{ $fire['title'] }}</th>
                    <td>{{ $fire['geoname'] }}</td>
                    {{-- InciWeb reports with -0500 offset, so add 5 hours to compensate --}}
                    <td>{{ \Carbon\Carbon::createFromTimeString($fire['date'])->addHours(5)->diffForHumans() }}</td>
                </tr>
                </tbody>
            @endforeach
        </table>
    </div>
@endsection