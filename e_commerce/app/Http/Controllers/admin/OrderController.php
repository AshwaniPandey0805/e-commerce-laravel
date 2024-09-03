<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        $orders = Order::latest('orders.created_at')->with('getUsers')->paginate(10);
        $data['orders'] = $orders;
        return view('admin.order.list', $data);
    }

    public function detail($id, Request $request){
        $order = Order::where('id', $id)->first();
        $orderItems = OrderItem::where('order_id', $order->id)->with('products.product_images')->get();
        $data['order'] = $order;
        $data['orderItems'] = $orderItems;
        return view('admin.order.orderDetails', $data);
    }

    public function update($id, Request $request){
        if(!isset($request->shipped_date)){
            return response()->json([
                'status' => false,
                'error' => ['shipped_date' => 'Please select shipping date and time'] 
            ]);
        }
        $now = Carbon::now();
        $shipped_date = Carbon::parse($request->shipped_date);
        if($shipped_date->lt($now)){
            return response()->json([
                'status' => false,
                'error' => ['shipped_date' => 'shipping date must be greater than current date and time'] 
            ]);
        }
        
        $order = Order::find($id);
        if(!isset($order)){
            return response()->json([
                'status' => false,
                'error' => ['shipped_date' => 'Order is not available']
            ]);
        }
        $order->status = $request->order_status;
        $order->shipped_date = Carbon::parse($request->shipped_date)->format("Y-m-d H:i:s");
        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'shipping date updated',
            'order_id' => $order->id
        ]);
    }
}
