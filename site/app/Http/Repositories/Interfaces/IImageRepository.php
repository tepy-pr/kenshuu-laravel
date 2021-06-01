<?php

namespace App\Http\Repositories\Interfaces;

interface IImageRepository
{
    public function createImageModelsFromFiles($files, $folder, $inputName): array;
}
