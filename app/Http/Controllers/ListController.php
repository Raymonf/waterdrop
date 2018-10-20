<?php

namespace App\Http\Controllers;

use App\Jobs\InciWebCacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ListController extends Controller
{
    public function index()
    {
        $inciWebData = Cache::has('fires') ? Cache::get('fires') : null;
        if ($inciWebData == null) {
            $cacher = new InciWebCacher();
            $this->dispatch($cacher);
            $inciWebData = Cache::get('fires');
        }

        return view('list', compact('inciWebData'));
    }
}
