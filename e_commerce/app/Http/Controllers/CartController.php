<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $countries = Country::orderBy('name', 'ASC')->get();
        $data['countries'] = $countries;
         
        return view('front.checkout', $data);
    }
}
