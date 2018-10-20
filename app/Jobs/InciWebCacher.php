<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Cache;

class InciWebCacher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    protected $response = [];

    private function getGeoName($lat, $long)
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => config('app.lookup_url') . '/lookup/' . $lat . '/' . $long,
            CURLOPT_RETURNTRANSFER => true
        ]);

        return curl_exec($ch);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $xml = simplexml_load_string(file_get_contents('https://inciweb.nwcg.gov/feeds/rss/incidents/'));

        $fires = [];
        $oldFires = collect(Cache::has('fires') ? Cache::get('fires') : []);

        foreach ($xml->channel->item as $item) {
            $geo = $item->children('geo', true);

            if (str_contains($item->title, ' (Wildfire)')) {
                $title = str_replace(' (Wildfire)', '', (string) $item->title);
                $geoRes = json_decode($this->getGeoName((string) $geo->lat, (string) $geo->long));
                $geoName = $geoRes->success ? $geoRes->name : null;

                $fires[] = [
                    'title' => $title,
                    'date' => (string) $item->pubDate,
                    'description' => trim(str_replace('   ', "\n", (string) $item->description)),
                    'link' => (string) $item->link,
                    'lat' => (string) $geo->lat,
                    'long' => (string) $geo->long,
                    'geoname' => $geoName ?? 'Unknown',
                    'new' => $oldFires->where('title', $title)->count() < 1
                ];
            }
        }

        Cache::put('fires', $fires, 60);

        $this->response = $fires;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
