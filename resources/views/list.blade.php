@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css">
@endsection

@section('content')
    <div class="container">
        <h4>Crowdsourced Fires</h4>
        <p>Possible fires, designed to alert citizens quickly</p>
        <table class="table table-responsive w-100 d-block d-md-table mb-5">
            <thead>
            <tr>
                <th scope="col">Location</th>
                <th>Report Time</th>
                <th class="text-center">Image</th>
                <th class="text-center">Vote</th>
            </tr>
            </thead>
            <tbody>
            @foreach($crowdsourced as $data)
                <tr>
                    <td>{{ $data->location }}</td>
                    <td>{{ $data->created_at->diffForHumans() }}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-primary btn-sm" data-remote="{{ url('/image/' . $data->id) }}" data-type="image" data-toggle="lightbox">
                            <i class="fas fa-camera"></i>
                        </button>
                    </td>
                    <td class="text-center">
                        <Voter class="text-center" type="report" id="{{ $data->id }}"
                            _status="{{ optional($data->votes()->where('user_id', auth()->id() ?? -1)->first())->status ?? 0 }}"
                            _upvotes="{{ $data->votes()->where('status', 1)->count() }}"
                            _downvotes="{{ $data->votes()->where('status', -1)->count() }}"
                            _votable="{{ auth()->check() ? 'true' : 'false' }}">
                        </Voter>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <h4>InciWeb Fires</h4>
        <p>Confirmed wildfires with detailed reports available</p>
        <table class="table table-responsive w-100 d-block d-md-table">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Location</th>
                <th scope="col">Last Update</th>
                <th scope="col">Full Report</th>
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
                    <td><a href="{{ $fire['link'] }}">Report &rsaquo;</a></td>
                </tr>
                </tbody>
            @endforeach
        </table>
    </div>
@endsection

@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
    <script>
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });
    </script>
@endsection