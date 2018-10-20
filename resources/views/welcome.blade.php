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
        <div class="container">
            <h3>Wildfire Reporter</h3>
            <p>Report wildfires and save lives with {{ config('app.name') }}</p>
        </div>
    </div>

    <div class="container py-4">
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="mb-4">Report a Fire</h4>
                        <p class="mb-0"><a href="/report" class="btn btn-outline-primary">Report &rsaquo;</a></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="mb-4">Fire List</h4>
                        <p class="mb-0"><a href="/list" class="btn btn-outline-primary">See List &rsaquo;</a></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="mb-4">Fire Notifications</h4>
                        <p class="mb-0"><a href="/subscribe" class="btn btn-outline-primary">Register &rsaquo;</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
