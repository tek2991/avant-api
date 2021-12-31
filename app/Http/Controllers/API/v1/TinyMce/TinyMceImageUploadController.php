<?php

namespace App\Http\Controllers\API\v1\TinyMce;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TinyMceImageUploadController extends Controller
{
    public function store(Request $request)
    {
        if (!$request->base64) {
            return response([
                'header' => 'Bad Request',
                'message' => 'No image found.'
            ], 400);
        }
        $user = Auth::user();
        $base64_image = $request->base64;
        if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
            $data = substr($base64_image, strpos($base64_image, ',') + 1);
            $data = base64_decode($data);

            $image_name = 'tiny_mce_uploaded_imgs/' . $user->id . '_' . time() . '.jpeg';

            try {
                Storage::disk('public')->put($image_name, $data);
                return response([
                    'header' => 'Success',
                    'message' => 'Image uploaded successfully.',
                    'image_url' => Storage::url($image_name)
                ], 200);
            } catch (\Exception $e) {
                return response([
                    'header' => 'Internal Server Error',
                    'message' => 'Something went wrong.'
                ], 500);
            }
        }
    }
}
