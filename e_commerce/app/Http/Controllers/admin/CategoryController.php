<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::latest()->paginate(10);
        return view('admin.category.list', compact('categories'));
    }

    public function create(){

        return view('admin.category.create');
        // echo 'catgeory created page';
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories'
        ]);
    
        if($validator->passes()){
            // Handle successful validation

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            // Use flash to store a temporary session message (available for the next request)
            $request->session()->flash('success', 'Category added successfully');

            // return redirect()->route('category.index');

            return response()->json([
                'status' => true,
                'message' => 'Validation passed. Proceed to save data.'
            ]);
        } else {
            return response()->json([
                'status' => false, // Should be false since validation failed
                'errors' => $validator->errors()  // Corrected to use errors()
            ]);
        }
    }

    public function edit(){

    }

    public function update(){

    }

    public function destroy(){

    }

}
