<?php

namespace App\Http\Repositories\Interfaces;

interface IImageRepository
{
    public function createImageModelsFromFiles($imageFiles): array;
}
