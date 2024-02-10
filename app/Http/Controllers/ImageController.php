<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Resources\ImageCollection;
use App\Http\Resources\ImageResource;
use Illuminate\Support\Facades\Auth;
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
        try {
            return new ImageResource($image);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Image not found'], 404);
        }
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

    public function store(StoreImageRequest $request)
    {
        $validated = $request->validated();
        try {
            $path = $this->uploadFile($validated['image'], 'images');
            $image =  Auth::user()->images()->create([
                'path' => $path,
                'filename' => $validated['image']->getClientOriginalName()
            ]);
        } catch (\Exception $e) {
            // Handle the error
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return new ImageResource($image);
    }

    public function destroy(Request $request, Image $image)
    {
        $image->delete();
        return response()->noContent();
    }
}
