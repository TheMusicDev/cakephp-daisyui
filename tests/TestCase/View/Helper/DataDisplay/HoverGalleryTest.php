<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use InvalidArgumentException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class HoverGalleryTest extends TestCase
{
    private DataDisplayHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new DataDisplayHelper(new View());
    }

    public function testDefault(): void
    {
        $html = $this->helper->hoverGallery(['image1.png', 'image2.png']);
        $this->assertStringContainsString('class="hover-gallery"', $html);
        $this->assertStringContainsString('image1.png', $html);
        $this->assertStringContainsString('image2.png', $html);
    }

    public function testArrayImages(): void
    {
        $html = $this->helper->hoverGallery([
            ['src' => 'image1.png', 'alt' => 'Image 1'],
            ['src' => 'image2.png', 'alt' => 'Image 2'],
        ]);
        $this->assertStringContainsString('alt="Image 1"', $html);
        $this->assertStringContainsString('alt="Image 2"', $html);
    }

    public function testTooManyImagesThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->helper->hoverGallery(array_fill(0, 11, 'image.png'));
    }
}
