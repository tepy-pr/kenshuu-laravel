<?php

namespace Tests\Unit;

use App\Http\Repositories\ImageRepository;
use App\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Utils\ImageUtil;

class ImageRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $imageRepo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->imageRepo = new ImageRepository(new Image());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_generate_image_models_from_files()
    {
        $inputName = "postImages";
        $files = ImageUtil::createImageFiles($inputName, true);
        $folder = "/images";

        $imageModels = $this->imageRepo->createImageModelsFromFiles($files, $folder, $inputName);

        $this->assertCount(2, $imageModels);
        $this->assertInstanceOf(Image::class, $imageModels[0]);
        $this->assertInstanceOf(Image::class, $imageModels[1]);
    }
}
