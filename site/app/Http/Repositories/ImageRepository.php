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

    public function validate($files, $inputName): array
    {
        $validatedImages = Validator::make($files, [
            $inputName . ".*" => "image"
        ])->validate();

        return $validatedImages;
    }

    public function createImageModelsFromFiles($files, $folder, $inputName): array
    {
        $imageModels = [];
        $images = [];
        $validatedImages = $this->validate($files, $inputName);

        if (count($validatedImages) > 0) {
            foreach ($validatedImages[$inputName] as $imageFile) {
                $ext = $imageFile->extension();
                $path = $imageFile->getPathName();
                $imageName = sha1_file($path) . "." . $ext;
                $imageFile->move(public_path($folder), $imageName);
                array_push($images, $folder . "/" . $imageName);
            }
        }

        foreach ($images as $image) {
            array_push($imageModels, new Image(["url" => $image]));
        }

        return $imageModels;
    }
}
