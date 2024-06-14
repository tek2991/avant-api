<?php

namespace App\Http\Controllers\TechDemos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mews\Purifier\Facades\Purifier;
use App\Models\Tinymce;

class TinyMceController extends Controller
{
    public function index()
    {
        $tinymces = Tinymce::paginate();
        return view('tech-demos.tiny-mce', compact('tinymces'));
    }

    public function imageUpload(Request $request)
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

            $image_name = 'tiny_mce/' . $user->id . '_' . time() . '.jpeg';

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

    public function store(Request $request)
    {
        $user = Auth::user();
        Purifier::clean($request->description);
        Tinymce::create([
            'description' => $request->description,
            'user_id' => $user->id
        ]);
        return back()->withStatus('TinyMCE created successfully.');
    }
}
