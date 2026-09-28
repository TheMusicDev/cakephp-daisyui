<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Navigation;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\NavigationHelper;

class DockTest extends TestCase
{
    private NavigationHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new NavigationHelper(new View());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        ClassMap::reset();
    }

    public function testDefault(): void
    {
        $items = [
            ['icon' => '<svg>home</svg>', 'label' => 'Home'],
        ];

        $result = $this->helper->dock($items);

        $this->assertStringContainsString('class="dock', $result);
        $this->assertStringContainsString('dock-label', $result);
        $this->assertStringContainsString('Home', $result);
        $this->assertStringContainsString('<svg>home</svg>', $result);
    }

    public function testWithUrl(): void
    {
        $items = [
            ['icon' => '<svg>home</svg>', 'label' => 'Home', 'url' => '/'],
        ];

        $result = $this->helper->dock($items);

        $this->assertStringContainsString('href=', $result);
        $this->assertStringContainsString('<a', $result);
    }

    public function testWithoutUrl(): void
    {
        $items = [
            ['icon' => '<svg>home</svg>', 'label' => 'Home'],
        ];

        $result = $this->helper->dock($items);

        $this->assertStringContainsString('<button type="button"', $result);
    }

    public function testActive(): void
    {
        $items = [
            ['icon' => '<svg>home</svg>', 'label' => 'Home', 'active' => true],
        ];

        $result = $this->helper->dock($items);

        $this->assertStringContainsString('dock-active', $result);
        $this->assertStringContainsString('aria-current="page"', $result);
    }

    public function testSize(): void
    {
        $items = [
            ['icon' => '<svg>home</svg>', 'label' => 'Home'],
        ];

        $result = $this->helper->dock($items, ['size' => 'sm']);

        $this->assertStringContainsString('dock-sm', $result);
    }

    public function testClassAppended(): void
    {
        $items = [
            ['icon' => '<svg>home</svg>', 'label' => 'Home'],
        ];

        $result = $this->helper->dock($items, ['class' => 'extra']);

        $this->assertStringContainsString('dock extra', $result);
    }

    public function testAttributePassthrough(): void
    {
        $items = [
            ['icon' => '<svg>home</svg>', 'label' => 'Home'],
        ];

        $result = $this->helper->dock($items, ['id' => 'my-dock']);

        $this->assertStringContainsString('id="my-dock"', $result);
    }

    public function testLabelEscaped(): void
    {
        $items = [
            ['icon' => '<svg>home</svg>', 'label' => '<b>Home</b>'],
        ];

        $result = $this->helper->dock($items);

        $this->assertStringContainsString('&lt;b&gt;Home&lt;/b&gt;', $result);
    }

    public function testIconRaw(): void
    {
        $items = [
            ['icon' => '<svg>home</svg>', 'label' => 'Home'],
        ];

        $result = $this->helper->dock($items);

        $this->assertStringContainsString('<svg>home</svg>', $result);
    }

    public function testUnknownSizeThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);

        $items = [
            ['icon' => '<svg>home</svg>', 'label' => 'Home'],
        ];

        $this->helper->dock($items, ['size' => 'nope']);
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['dock.base' => 'dock custom']);
        ClassMap::reset();

        $items = [
            ['icon' => '<svg>home</svg>', 'label' => 'Home'],
        ];

        $result = $this->helper->dock($items);

        $this->assertStringContainsString('class="dock custom', $result);
    }
}
