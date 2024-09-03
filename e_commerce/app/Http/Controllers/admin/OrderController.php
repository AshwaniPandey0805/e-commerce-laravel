<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
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
}
