<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;

class TemImageController extends Controller
{
    public function create(Request $request) {
        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $ext = $image->getClientOriginalExtension();
                $newName = time().'.'.$ext;
    
                $tempImg = new TempImage();
                $tempImg->name = $newName;
                $tempImg->save();

                $newImage2 = $tempImg->id.'-'.time().'.'.$ext;
                $tempImg->name = $newImage2;
                $tempImg->save();
    
                if ($image->move(public_path('temp'), $newImage2)) {


                    // Generate Thumbnail
                    $sourcePath = public_path().'/temp/'.$newImage2;
                    $destPath = public_path().'/temp/thumb/'.$newImage2;
                    $image = Image::read($sourcePath);
                    $image->resize(300, 275);
                    $image->save($destPath);

                    return response()->json([
                        'status' => true,
                        'image_id' => $tempImg->id,
                        'path' => asset('/temp/thumb/'.$newImage2),
                        'message' => 'Image uploaded Successfully'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Failed to move image to the destination folder.'
                    ], 500);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'No image uploaded.'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
