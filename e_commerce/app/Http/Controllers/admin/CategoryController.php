<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;

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
            $category->show_home = $request->show_home;
            $category->save();

            //save image file
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'-'.time().'.'.$ext;
                // Define the source path of the temporary image
                $sPath = public_path() . '/temp/' . $tempImage->name;
            
                // Define the destination path in the 'uploads/category' directory
                $dPath = public_path().'/uploads/category/' . $newImageName;
                
                File::copy($sPath, $dPath);

                $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                $img  = Image::read($sPath);
                $img->resize(450,600);
                $img->save($dPath);

                $category->image = $newImageName;
                $category->save();
            }
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

    public function edit(Request $request , $categoryID ){
        // dd($categoryID);
        $category = Category::find($categoryID);
        // dd($category);
        if(empty($category)){
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ]);
        }
        return view('admin.category.update', compact('category'));
    }

    public function update($categoryID, Request $request){
        
        $category = Category::find($categoryID);

        if(empty($category)){
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id'
        ]);
    
        if($validator->passes()){
            // Handle successful validation
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->show_home = $request->show_home;
            $category->save();

            $oldImage = $category->image;

            //save image file
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'-'.time().'.'.$ext;
                // Define the source path of the temporary image
                $sPath = public_path() . '/temp/' . $tempImage->name;
            
                // Define the destination path in the 'uploads/category' directory
                $dPath = public_path().'/uploads/category/' . $newImageName;
                
                File::copy($sPath, $dPath);

                $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                $img  = Image::read($sPath);
                $img->resize(450,600);
                $img->save($dPath);

                $category->image = $newImageName;
                $category->save();

                //Delete old image for category and thumb folder
                File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
                File::delete(public_path().'/uploads/category/'.$oldImage);
            }
            $category->save();
            // Use flash to store a temporary session message (available for the next request)
            $request->session()->flash('success', 'Category updated successfully');

            // return redirect()->route('category.index');

            return response()->json([
                'status' => true,
                'message' => 'Validation passed. Proceed to update data.'
            ]);
        } else {
            return response()->json([
                'status' => false, // Should be false since validation failed
                'errors' => $validator->errors()  // Corrected to use errors()
            ]);
        }        
    }

    public function destroy($categoryID, Request $request){
        
        $category = Category::find($categoryID);

        if(empty($category)){
            $request->session()->flash('error', 'Category Not Found');
            return redirect()->route('category.index');
        }else {
            $category->delete();
            File::delete(public_path().'/uploads/category/thumb/'.$category->image);
            File::delete(public_path().'/uploads/category/'.$category->image);
            $request->session()->flash('success', 'Category deleted ');
            return redirect()->route('category.index');
        }

    }

}
