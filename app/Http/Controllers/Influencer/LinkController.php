<?php

namespace App\Http\Controllers\Influencer;

use App\Http\Resources\LinkResource;
use App\Models\Link;
use App\Models\LinkProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LinkController
{
    public function store(Request $request)
    {
        $link = Link::create([
            'user_id' => $request->user()->id,
            'code' => Str::random(6),
        ]);

        foreach($request->input('products') as $productId) {
            LinkProduct::create([
                'link_id' => $link->id,
                'product_id' => $productId,
            ]);
        }

        return new LinkResource($link);
    }
}
