<?php

use App\Mail\OrderMail;
use App\Models\Category;
use App\Models\Order;
use App\Models\ProductImage;
// use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Mail;

    function getCategories(){
        return Category::orderBy('name', 'ASC')
                        ->where('show_home', 'Yes')
                        ->where('status', 1)
                        ->with('getSubCategories')
                        ->get();
    }

    function getProductImage($id,){
        return ProductImage::select('image')->where('product_id', $id)->first();
    }   

    // send email funtion
    function sendOrderEmail($id, $type ){
        $order = Order::where('id',$id)->with('getOrderItems')->first();
        $email = '';
        if($type == 'customer'){
            $mailData = [
                'subject' => 'Order Detail',
                'order' => $order,
                'type' => $type
            ]; 
             $email = $order->email;
        }

        if($type == 'admin'){
            $mailData = [
                'subject' => 'Order Detail',
                'order' => $order,
                'type' => $type
            ];
            
            $email = env('ADMIN_EMAIL');
        }
        Mail::to($email)->send(new OrderMail($mailData));
    }
?>