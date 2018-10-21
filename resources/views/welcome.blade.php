@extends('layouts.app')

@section('header')
    <style>
        main.py-4 {
            padding-top: 0 !important;
        }
    </style>
@endsection

@section('content')
    <div class="jumbotron">
        {{--<div class="container">--}}
            {{--<iframe src="https://player.vimeo.com/video/245530636?title=0&portrait=0&byline=0&autoplay=1&loop=1" allow="autoplay"></iframe>--}}
        {{--</div>--}}
        <div class="container">
            <div class="row">
                <div class="col-md-4 order-md-2 text-md-right mb-4 mb-md-0">
                    <img src="/img/waterdrop.png" alt="{{ config('app.name') }} logo" style="height: 80px">
                </div>
                <div class="col-md-8 order-md-1">
                    <h3>{{ config('app.name') }} Wildfire Reporter</h3>
                    <p>Report wildfires and help save lives with {{ config('app.name') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="mb-4">Report a Fire</h4>
                        <p>Report a wildfire and warn others</p>
                        <p class="mb-0"><a href="/report" class="btn btn-outline-primary">Report &rsaquo;</a></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="mb-4">Fire List</h4>
                        <p>A list of crowdsourced and confirmed data</p>
                        <p class="mb-0"><a href="/list" class="btn btn-outline-primary">See List &rsaquo;</a></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="mb-4">Fire Notifications</h4>
                        <p class="text-muted">Coming soon...</p>
                        <p class="mb-0"><a href="/subscribe" class="btn btn-outline-primary disabled">Register &rsaquo;</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
