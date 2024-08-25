<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductSubCategoryController extends Controller
{
    public function getSubCategory(Request $request){
        $subCategory = SubCategory::where('category_id', $request->category_id)
                        ->orderBy('name', 'ASC')
                        ->get();
        
        if(!empty($subCategory)){
            return response()->json([
                'status' => true,
                'subCategory' => $subCategory
            ]);
        }
    }
}
