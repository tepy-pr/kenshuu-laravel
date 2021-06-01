<?php

namespace App\Http\Repositories\Interfaces;

interface IImageRepository
{
    public function validate($file, $inputName): array;
    public function createImageModelsFromFiles($files, $folder, $inputName): array;
}
