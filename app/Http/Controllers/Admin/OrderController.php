<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;

class OrderController
{
    public function index()
    {
        Gate::authorize('view', 'orders');
        $orders = Order::paginate();

        return OrderResource::collection($orders);
    }

    public function show($id)
    {
        Gate::authorize('view', 'orders');
        return new OrderResource(Order::find($id));
    }

    public function export()
    {
        Gate::authorize('view', 'orders');
        $headers = [
            'content-type' => 'text/csv',
            'content-disposition' => 'attachment; filename=orders.csv',
            'pragma' => 'no-cache',
            'cache-control' => 'must-revalidate, post-check=0, pre-check=0',
            'expires' => '0',
        ];

        $callback = function() {
            $orders = Order::all();
            $file = fopen('php://output', 'w');
            
            // Header Row
            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Product Title',
                'Price',
                'Quantity'
            ]);
            
            // Body
            foreach($orders as $order) {
                fputcsv($file, 
                [
                    $order->id,
                    $order->name,
                    $order->email,
                    '',
                    '',
                    ''
                ]);

                foreach($order->orderItems as $orderItem) {
                    fputcsv($file, ['', '', '', $orderItem->product_title, $orderItem->price, $orderItem->quantity]);
                }
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
