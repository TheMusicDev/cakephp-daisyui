<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class AuraTest extends TestCase
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
        $html = $this->helper->aura('<div>Content</div>');
        $this->assertStringContainsString('class="aura"', $html);
        $this->assertStringContainsString('<div>Content</div>', $html);
    }

    public function testAppearance(): void
    {
        $html = $this->helper->aura('<div>Content</div>', ['appearance' => 'rainbow']);
        $this->assertStringContainsString('aura-rainbow', $html);
    }

    public function testSize(): void
    {
        $html = $this->helper->aura('<div>Content</div>', ['size' => 'lg']);
        $this->assertStringContainsString('aura-lg', $html);
    }

    public function testUnknownAppearanceThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->aura('<div></div>', ['appearance' => 'nope']);
    }

    public function testUnknownSizeThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->aura('<div></div>', ['size' => 'nope']);
    }
}
