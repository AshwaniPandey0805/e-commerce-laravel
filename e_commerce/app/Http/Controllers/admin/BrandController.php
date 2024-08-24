<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brands;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index(){
        $brands = Brands::latest('id')->paginate(10);
        return view('admin.brand.list', compact('brands'));
    }

    public function create(){
        return view('admin.brand.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands',
            'status' => 'required'
        ]);

        if($validator->passes()){

            $brand = new Brands();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();
            
            $request->session()->flash('success','Brand added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Brand added successfully'
            ]);
            

        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Someting went wrong'
            ]);
        }
    }

    public function edit($id, Request $request){
        $brand = Brands::find($id);
        if(empty($brand)){
            return response()->json([
                'status' => false,
                'isNotFound' => true,
                'message' => 'Brand not found'
            ]);
        }

        return view('admin.brand.edit', compact('brand'));
    }

    public function update($id, Request $request){

        $brand = Brands::find($id);

        if(empty($brand)){
            return response()->json([
                'status' => false,
                'isNotFound' => true,
                'message' => 'Brand not found'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:brands',
            'status' => 'required'
        ]);

        if($validator->passes()){

            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();
            
            $request->session()->flash('success','Brand updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Brand updated successfully'
            ]);
            

        } else {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                'message' => 'Someting went wrong'
            ]);
        }

    }

    public function destory($id, Request $request){
        $brand = Brands::find($id);

        if(empty($brand)){
            return response()->json([
                'status' => false,
                'isNotFound' => true,
                'message' => 'Brand not found'
            ]);
        }

        $brand->delete();
        $request->session()->flash('success','Brand deleted successfully');
        return redirect()->route('brand.index');
    }
}
