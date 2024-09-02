<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingCharge;
use Carbon\Carbon;
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
        
        // Calculating shipping charge if coupon is applied
        $subTotal = 0.0;
        if(session()->has('coupon_code')){
            $coupon = DiscountCoupon::where('code', session()->get('coupon_code'))->first();
            $amountAfterDiscount = 0.0;
                $shippingChargeAmount = 0.0;
                $subtotalAmountString = Cart::subtotal('2','.',',');
                $subtotalAmountFloat = floatval(str_replace(',', '', $subtotalAmountString));
                $discountAmmount = $coupon->discount_amount;
                //check the type of
                if($coupon->type == 'percent'){
                    // apply dicount on sub-total amount
                    $discount = ($subtotalAmountFloat * $discountAmmount) / 100;
                    $amountAfterDiscount = $subtotalAmountFloat - $discount;
                    // add ship charge
                    $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
                    $shippingAmount = ShippingCharge::select('charges')
                                        ->where('country_id', $customerAddress->country_id)
                                        ->first();
                    
                    if($shippingAmount != null){
                        
                        $shippingCharge = $shippingAmount->charges * Cart::count();
                        
                        $subTotalAmount = $shippingCharge + $amountAfterDiscount;
                        // dd($subTotalAmount, $amountAfterDiscount);
                    } else {
                        $shippingCharge = 0.0;
                        $subTotalAmount = $shippingChargeAmount + $amountAfterDiscount;
                    }
                    
                } else {
                    $discount = $subtotalAmountFloat * $discountAmmount;
                    $amountAfterDiscount = $subtotalAmountFloat - $discount;
                    // add ship charge
                    $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
                    $shippingAmount = ShippingCharge::select('charges')
                                        ->where('country_id', $customerAddress->country_id)
                                        ->first();
                    
                    if($shippingAmount != null){
                        $shippingCharge = $shippingAmount->charges * Cart::count();
                        $subTotalAmount = $shippingCharge + $amountAfterDiscount;
                    }
                    $subTotalAmount = $shippingChargeAmount + $amountAfterDiscount;
                }
            
        } else {

            $customerAddress = CustomerAddress::where('user_id' , Auth::user()->id)->first();
            $shippingInfo = ShippingCharge::where('country_id', $customerAddress->country_id)->first();
            if($shippingInfo != null ){
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
            } else {
                $shippingAmount = 0.0;
                $shippingCharge = 0.0;
                $count = 0;
                if(Cart::count() > 0){
                    $count = Cart::count();
                    $shippingCharge = $count * $shippingAmount;
                    
                }
                $totalString = Cart::subtotal(2, '.', ',');
                $subTotal = floatval(str_replace(',', '', $totalString));
                $subTotalAmount = $subTotal + $shippingCharge;
            }

        }
        

        $countries = Country::orderBy('name', 'ASC')->get();
        $data['countries'] = $countries;
        $data['subTotal'] = $amountAfterDiscount;
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

    public function calculateShippingCharge(Request $request){


        $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();
        // dd($shippingInfo);
        if($shippingInfo == null){
            $shippingCharge = 0.0;
            $totalString = Cart::subtotal(2, '.', ',');
            $subTotal = floatval(str_replace(',', '', $totalString));
            $subTotalAmount = $subTotal + $shippingCharge;

            $user = Auth::user();
            $customerAddress = CustomerAddress::where('user_id', $user->id)->first();
            $customerAddress->country_id = $request->country_id;
            $customerAddress->save();
            
            return response()->json([
                'status' => true,
                'data' => [
                    'subTotalAmount' => number_format($subTotalAmount, 2, '.', ','),
                    'shippingCharge' => number_format($shippingCharge, 2, '.', ','),
                    'subTotal' => number_format($subTotal, 2, '.', ',')
                ],
            ]);
        }
        if(session()->has('coupon_code')){
            $coupon = DiscountCoupon::where('code', session()->get('coupon_code'))->first();
            $amountAfterDiscount = 0.0;
                $shippingChargeAmount = 0.0;
                $subtotalAmountString = Cart::subtotal('2','.',',');
                $subtotalAmountFloat = floatval(str_replace(',', '', $subtotalAmountString));
                $discountAmmount = $coupon->discount_amount;
                //check the type of
                if($coupon->type == 'percent'){
                    // apply dicount on sub-total amount
                    $discount = ($subtotalAmountFloat * $discountAmmount) / 100;
                    $amountAfterDiscount = $subtotalAmountFloat - $discount;
                    $user = Auth::user();
                    $customerAddress = CustomerAddress::where('user_id', $user->id)->first();
                    $customerAddress->country_id = $request->country_id;
                    $customerAddress->save();
                    // add ship charge
                    $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
                    $shippingAmount = ShippingCharge::select('charges')
                                        ->where('country_id', $customerAddress->country_id)
                                        ->first();
                    if($shippingAmount != null){
                        
                        $shippingCharge = $shippingAmount->charges * Cart::count();
                        
                        $subTotalAmount = $shippingCharge + $amountAfterDiscount;
                        // dd($subTotalAmount, $amountAfterDiscount);
                    } else {
                        $shippingCharge = 0.0;
                        $subTotalAmount = $shippingChargeAmount + $amountAfterDiscount; 
                    }

                    // dd()
                    
                    // $subTotalAmount = $shippingChargeAmount + $amountAfterDiscount;
                } else {
                    $discount = $subtotalAmountFloat * $discountAmmount;
                    $amountAfterDiscount = $subtotalAmountFloat - $discount;
                    // add ship charge
                    $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
                    $shippingAmount = ShippingCharge::select('charges')
                                        ->where('country_id', $customerAddress->country_id)
                                        ->first();
                    
                    if($shippingAmount != null){
                        $shippingCharge = $shippingAmount->charges * Cart::count();
                        $subTotalAmount = $shippingCharge + $amountAfterDiscount;
                    }
                    $subTotalAmount = $shippingChargeAmount + $amountAfterDiscount;
                }

                return response()->json([
                    'status' => true,
                    'data' => [
                        'subTotalAmount' => number_format($subTotalAmount, 2, '.', ','),
                        'shippingCharge' => number_format($shippingCharge, 2, '.', ','),
                        'subTotal' => number_format($amountAfterDiscount, 2, '.', ',')
                    ],
                ]);
            
        } else {
        
            $user = Auth::user();
            $customerAddress = CustomerAddress::where('user_id', $user->id)->first();
            $customerAddress->country_id = $request->country_id;
            $customerAddress->save();

            if($shippingInfo != null){
                $shippingAmount = $shippingInfo->charges;

                // calculation
                $shippingCharge = 0.0;
                $count = 0;
                if(Cart::count() > 0){
                    $count = Cart::count();
                    $shippingCharge = $count * $shippingAmount;
                    
                }
                $totalString = Cart::subtotal(2, '.', ',');
                $subTotal = floatval(str_replace(',', '', $totalString));
                $subTotalAmount = $subTotal + $shippingCharge;
                
                return response()->json([
                    'status' => true,
                    'data' => [
                        'subTotalAmount' => $subTotalAmount,
                        'shippingCharge' => $shippingCharge
                    ],
                ]);
                
            } else {

                $shippingCharge = 0.0;
                $count = 0;
                if(Cart::count() > 0){
                    $count = Cart::count();
                    $shippingCharge = $count * 20.0;
                    
                }
                $totalString = Cart::subtotal(2, '.', ',');
                $subTotal = floatval(str_replace(',', '', $totalString));
                $subTotalAmount = $subTotal + $shippingCharge;
                
                return response()->json([
                    'status' => true,
                    'data' => [
                        'subTotalAmount' => $subTotalAmount,
                        'shippingCharge' => $shippingCharge
                    ],
                ]);
            }

        }
        
    }

    public function applyDiscountCoupon(Request $request){
        $coupon = DiscountCoupon::where('code', $request->coupon_code)->first();
        session()->put('coupon_code', $request->coupon_code);
        if(!empty($coupon)){
            
            // 1 check the validity of applied coupon
            $now = Carbon::now();
            $start_at = Carbon::parse($coupon->start_at);

            // if coupon date time is greater than current time that means coupon is not valid for this time
            if($start_at->gt($now)){
                return response()->json([
                    'status' => false,
                    'errors' => ['applied-coupon' => 'Coupon is invalid please check valid interval']
                ]);
            }

            // check coupun expiration
            $end_at = Carbon::parse($coupon->end_at);
            if($end_at->lt($start_at)){
                return response()->json([
                    'status' => false,
                    'errors' => ['applied-coupon' => 'Coupon is expired']
                ]);
            }
            // dd($coupon);
            // check the status
            if($coupon->status == 1 ){
                $amountAfterDiscount = 0.0;
                $shippingChargeAmount = 0.0;
                $subtotalAmountString = Cart::subtotal('2','.',',');
                $subtotalAmountFloat = floatval(str_replace(',', '', $subtotalAmountString));
                $discountAmmount = $coupon->discount_amount;
                //check the type of
                if($coupon->type == 'percent'){
                    // apply dicount on sub-total amount
                    $discount = ($subtotalAmountFloat * $discountAmmount) / 100;
                    $amountAfterDiscount = $subtotalAmountFloat - $discount;
                    // add ship charge
                    $shippingAmount = ShippingCharge::select('charges')
                                        ->where('country_id', $request->country_id)
                                        ->first();
                    
                    if($shippingAmount != null){
                        $shippingChargeAmount = $shippingAmount->charges;
                        $subTotal = $shippingChargeAmount + $amountAfterDiscount;
                    }
                    
                    $subTotal = $shippingChargeAmount + $amountAfterDiscount;
                } else {
                    $discount = $subtotalAmountFloat * $discountAmmount;
                    $amountAfterDiscount = $subtotalAmountFloat - $discount;
                    // add ship charge
                    $shippingAmount = ShippingCharge::select('charges')
                                        ->where('country_id', $request->country_id)
                                        ->first();
                    
                    if($shippingAmount != null){
                        $shippingChargeAmount = $shippingAmount->charges;
                        $subTotal = $shippingChargeAmount + $amountAfterDiscount;
                    }
                }

                // $user = Auth::user();

                return response()->json([
                    'status' => true,
                    'shipping_amount' => number_format($shippingChargeAmount,2,'.',','),
                    'amount_after_discount' => number_format($amountAfterDiscount,2,'.',','),
                    'sub_total_amount' => number_format($subTotal,2,'.',',')
                ]); 
                
            } else {
                return response()->json([
                    'status' => false,
                    'errors' => ['applied-coupon' => 'Coupon is in-active this time']
                ]);
            }

        } else {
            return response()->json([
                'status' => false,
                'message' => 'Coupon in not available'
            ]);
        }

        
    }
    
    public function thankYou($id){
        $orderDetail = Order::find($id);
        $data['order'] = $orderDetail;
        return view('front.thankYou',$data);
    }
    
}
