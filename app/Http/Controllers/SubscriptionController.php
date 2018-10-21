<?php

namespace App\Http\Controllers;

use App\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    protected $locations = [
        'all' => [0.0, 0,0]
    ];

    public function subscribe($location, $token)
    {
        if (!array_key_exists($location, $this->locations)) {
            abort(400);
        }

        // TODO: Validate token
        $exists = Subscription::where('location', $location)
            ->where('token', $token)->count() > 0;

        if (!$exists) {
            Subscription::create([
                'location' => $location,
                'token' => $token
            ]);
        }
    }
}
