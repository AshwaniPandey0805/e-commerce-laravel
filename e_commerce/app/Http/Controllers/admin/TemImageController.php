<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;

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
    
                if ($image->move(public_path('temp'), $newName)) {
                    return response()->json([
                        'status' => true,
                        'image_id' => $tempImg->id,
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
