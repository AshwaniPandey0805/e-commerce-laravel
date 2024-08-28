<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index(){
        $featuredProducts = Product::where('is_featured', 'Yes')
                            ->where('status' , 1)
                            ->orderBy('title', 'ASC')
                            ->with('product_images')
                            ->get();
        $latestProduct = Product::where('status', 1)
                                ->orderBy('id','ASC')
                                ->with('product_images')
                                ->get();
        $data['featuredProducts'] = $featuredProducts; 
        $data['latestProduct'] = $latestProduct; 
        return view('front.home', $data);
    }
}

