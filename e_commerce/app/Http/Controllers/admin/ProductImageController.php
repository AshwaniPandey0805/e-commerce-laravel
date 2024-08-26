<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class ProductImageController extends Controller
{
    public function update(Request $request){

        if(!empty($request->image)){
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $tempImagePath = $image->getPathName();
        }
        
        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = "NULL";
        $productImage->save();

        $imageName = $request->product_id.'-'.$productImage->id.'-'.time().'.'.$ext;
        $productImage->image = $imageName;
        $productImage->save();

        // Generate product thumb image

        // Large Image
        $sourcePathLarge = $tempImagePath;
        $destinationPathLarge = public_path().'/uploads/products/large/'.$imageName;
        $image = Image::read($sourcePathLarge);
        $image->resize('1400', '1000' ,function($constraint){
            $constraint->aspectRatio();
        });
        $image->save($destinationPathLarge);

        // Small Image
        $sourcePathSmall = $tempImagePath;
        $destinationPathSmall = public_path().'/uploads/products/small/'.$imageName;
        $image = Image::read($sourcePathSmall);
        $image->resize('300', '300' );
        $image->save($destinationPathSmall);
        
        return response()->json([
            'status' => true,
            'image_id' => $productImage->id,
            'path' => asset('uploads/products/small/'.$productImage->image),
            'message' => 'Image Updated successfully'
        ]);    
    }

    public function destroy(Request $request){
        $productImage = ProductImage::find($request->id);

        if(empty($productImage)){
            return response()->json([
                'status' => false,
                'isNotFound' => true,
                'message' => 'Image  not found'
            ]);
        }
        //Delete form Gallary
        File::delete(public_path('/uploads/products/large/'.$productImage->image));
        File::delete(public_path('/uploads/products/small/'.$productImage->image));

        $productImage->delete();
        $request->session()->flash('success','Image Deleted Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully'
        ]);

    }
}
