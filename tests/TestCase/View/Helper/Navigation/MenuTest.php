<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Navigation;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\NavigationHelper;

class MenuTest extends TestCase
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
        ClassMap::reset();
        parent::tearDown();
    }

    public function testDefault(): void
    {
        $result = $this->helper->menu([
            ['text' => 'Home', 'url' => '/'],
            ['text' => 'About', 'url' => '/about'],
        ]);
        $this->assertStringContainsString('<ul class="menu">', $result);
        $this->assertStringContainsString('<a href="/">Home</a>', $result);
        $this->assertStringContainsString('<a href="/about">About</a>', $result);
    }

    public function testWithSize(): void
    {
        $result = $this->helper->menu([], ['size' => 'lg']);
        $this->assertStringContainsString('menu-lg', $result);
    }

    public function testWithDirection(): void
    {
        $result = $this->helper->menu([], ['direction' => 'horizontal']);
        $this->assertStringContainsString('menu-horizontal', $result);
    }

    public function testWithModifier(): void
    {
        $result = $this->helper->menu([], ['modifier' => 'paged']);
        $this->assertStringContainsString('menu-paged', $result);
    }

    public function testTitle(): void
    {
        $result = $this->helper->menu([
            ['title' => 'Section Header'],
        ]);
        $this->assertStringContainsString('menu-title', $result);
        $this->assertStringContainsString('Section Header', $result);
    }

    public function testButton(): void
    {
        $result = $this->helper->menu([
            ['text' => 'Click me'],
        ]);
        $this->assertStringContainsString('<button type="button">Click me</button>', $result);
    }

    public function testActive(): void
    {
        $result = $this->helper->menu([
            ['text' => 'Home', 'url' => '/', 'active' => true],
        ]);
        $this->assertStringContainsString('menu-active', $result);
        $this->assertStringContainsString('aria-current="page"', $result);
    }

    public function testDisabled(): void
    {
        $result = $this->helper->menu([
            ['text' => 'Disabled', 'url' => '/', 'disabled' => true],
        ]);
        $this->assertStringContainsString('menu-disabled', $result);
        $this->assertStringContainsString('aria-disabled="true"', $result);
        $this->assertStringContainsString('tabindex="-1"', $result);
    }

    public function testIcon(): void
    {
        $result = $this->helper->menu([
            ['text' => 'Home', 'url' => '/', 'icon' => '<svg></svg>'],
        ]);
        $this->assertStringContainsString('<svg></svg>', $result);
        $this->assertStringContainsString('Home', $result);
    }

    public function testRawContent(): void
    {
        $result = $this->helper->menu([
            ['content' => '<span>Custom</span>'],
        ]);
        $this->assertStringContainsString('<span>Custom</span>', $result);
    }

    public function testChildren(): void
    {
        $result = $this->helper->menu([
            [
                'text' => 'Parent',
                'url' => '/',
                'children' => [
                    ['text' => 'Child 1', 'url' => '/child1'],
                    ['text' => 'Child 2', 'url' => '/child2'],
                ],
            ],
        ]);
        $this->assertStringContainsString('<details>', $result);
        $this->assertStringContainsString('<summary>', $result);
        $this->assertStringContainsString('Child 1', $result);
        $this->assertStringContainsString('Child 2', $result);
    }

    public function testClassAppended(): void
    {
        $result = $this->helper->menu([], ['class' => 'extra']);
        $this->assertStringContainsString('menu extra', $result);
    }

    public function testUnknownSizeThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->menu([], ['size' => 'nope']);
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['menu.base' => 'menu custom']);
        ClassMap::reset();

        $result = $this->helper->menu([]);
        $this->assertStringContainsString('menu custom', $result);
    }

    public function testSubmenuSummaryHoldsTheLabelNotALink(): void
    {
        $html = $this->helper->menu([['text' => 'Docs', 'url' => '/docs', 'open' => true, 'children' => [['text' => 'A']]]]);

        $this->assertStringContainsString('<details open="open"><summary>Docs</summary><ul>', $html);
        $this->assertStringNotContainsString('<summary><a', $html);
    }

    public function testDisabledLinkHasNoHref(): void
    {
        $html = $this->helper->menu([['text' => 'Soon', 'url' => '/soon', 'disabled' => true]]);

        $this->assertStringNotContainsString('href=', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
    }

    public function testDisabledParentDoesNotOpen(): void
    {
        $html = $this->helper->menu([['text' => 'Parent', 'disabled' => true, 'children' => [['text' => 'Child', 'url' => '/c']]]]);

        $this->assertStringContainsString('<li class="menu-disabled"><span aria-disabled="true">Parent</span></li>', $html);
        $this->assertStringNotContainsString('Child', $html);
    }
}
