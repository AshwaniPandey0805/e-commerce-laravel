<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(){
        $categories = Category::where('status', 1)->orderBy('name', 'ASC')->with('getSubCategories')->get();
        $products = Product::where('status', 1)->orderBy('title', 'ASC')->with('product_images')->get();
        $brands = Brands::orderBy('name', 'ASC')->where('status', 1)->get();
        $data['categories'] = $categories;
        $data['products'] = $products;
        $data['brands'] = $brands;
        return view('front.shop', $data);
    }
}
