<?php

namespace App\Http\Repositories;

use App\Http\Repositories\Interfaces\IImageRepository;
use App\Image;
use Illuminate\Support\Facades\Validator;

class ImageRepository implements IImageRepository
{
    public $image;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    public function createImageModelsFromFiles($imageFiles): array
    {
        if (!$imageFiles) {
            return [];
        }

        $imageModels = [];
        $images = [];

        if (count($imageFiles) > 0) {
            foreach ($imageFiles as $imageFile) {
                $ext = $imageFile["image"]->extension();
                $path = $imageFile["image"]->getPathName();
                $imageName = sha1_file($path) . "." . $ext;
                $imageFile["image"]->move(public_path("/images"), $imageName);
                array_push($images, "/images/" . $imageName);
            }
        }

        foreach ($images as $image) {
            array_push($imageModels, new Image(["url" => $image]));
        }

        return $imageModels;
    }
}
