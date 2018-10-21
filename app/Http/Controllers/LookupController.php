<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LookupController extends Controller
{
    public function lookup($lat, $long)
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => config('app.lookup_url') . '/lookup/' . $lat . '/' . $long,
            CURLOPT_RETURNTRANSFER => true
        ]);

        return curl_exec($ch);
    }
}
