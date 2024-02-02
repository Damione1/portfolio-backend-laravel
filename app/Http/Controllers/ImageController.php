<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Resources\ImageCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        return new ImageCollection(Image::all());
    }

    public function show(Request $request, Image $image)
    {
        return new ImageCollection($image);
    }

    public function uploadFile(UploadedFile $file, $folder = null, $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);
        $disk = Storage::disk('gcs');

        // Check if file with the same name exists
        if ($disk->exists($folder . '/' . $name . "." . $file->getClientOriginalExtension())) {
            // Generate a new name
            $name = Str::random(25);
        }

        $filePath = $file->storeAs(
            $folder,
            $name . "." . $file->getClientOriginalExtension(),
            ['disk' => 'gcs']
        );

        // Make the file publicly accessible
        $disk->setVisibility($filePath, 'public');

        // Return the public URL of the file
        return $disk->url($filePath);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $path = $this->uploadFile($request->file('image'), 'images');
            $image = Image::create([
                'path' => $path,
            ]);
        } catch (\Exception $e) {
            // Handle the error
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return new ImageCollection($image);
    }
}
