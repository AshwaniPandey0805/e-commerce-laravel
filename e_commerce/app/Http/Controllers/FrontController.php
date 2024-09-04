<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    public function index(){
        $featuredProducts = Product::where('is_featured', 'Yes')
                            ->where('status' , 1)
                            ->orderBy('title', 'ASC')
                            ->with('product_images');
                            
        $latestProducts = Product::where('status', 1)
                                ->orderBy('id','ASC')
                                ->with('product_images');
                                
        
        $wishListProductId = [];
        if(Auth::check()){
            $wishListProducts = WishList::select('product_id')->where('user_id', Auth::user()->id)->get();
            if(isset($wishListProducts)){
                foreach ($wishListProducts as  $product) {
                    $wishListProductId[] = $product->product_id;
                }
            }
        }

        $featuredProducts = $featuredProducts->take(8)->get();
        $latestProducts = $latestProducts->take(8)->get();
        $data['featuredProducts'] = $featuredProducts; 
        $data['latestProduct'] = $latestProducts;
        $data['wishListProductId'] = $wishListProductId;
        return view('front.home', $data);
    }
}

