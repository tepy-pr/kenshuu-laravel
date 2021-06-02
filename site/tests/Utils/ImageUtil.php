<?php

namespace Tests\Utils;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageUtil
{

    public static function createImageFiles($isValid = true)
    {
        Storage::fake("images");
        $ext = $isValid ? "png" : "pdf";

        $image1 = UploadedFile::fake()->image("test1." . $ext);
        $image2 = UploadedFile::fake()->image("test2.png");

        $images = [["image" => $image1], ["image" => $image2]];

        return $images;
    }

    public static function createFakeImage($name = "test.png")
    {
        Storage::fake("images");
        $image = UploadedFile::fake()->image($name);
        return $image;
    }
}
