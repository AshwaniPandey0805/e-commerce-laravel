<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingCharge;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Colors\Rgb\Channels\Red;

class CartController extends Controller
{
    public function addToCart(Request $request){
        $product = Product::where('id', $request->id)->with('product_images')->first();
        $status = false;
        $message = '';
        if($product == null){
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }
        
        if(Cart::count() > 0){
            $cartContent = Cart::content();
            $productAlreadyExist = false;
            
            foreach ($cartContent as $item) {
                if($item->id == $product->id){
                    // dd($item->id, $product->id);
                    $productAlreadyExist = true;
                }
            }
            
            if($productAlreadyExist ==  false) {
                // add product to cart
                Cart::add( 
                    $product->id, 
                    $product->title, 1, 
                    $product->price, 
                    [
                        'productImage' => (!empty($product->product_images))  ? $product->product_images->first() : '' 
                    ]);

                    $status = true;
                    $message = $product->title.' added to cart ';

            } else {
                $status = false;
                $message = $product->title.' product already exist in cart'; 
            }

        } else {
            // cart is empty
            Cart::add( 
                $product->id, 
                $product->title, 1, 
                $product->price, 
                [
                    'productImage' => (!empty($product->product_images))  ? $product->product_images->first() : '' 
                ]);

                $status = true;
                $message = $product->title.' added to cart ';
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function cart(){
        $cartContents = Cart::content();
        // dd($cartContents);
        $cartSubTotal = Cart::subtotal();
        $data['cartContents'] = $cartContents;
        $data['cartSubTotal'] = $cartSubTotal;
        return view('front.cart', $data);
    }


    public function updateCart(Request $request){
        $rowId = $request->rowId;
        $qty = $request->qty;

        $cartProduct = Cart::get($rowId);
        // dd($cartProduct->id);
        $product = Product::find($cartProduct->id);
        
        if($request->qty <= $product->qty){
            Cart::update($rowId, $qty);
            $message = 'cart updated successfully';
            $request->session()->flash('success',$message);
        } else {
            $message = 'Maximum qunatity limit for'.$product->title.' product is : '.$product->qty;
            $request->session()->flash('error',$message);
        }

        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }


    public function deleteCart(Request $request){
        // dd($request->rowId);
        $rowId = $request->rowId;
        if($rowId == null){
            return response()->json([
                'status' => false,
                'message' => 'Cart not found'
            ]);
        }
        $tempInfo = Cart::get($rowId);
        if(empty($tempInfo)){
            $request->session()->flash('error', 'Cart not found');
            return response()->json([
                'status' => true,
                'message' => 'Cart not found'
            ]);
        }
        Cart::remove($tempInfo->rowId);
        $request->session()->flash('success', 'Cart deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Cart deleted successfully'
        ]);
        

    }

    public function checkout(){

        // if cart is empty
        if(Cart::count() == 0){
            return redirect()->route('front.cart');
        }

        if(Auth::check() == false){
            if(!session()->has('url.intended')){
                session(['url.intended' => url()->current()]);
            }
            return redirect()->route('front.login');
        }

        session()->forget('url.intented');
        // dd(Auth::user()->id);
        
        // Calculating shipping charge
        $customerAddress = CustomerAddress::where('user_id' , Auth::user()->id)->first();
        $shippingInfo = ShippingCharge::where('country_id', $customerAddress->country_id)->first();
        $shippingAmount = $shippingInfo->charges;
        $shippingCharge = 0.0;
        $count = 0;
        if(Cart::count() > 0){
            $count = Cart::count();
            $shippingCharge = $count * $shippingAmount;
            
        }
        // $subTotalAmount = (double)(floatval(str_replace(',', '.', Cart::subtotal()))) + $shippingAmount;
        // dd($subTotalAmount);
        // Get the total as a formatted string
        $totalString = Cart::subtotal(2, '.', ',');
        $subTotal = floatval(str_replace(',', '', $totalString));
        $subTotalAmount = $subTotal + $shippingCharge;
        $countries = Country::orderBy('name', 'ASC')->get();
        $data['countries'] = $countries;
        $data['customerAddress'] = $customerAddress;
        $data['shippingCharge'] = $shippingCharge;
        $data['subTotalAmount'] = $subTotalAmount;
         
        return view('front.checkout', $data);
    }

    public function checkoutProcess(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required',
            'appartment' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
        ]);

        if($validator->passes()){

            //save user address

            $user = Auth::user();

            CustomerAddress::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'country_id' => $request->country,
                    'address' => $request->address,
                    'apartement' => $request->appartment,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip_code' => $request->zip,
                ]
            );

            // Calculating shipping charge
            $customerAddress = CustomerAddress::where('user_id' , Auth::user()->id)->first();
            $shippingInfo = ShippingCharge::where('country_id', $customerAddress->country_id)->first();
            $shippingAmount = $shippingInfo->charges;
            $shippingCharge = 0.0;
            $count = 0;
            if(Cart::count() > 0){
                $count = Cart::count();
                $shippingCharge = $count * $shippingAmount;
                
            }
            $totalString = Cart::subtotal(2, '.', ',');
            $subTotal = floatval(str_replace(',', '', $totalString));
            $subTotalAmount = $subTotal + $shippingCharge;
            // Save order Details
            if($request->payment_method == 'cod'){
                $shipping  =  $shippingCharge;
                $discount  =  0;
                $subTotal = Cart::subtotal(2,'.','');
                $grandtotal = $subTotalAmount ;

                $order = new Order();
                $order->user_id = $user->id;
                $order->subtotal = $subTotal;
                $order->shipping = $shipping;
                $order->discount = $discount;
                $order->grand_total = $grandtotal;
                
                $order->first_name = $request->first_name;
                $order->last_name = $request->last_name;
                $order->email = $request->email;
                $order->mobile = $request->mobile;
                $order->country_id = $request->country;
                $order->address = $request->address;
                $order->apartement = $request->appartment;
                $order->city = $request->city;
                $order->state = $request->state;
                $order->zip_code = $request->zip;
                $order->save();

                
            }
            // save ordered_item details
            foreach (Cart::content() as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = ($item->price * $item->qty);
                $orderItem->save();
            }

            $request->session()->flash('success', 'Order saved successfully');
            Cart::destroy();
            return response()->json([
                'status' => true,
                'message' => 'Order saved successfully',
                'order_id' => $order->id,
            ]);

            //

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Something went wrong'
            ]);
        }
    }
    
    public function thankYou($id){
        $orderDetail = Order::find($id);
        $data['order'] = $orderDetail;
        return view('front.thankYou',$data);
    }
    
}
