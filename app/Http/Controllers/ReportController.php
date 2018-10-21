<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Rules\LatLong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        return view('report');
    }

    public function store(Request $request)
    {
        $request->validate([
            'location' => 'required|string|min:4',
            'photo' => 'required|image|file',
            //'lat' => ['required', 'string', new LatLong(true)],
            //'long' => ['required', 'string', new LatLong(false)],
            'lat' => 'required|string',
            'long' => 'required|string',
        ]);

        // TODO: File validation
        // Make sure it's a valid photo

        $report = Report::create([
            'location' => $request->location,
            'ip_address' => $request->ip(),
            'lat' => $request->lat,
            'long' => $request->long
        ]);

        foreach (Report::get() as $person) {
            $this->notify($person->token, config('app.fcm_key'));
        }

        $path = $request->file('photo')->storeAs(
            'public', 'fireimg' . $report->id
        );

        flash('Successfully reported! It is now visible to other people.', 'success');

        return redirect('/list');
    }

    public function image(Report $report)
    {
        return response()->download(storage_path('app/public/fireimg' . $report->id));
    }

    public function notify($location, $token)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $message = [
            'title' => config('app.name') . ' - Wildfire Reported',
            'text' => 'Warning: A fire was reported at ' . $location . '. Please be careful.'
        ];
        $fields = array(
            'to' => $token,
            'notification' => $message
        );
        $headers = array(
            'Authorization: key=' . config('app.fcm_key'),
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL,$url);
        curl_setopt( $ch,CURLOPT_POST,true);
        curl_setopt( $ch,CURLOPT_HTTPHEADER,$headers);
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt( $ch,CURLOPT_POSTFIELDS,json_encode($fields));
        $result = curl_exec($ch);
        Log::info($result);
        curl_close($ch);
    }
}
