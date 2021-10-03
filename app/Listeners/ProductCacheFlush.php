<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class ProductCacheFlush
{
    
    public function handle($event)
    {
        Cache::forget('products');
    }
}
