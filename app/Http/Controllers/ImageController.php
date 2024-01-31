<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Resources\ImageCollection;

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

    public function store(Request $request)
    {
        $image = new Image();
        $image->fill($request->all());
        $image->save();
        return new ImageCollection($image);
    }
}
