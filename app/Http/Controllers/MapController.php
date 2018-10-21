<?php

namespace App\Http\Controllers;

use App\Jobs\InciWebCacher;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MapController extends Controller
{
    public function index()
    {
        $inciWebData = Cache::has('fires') ? Cache::get('fires') : null;
        if ($inciWebData == null) {
            $cacher = new InciWebCacher();
            $this->dispatch($cacher);
            $inciWebData = Cache::get('fires');
        }
        $crowdsourced = Report::latest()->limit(30)->get();

        return view('map', compact('inciWebData', 'crowdsourced'));
    }

    private function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    public function heatmap()
    {
        $response = '{"type":"FeatureCollection","features":[';

        $items = Report::latest()->limit(30)->get();
        $count = $items->count();

        for ($i = 0; $i < $count; $i++)
        {
            $item = $items[$i];
            if ($item->lat && $item->long && floatval($item->lat) !== false && floatval($item->long) !== false) {
                $response .= '{"type":"Feature","properties":{"dbh":12},"geometry":{"type":"Point","coordinates":[' . $item->long . ', ' . $item->lat . ']}}';
                if ($i < $count - 1) {
                    $response .= ',';
                }
            }
        }

        if ($this->endsWith($response, ',')) {
            $response = substr($response, 0, strlen($response) - 1);
        }

        $response .= ']}';

        return response($response)->header('Content-Type', 'application/json');
    }
}
