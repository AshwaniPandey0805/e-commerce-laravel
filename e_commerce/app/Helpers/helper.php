<?php

use App\Models\Category;
use App\Models\ProductImage;

    function getCategories(){
        return Category::orderBy('name', 'ASC')
                        ->where('show_home', 'Yes')
                        ->where('status', 1)
                        ->with('getSubCategories')
                        ->get();
    }

    function getProductImage($id){
        return ProductImage::select('image')->where('product_id', $id)->first();
    }
?>