<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
   
    public function index()
    {
        return Image::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->store('images', 'public');

        $image = Image::create([
            'name' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
            'path' => $path,
        ]);

        return response()->json($image, 201);
    }

    public function show(Image $image)
    {
        return response()->json($image);
    }


    public function destroy(Image $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();
        return response()->json(null, 204);
    }
}


