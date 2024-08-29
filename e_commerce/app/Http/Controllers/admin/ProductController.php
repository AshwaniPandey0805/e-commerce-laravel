<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brands;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{

    public function index() {
        $products = Product::latest('id')->with('product_images')->paginate(10);
        $data['products'] = $products;
        
        return view('admin.product.list', $data);
    }

    public function create()
    {
        $categories = Category::orderBy('name', "ASC")->get();
        $brands = Brands::all();
        $data['categories'] = $categories;
        $data['brands'] = $brands;

        return view('admin.product.create' , $data);
    }

    public function store(Request $request){

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No' 
        ];

        if ( !empty($request->track_qty) && $request->track_qty == 'Yes' ) {
            $rule['qty'] = 'required|numeric';
        }

        $validatore = Validator::make( $request->all(), $rules );

        if ($validatore->passes()) {

            $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->discription = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->short_description = $request->short_description;
            $product->shipping_return = $request->shipping_return;
            $product->related_products = $request->related_products;
            $product->save();

            //Save Gallery Pics
            if(!empty($request->image_array)){
                foreach ($request->image_array as $temp_image_id) {
                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.',$tempImageInfo->name);
                    $ext = last($extArray);
        
                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = "NULL";
                    $productImage->save();

                    $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    $productImage->image = $imageName;
                    $productImage->save();
                    # code...

                    // Generate product thumb image

                    // Large Image
                    $sourcePathLarge = public_path().'/temp/'.$tempImageInfo->name;
                    $destinationPathLarge = public_path().'/uploads/products/large/'.$imageName;
                    $image = Image::read($sourcePathLarge);
                    $image->resize('1400', '1000' ,function($constraint){
                        $constraint->aspectRatio();
                    });
                    $image->save($destinationPathLarge);

                    // Small Image
                    $sourcePathSmall = public_path().'/temp/'.$tempImageInfo->name;
                    $destinationPathSmall = public_path().'/uploads/products/small/'.$imageName;
                    $image = Image::read($sourcePathSmall);
                    $image->resize('300', '300' );
                    $image->save($destinationPathSmall);
                }
            }

            $request->session()->flash('success', 'Product added successfully');

            return response()->json([
                'status' => true,
                'data' => $ext,
                'message' => 'Product stored successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validatore->errors()
            ]);
        }
    }

    public function edit($id, Request $request){

        $product = Product::find($id);
        // dd($product);
        $subCategories = SubCategory::where('category_id', $product->category_id)->get();
        $productImages = ProductImage::where('product_id', $product->id)->get();
        // dd($productImages);
        // dd($subcategories);
        $categories = Category::orderBy('name', "ASC")->get();
        $brands = Brands::all();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['subCategories'] = $subCategories;
        $data['product'] = $product;
        $data['productImages'] = $productImages; 
        return view('admin.product.edit',$data);
    }

    public function update($id, Request $request){
        
        $product = Product::find($id);
        if(empty($product)){
            return response()->json([
                'status' => false,
                'isNotFound' => true,
                'message' => 'Product not found'
            ]); 
        }
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'.$product->id.',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No' 
        ];

        if ( !empty($request->track_qty) && $request->track_qty == 'Yes' ) {
            $rule['qty'] = 'required|numeric';
        }

        $validatore = Validator::make( $request->all(), $rules );

        if ($validatore->passes()) {

            // $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->discription = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->short_description = $request->short_description;
            $product->shipping_return = $request->shipping_return;
            $product->related_products = $request->related_products;
            $product->save();

            //Save Gallery Pics
            // if(!empty($request->image_array)){
            //     foreach ($request->image_array as $temp_image_id) {
            //         $tempImageInfo = TempImage::find($temp_image_id);
            //         $extArray = explode('.',$tempImageInfo->name);
            //         $ext = last($extArray);
        
            //         $productImage = new ProductImage();
            //         $productImage->product_id = $product->id;
            //         $productImage->image = "NULL";
            //         $productImage->save();

            //         $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
            //         $productImage->image = $imageName;
            //         $productImage->save();
            //         # code...

            //         // Generate product thumb image

            //         // Large Image
            //         $sourcePathLarge = public_path().'/temp/'.$tempImageInfo->name;
            //         $destinationPathLarge = public_path().'/uploads/products/large/'.$imageName;
            //         $image = Image::read($sourcePathLarge);
            //         $image->resize('1400', '1000' ,function($constraint){
            //             $constraint->aspectRatio();
            //         });
            //         $image->save($destinationPathLarge);

            //         // Small Image
            //         $sourcePathSmall = public_path().'/temp/'.$tempImageInfo->name;
            //         $destinationPathSmall = public_path().'/uploads/products/small/'.$imageName;
            //         $image = Image::read($sourcePathSmall);
            //         $image->resize('300', '300' );
            //         $image->save($destinationPathSmall);
            //     }
            // }

            $request->session()->flash('success', 'Product updated successfully');

            return response()->json([
                'status' => true,
                // 'data' => $ext,
                'message' => 'Product updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validatore->errors()
            ]);
        }

    }

    public function delete($id, Request $request ){
        $product = Product::find($id);

        //Delete product image
        $productImages = ProductImage::where('product_id', $id)->get();
        
        if(empty($product)){
            return redirect()->route('product.index')->with('error','Product Not Found');    
        }

        if(!empty($productImages)){
            foreach ($productImages as $image) {
                File::delete(public_path('/uploads/products/large/'.$image->image));
                File::delete(public_path('/uploads/products/small/'.$image->image));
                # code...
            }

            ProductImage::where('product_id', $id)->delete();
        }

        $product->delete();
        $request->session()->flash('success', 'Product deleted successfully');
        return redirect()->route('product.index')->with('success','Product deleted successfully');
    }
}
