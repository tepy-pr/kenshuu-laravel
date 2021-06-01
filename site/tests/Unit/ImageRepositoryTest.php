<?php

namespace Tests\Unit;

use App\Http\Repositories\ImageRepository;
use App\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Tests\Utils\ImageUtil;

class ImageRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_image_files()
    {
        $inputName = "postImages";
        $files = ImageUtil::createImageFiles($inputName, true);

        $imageRepo = new ImageRepository(new Image());
        $result = $imageRepo->validate($files, $inputName);

        $this->assertNotNull($result);
    }

    public function test_invalid_image_files()
    {
        $this->expectException(ValidationException::class);

        $inputName = "postImages";
        $files = ImageUtil::createImageFiles($inputName, false);

        $imageRepo = new ImageRepository(new Image());
        $imageRepo->validate($files, $inputName);
    }

    public function test_generate_image_models_from_files()
    {
        $inputName = "postImages";
        $files = ImageUtil::createImageFiles($inputName, true);
        $folder = "/images";

        $imageRepo = new ImageRepository(new Image());
        $imageModels = $imageRepo->createImageModelsFromFiles($files, $folder, $inputName);

        $this->assertCount(2, $imageModels);
        $this->assertInstanceOf(Image::class, $imageModels[0]);
        $this->assertInstanceOf(Image::class, $imageModels[1]);
    }
}
