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
        $imageFiles = ImageUtil::createImageFiles();

        $imageModels = $this->imageRepo->createImageModelsFromFiles($imageFiles);

        $this->assertCount(2, $imageModels);
        $this->assertInstanceOf(Image::class, $imageModels[0]);
        $this->assertInstanceOf(Image::class, $imageModels[1]);
    }

    public function test_it_return_empty_when_no_image_file()
    {
        $imageModels = $this->imageRepo->createImageModelsFromFiles(null);

        $this->assertEmpty($imageModels);
    }
}
