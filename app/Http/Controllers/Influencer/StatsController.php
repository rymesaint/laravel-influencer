<?php

namespace App\Http\Controllers\Influencer;

use App\Models\Link;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class StatsController
{
    public function index(Request $request)
    {
        $user = $request->user();

        $links = Link::whereUserId($user->id)->get();

        return $links->map(function(Link $link) {
            $orders = Order::whereCode($link->code)->whereComplete(1)
            ->get();

            return [
                'code' => $link->code,
                'count' => $orders->count(),
                'revenue' => $orders->sum(function(Order $order) {
                    return $order->influencer_total;
                }),
            ];
        });
    }

    public function ranking()
    {
        return Redis::zrevrange('rankings', 0, -1, 'WITHSCORES');
    }
}
