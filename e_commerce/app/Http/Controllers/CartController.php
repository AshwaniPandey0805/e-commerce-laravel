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
        $amountAfterDiscount = 0.0;
        $shippingCharge = 0.0;
        $html = '';
        $subtotalAmountString = Cart::subtotal('2','.',',');
        $subTotal = floatval(str_replace(',', '', $subtotalAmountString));
        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
        $shippingAmount = ShippingCharge::select('charges')
                            ->where('country_id', $customerAddress->country_id)
                            ->first();
        
        if(session()->has('coupon_code')){
            $coupon = DiscountCoupon::where('code', session()->get('coupon_code'))->first();
            $discountAmmount = $coupon->discount_amount;
            if($coupon->type == 'percent'){
                $discount = ($subTotal * $discountAmmount) / 100;
                $amountAfterDiscount = $subTotal - $discount;
            } else {
                $amountAfterDiscount = $subTotal - $discountAmmount;
                // $amountAfterDiscount = $subTotal - $discount;
            }
            if($shippingAmount != null){
                $shippingCharge = $shippingAmount->charges * Cart::count();
                $subTotalAmount = $shippingCharge + $amountAfterDiscount;
            } else {
                $subTotalAmount = $shippingCharge + $amountAfterDiscount;
            }
            $html = '<div id="coupon_list" class="mt-4">
                            <strong>'.session()->get('coupon_code').'</strong>
                            <a href="" class="btn btn-sm btn-danger" id="remove_coupon" ><i class="fa fa-times"></i></a>
                        </div>';
        } else {
            if($shippingAmount != null ){
                $count = 0;
                if(Cart::count() > 0){
                    $count = Cart::count();
                    $shippingCharge = $count * $shippingAmount->charges;
                }
            } else {
                $count = 0;
                if(Cart::count() > 0){
                    $count = Cart::count();
                    $shippingCharge = $count * 0.0;
                }
            }
            $subTotalAmount = $subTotal + $shippingCharge;

        }
        
        // dd($amountAfterDiscount);
        $countries = Country::orderBy('name', 'ASC')->get();
        $data['countries'] = $countries;
        $data['subTotal'] = ( $amountAfterDiscount > 0.0 ) ? $amountAfterDiscount : $subTotal  ;
        $data['customerAddress'] = $customerAddress;
        $data['shippingCharge'] = $shippingCharge;
        $data['subTotalAmount'] = $subTotalAmount;
        $data['html'] = $html;
         
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
            $shippingCharge = 0.0;
            $customerAddress = CustomerAddress::where('user_id' , Auth::user()->id)->first();
            $shippingInfo = ShippingCharge::where('country_id', $customerAddress->country_id)->first();
            $shippingAmount = ( $shippingInfo != null ) ? $shippingInfo->charges : 0.0;
            $count = 0;
            if(Cart::count() > 0){
                $count = Cart::count();
                $shippingCharge = $count * $shippingAmount;
            }
            $totalString = Cart::subtotal(2, '.', ',');
            $subTotal = floatval(str_replace(',', '', $totalString));
            $subTotalAmount = $subTotal + $shippingCharge;
            $discount = 0.0;
            $amountAfterDiscount = 0.0;
            // Save order Details
            if($request->payment_method == 'cod'){

                if(session()->has('coupon_code')){
                    $coupon = DiscountCoupon::where('code', session()->get('coupon_code'))->first();
                    $discountAmmount = $coupon->discount_amount;
                    if($coupon->type == 'percent'){
                        $discount = ($subTotal * $discountAmmount) / 100;
                        $amountAfterDiscount = $subTotal - $discount;
                    } else {
                        $amountAfterDiscount = $subTotal - $discountAmmount;
                        // $amountAfterDiscount = $subTotal - $discount;
                    }

                    if($shippingAmount != null){
                        // $shippingCharge = $shippingAmount->charges * Cart::count();
                        $subTotalAmount = $shippingCharge + $amountAfterDiscount;
                    } else {
                        $subTotalAmount = $shippingCharge + $amountAfterDiscount;
                    }
                } 
                
                $shipping  =  $shippingCharge;
                $discountPrice  = $discount;  
                $subTotal = ( $amountAfterDiscount > 0.0 ) ? $amountAfterDiscount : Cart::subtotal(2,'.','');
                $grandtotal = $subTotalAmount ;

                $order = new Order();
                $order->user_id = $user->id;
                $order->subtotal = $subTotal;
                $order->shipping = $shipping;
                $order->discount = $discountPrice;
                $order->grand_total = $grandtotal;
                $order->coupon_code = $request->applied_coupon;
                
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

            session()->forget('coupon_code');
            $request->session()->flash('success', 'Order saved successfully');
            Cart::destroy();
            //send email to user
            sendOrderEmail($order->id, 'customer');
            return response()->json([
                'status' => true,
                'message' => 'Order saved successfully',
                'order_id' => $order->id,
            ]);

            

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Something went wrong'
            ]);
        }
    }

    public function calculateShippingCharge(Request $request){

        // 1. check shipping info is available in database
        $shippingInfo = ShippingCharge::where('country_id', $request->country_id)->first();

        // common
        $shippingCharge = 0.0;
        $amountAfterDiscount = 0.0;
        $shippingChargeAmount = 0.0;

        $user = Auth::user();
        $totalString = Cart::subtotal(2, '.', ',');
        $subTotal = floatval(str_replace(',', '', $totalString));
        $customerAddress = CustomerAddress::where('user_id', $user->id)->first();
        $customerAddress->country_id = $request->country_id;
        $customerAddress->save();
        $shippingAmount = ShippingCharge::select('charges')
                            ->where('country_id', $customerAddress->country_id)
                            ->first();

        if($shippingInfo == null){
            // dd("1");
            if(session()->has('coupon_code')){
                $coupon = DiscountCoupon::where('code', session()->get('coupon_code'))->first();
                $discountAmmount = $coupon->discount_amount;
                if($coupon->type == 'percent'){
                    $discount = ($subTotal * $discountAmmount) / 100;
                    $amountAfterDiscount = $subTotal - $discount;
                } else {
                    $amountAfterDiscount = $subTotal - $discountAmmount;
                    // $amountAfterDiscount = $subTotal - $discount;
                }

                if($shippingAmount != null){
                    $shippingCharge = $shippingAmount->charges * Cart::count();
                    $subTotalAmount = $shippingCharge + $amountAfterDiscount;
                } else {
                    $subTotalAmount = $shippingChargeAmount + $amountAfterDiscount; 
                }
            } else {
                $amountAfterDiscount = $subTotal; 
                $subTotalAmount = $subTotal + $shippingCharge;
            }

            $html = '<div id="coupon_list" class="mt-4">
                            <strong>'.session()->get('coupon_code').'</strong>
                            <a href="" class="btn btn-sm btn-danger" id="remove_coupon" ><i class="fa fa-times"></i></a>
                        </div>';
            
            return response()->json([
                'status' => true,
                'data' => [
                    'subTotalAmount' => number_format($subTotalAmount, 2, '.', ','),
                    'shippingCharge' => number_format($shippingCharge, 2, '.', ','),
                    'subTotal' => number_format($amountAfterDiscount, 2, '.', ','),
                    'html' => $html
                ],
            ]);
        }

        if(session()->has('coupon_code')){
            $coupon = DiscountCoupon::where('code', session()->get('coupon_code'))->first();
            $discountAmmount = $coupon->discount_amount;
            if($coupon->type == 'percent'){
                $discount = ($subTotal * $discountAmmount) / 100;
                $amountAfterDiscount = $subTotal - $discount;
            } else {
                $amountAfterDiscount = $subTotal - $discountAmmount;
                // $amountAfterDiscount = $subTotal - $discount;
            }
            if($shippingAmount != null){
                $shippingCharge = $shippingAmount->charges * Cart::count();
                $subTotalAmount = $shippingCharge + $amountAfterDiscount;
            } else {
                $subTotalAmount = $shippingChargeAmount + $amountAfterDiscount; 
            }
            $html = '<div id="coupon_list" class="mt-4">
                            <strong>'.session()->get('coupon_code').'</strong>
                            <a href="" class="btn btn-sm btn-danger" id="remove_coupon" ><i class="fa fa-times"></i></a>
                        </div>';
            return response()->json([
                'status' => true,
                'data' => [
                    'subTotalAmount' => number_format($subTotalAmount, 2, '.', ','),
                    'shippingCharge' => number_format($shippingCharge, 2, '.', ','),
                    'subTotal' => number_format($amountAfterDiscount, 2, '.', ','),
                    'html' => $html
                ],
            ]);
        } else {
            $shippingAmount = $shippingInfo->charges;
            $count = 0;
            if(Cart::count() > 0){
                $count = Cart::count();
                $shippingCharge = $count * $shippingAmount;
            }
            $subTotalAmount = $subTotal + $shippingCharge;
            return response()->json([
                'status' => true,
                'data' => [
                    'subTotalAmount' => number_format($subTotalAmount, 2, '.', ','),
                    'shippingCharge' => number_format($shippingCharge, 2, '.', ','),
                    'subTotal' => number_format($subTotal, 2, '.', ',')
                ],
            ]);
        }
    }

    public function applyDiscountCoupon(Request $request){
        
        $coupon = DiscountCoupon::where('code', $request->coupon_code)->first();
        
        if(!empty($coupon)){
            
            $amountAfterDiscount = 0.0;
            $shippingChargeAmount = 0.0;
            $subtotalAmountString = Cart::subtotal('2','.',',');
            $subtotalAmountFloat = floatval(str_replace(',', '', $subtotalAmountString));
            $shippingAmount = ShippingCharge::select('charges')
                                ->where('country_id', $request->country_id)
                                ->first();

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

            // check the limit max_uses for the user can use the coupon 
            $coupon_uses = Order::where('coupon_code', $coupon->code)
                                ->where('user_id', Auth::user()->id )
                                ->count();
            if($coupon->max_uses > 0){
                if($coupon_uses > $coupon->max_uses){
                    return response()->json([
                        'status' => false,
                        'errors' => ['applied-coupon' => Auth::user()->name.' exceeded the coupon usage limit']
                    ]);
                }
            }

            // check the limit number of users can use the coupon
            $max_users_coupon = Order::where('coupon_code', $coupon->code)->count();
            if($coupon->max_user_uses > 0){
                if($max_users_coupon > $coupon->max_user_uses ){
                    return response()->json([
                        'status' => false,
                        'errors' => ['applied-coupon' => 'Coupon usage limit exceeded']
                    ]);
                }
            }
              
            // check for minimum amount 
            $min_amount = $coupon->min_amount;
            if($subtotalAmountFloat > 0){
                if($subtotalAmountFloat < $min_amount ){
                    return response()->json([
                        'status' => false,
                        'errors' => ['applied-coupon' => 'Minimum amount must be $'.$coupon->min_amount.' to apply this coupon']
                    ]);
                }
            }
            
            //  After All validation apply copoun
            session()->put('coupon_code', $request->coupon_code);
            // check the status
            if($coupon->status == 1 ){
                
                $discountAmmount = $coupon->discount_amount;
                //check the type of
                if($coupon->type == 'percent'){
                    
                    $discount = ($subtotalAmountFloat * $discountAmmount) / 100;
                    $amountAfterDiscount = $subtotalAmountFloat - $discount;
                } else {
                    
                    $amountAfterDiscount = $subtotalAmountFloat - $discountAmmount; // 1140-20 = 1120()
                    // $amountAfterDiscount = $subtotalAmountFloat - $discount;
                }
                // add ship amount
                if($shippingAmount != null){
                    $shippingChargeAmount = $shippingAmount->charges * Cart::count(); // 10 * 2 = 20
                    $subTotal = $shippingChargeAmount + $amountAfterDiscount; // 20 + 1120 = 1400
                } else {
                    $subTotal = $shippingChargeAmount + $amountAfterDiscount;
                }
                
                $html = '<div id="coupon_list" class="mt-4">
                            <strong>'.session()->get('coupon_code').'</strong>
                            <a href="" class="btn btn-sm btn-danger" id="remove_coupon" ><i class="fa fa-times"></i></a>
                        </div>';
                return response()->json([
                    'status' => true,
                    'shipping_amount' => number_format($shippingChargeAmount, 2, '.', ','),
                    'amount_after_discount' => number_format($amountAfterDiscount, 2, '.', ','),
                    'sub_total_amount' => number_format($subTotal, 2, '.', ','),
                    'html' => $html
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

    public function removeDiscountCoupon(Request $request){
        // dd($request->all());
        session()->forget('coupon_code');
        return response()->json([
            'status' => true,
            'message' => 'coupon deleted',
        ]);
    }
    
    public function thankYou($id){
        $orderDetail = Order::find($id);
        $data['order'] = $orderDetail;
        return view('front.thankYou',$data);
    }
    
}
