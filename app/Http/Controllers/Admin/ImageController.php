<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ImageUploadRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController
{
    public function upload(ImageUploadRequest $request)
    {

        $file = $request->file('image');
        $name = Str::random(10);
        $url = Storage::putFileAs('images', $file, $name.'.'.$file->extension());

        return [
            'url' => Storage::url($url),
            'path' => $url
        ];
    }
}
