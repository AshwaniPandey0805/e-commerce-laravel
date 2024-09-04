<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Colors\Rgb\Channels\Red;

class wishListController extends Controller
{

    public function index(){

        $wishListProducts = WishList::where('user_id', Auth::user()->id)
                            ->with('getWishListProducts.product_images')
                            ->orderBy('created_at', 'ASC')->get();
        // dd($wishListProducts);
        $data['wishListProducts'] = $wishListProducts;
        return view('front.account.wishList', $data);
    }

    public function store(Request $request){
        
        if(Auth::check()){

            $product = Product::where('id', $request->id)->with('product_images')->first();
            if(!isset($product)){
                return response()->json([
                    'status' => true,
                    'message' =>'product is not available'
                ]);
            }
            // store to wish list
            $ProductExistsInWishList = WishList::where('product_id', $request->id)
                                        ->where('user_id', Auth::user()->id)
                                        ->first();
            if(!$ProductExistsInWishList){
                
                $wishListProduct = new WishList();
                $wishListProduct->user_id = Auth::user()->id;
                $wishListProduct->product_id = $product->id;
                $wishListProduct->save();
                return response()->json([
                    'status' => true,
                    'message' => $product->title.' Product added successfully',
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'error' => $product->title.' Product is already in your wishlist.'
                ]);
            }

        } else {
            return response()->json([
                'status' => false,
                'error' => 'Please login first'
            ]);
        }

    }

    public function delete(Request $request){
        if(Auth::check()){

            $wishListProduct = WishList::where('product_id', $request->id)
                                            ->where('user_id', Auth::user()->id)
                                            ->first();
            $wishListProduct->delete();
            request()->session()->flash('success','Product deleted from wish list');
            return response()->json([
                'status' => true,
                'message' => 'Product deleted from wish list'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'error' => 'Please login first'
            ]);
        }
    }
}
