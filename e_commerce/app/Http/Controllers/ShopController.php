<?php

namespace App\Http\Controllers;

use App\Models\Brands;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug =  null , $subCategorySlug = null){

        $categorySelected = '';
        $subCategorySelected = '';

        $brandArray = [];
        if(!empty(  $request->get('brands'))){
            $brandArray = explode(',',$request->get('brands'));
        }

        $categories = Category::where('status', 1)->orderBy('name', 'ASC')->with('getSubCategories')->get();
        $products = Product::where('status', 1);

        //  Apply filter here
        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $products = $products->where('category_id', $category->id);
                $categorySelected = $category->id;
            } else {
                // Handle the case where the category does not exist
            }
        }
        
        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
            if ($subCategory) {
                $products = $products->where('sub_category_id', $subCategory->id);
                $subCategorySelected = $subCategory->id;
            } else {
                // Handle the case where the subcategory does not exist
            }
        }

        if(!empty($request->get('brands'))){
            $brandArray = explode( ',', $request->get('brands'));
            $products = $products->whereIn('brand_id', $brandArray);
        }

        if($request->get('price_max') != ''  && $request->get('price_min') != ''){
            $products = $products->whereBetween('price',[ intval($request->get('price_min')), intval($request->get('price_max'))]);
        }

        if($request->get('sort') != ''){
            if($request->get('sort') == 'latest'){
                $products = $products->orderBy('created_at', 'DESC' );
            } elseif ( $request->get('sort') == 'price_desc' ) {
                $products = $products->orderBy('price', 'DESC');
            } else {
                $products = $products->orderBy('price', 'ASC');
            }
        } else {
            $products = $products->orderBy('created_at', 'DESC' );
        }

        $products = $products->with('product_images')->paginate(6);

        // dd($products);
        $brands = Brands::orderBy('name', 'ASC')->where('status', 1)->get();
        $data['categories'] = $categories;
        $data['products'] = $products;
        $data['brands'] = $brands;
        $data['categorySelected'] = $categorySelected;
        $data['subCategorySelected'] = $subCategorySelected;
        $data['brandArray'] = $brandArray;
        $data['price_max'] = (intval($request->get('price_max')) == 0) ? 1000 : intval($request->get('price_max'));
        $data['price_min'] = intval($request->get('price_min'));
        $data['sort'] = $request->get('sort') ;
        return view('front.shop', $data);
    }
}
