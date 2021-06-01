<?php

namespace Tests\Utils;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUtil
{

    public static function createImageFiles($inputName, $isValid)
    {
        Storage::fake("images");
        $ext = $isValid ? "png" : "pdf";

        $image1 = UploadedFile::fake()->image("test1." . $ext);
        $image2 = UploadedFile::fake()->image("test2.png");

        $images = [$image1, $image2];
        $files = [
            $inputName => $images
        ];

        return $files;
    }

    public static function createFakeImage($name = "test.png")
    {
        Storage::fake("images");
        $image = UploadedFile::fake()->image($name);
        return $image;
    }
}
