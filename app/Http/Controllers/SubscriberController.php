<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriberRequest;
use App\Subscriber;

class SubscriberController extends Controller
{
    public function store(SubscriberRequest $request)
    {
        $subscriber = Subscriber::where('email', $request->get('email'))->first();

        if (!$subscriber) {
            $subscriber = new Subscriber();
        }

        return $subscriber->store($request);
    }
}
