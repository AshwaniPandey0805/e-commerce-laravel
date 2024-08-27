<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Psy\Sudo;

class SubCategoryController extends Controller
{

    public function index(){
        $subCategories = SubCategory::select('sub_categories.*', 'categories.name as CategoryName')
                        ->latest('id')
                        ->leftJoin('categories','categories.id','sub_categories.category_id');
        
        $subCategories = $subCategories->paginate(10);
        
        return view('admin.sub_category.list', compact('subCategories'));
    }

    public function create(){
        $categories = Category::orderBy('name','ASC')->get();
        return view('admin.sub_category.create',compact('categories'));
    }

    public function store(Request $request){
        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
            'status' => 'required'
        ]);

        if($validator->passes()){

            $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->category_id = $request->category;
            $subCategory->status = $request->status;
            $subCategory->show_home = $request->show_home;
            $subCategory->save();

            $request->session()->flash('success', 'Sub category added successfully');

            return response()->json([
                'status' => true,
                'message' => 'sub-category added successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request){
        
        $subCategory = SubCategory::find($id);
        // dd($subCategory);
        if(empty($subCategory)){
            return response()->json([
                'status' => false,
                'isFound' => true,
                'message' => 'Sub-category not found'
            ]);
        }

        $categories = Category::orderBy('name','ASC')->get();
        return view('admin.sub_category.edit',compact('categories','subCategory'));
    }

    public function update($id, Request $request){
        $subCategory = SubCategory::find($id);

        $validator =  Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
            'category' => 'required',
            'status' => 'required'
        ]);

        if($validator->passes()){

            // $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->category_id = $request->category;
            $subCategory->status = $request->status;
            $subCategory->show_home = $request->show_home;
            $subCategory->save();

            $request->session()->flash('success', 'Sub category Updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'sub-category updated successfully'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }

    public function destroy($id, Request $request){
        $subCategory = SubCategory::find($id);
        if(empty($subCategory)){
            return response()->json([
                'status' => false,
                'isNotFound' => true,
                'message' => 'Category not found'
            ]);
        }

        $subCategory->delete();
        $request->session()->flash('success','Sub Category deleted Successfully');
        return redirect()->route('sub-category.index');
    }
}
