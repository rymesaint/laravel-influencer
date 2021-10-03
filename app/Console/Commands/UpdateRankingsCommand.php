<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class UpdateRankingsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:rankings';

    public function handle()
    {
        
        $users = User::whereIsInfluencer(1)->get();

        $users->each(function(User $user) {
            $orders = Order::whereUserId($user->id)->whereComplete(1)
            ->get();
            $revenue = $orders->sum(function(Order $order) {
                return $order->influencer_total;
            });

            Redis::zadd('rankings', $revenue, $user->full_name);
        });

    }
}
