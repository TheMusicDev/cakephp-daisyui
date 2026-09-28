<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Layout;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\LayoutHelper;

class DrawerTest extends TestCase
{
    private LayoutHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new LayoutHelper(new View());
    }

    protected function tearDown(): void
    {
        ClassMap::reset();
        parent::tearDown();
    }

    public function testDefault(): void
    {
        $result = $this->helper->drawer('my-drawer', '<p>Content</p>', '<ul><li>Menu</li></ul>');
        $this->assertStringContainsString('drawer', $result);
        $this->assertStringContainsString('drawer-toggle', $result);
        $this->assertStringContainsString('id="my-drawer"', $result);
        $this->assertStringContainsString('type="checkbox"', $result);
        $this->assertStringContainsString('drawer-content', $result);
        $this->assertStringContainsString('<p>Content</p>', $result);
        $this->assertStringContainsString('drawer-side', $result);
        $this->assertStringContainsString('<ul><li>Menu</li></ul>', $result);
    }

    public function testWithPlacement(): void
    {
        $result = $this->helper->drawer('my-drawer', '<p>Content</p>', '<ul></ul>', ['placement' => 'end']);
        $this->assertStringContainsString('drawer-end', $result);
    }

    public function testWithModifier(): void
    {
        $result = $this->helper->drawer('my-drawer', '<p>Content</p>', '<ul></ul>', ['modifier' => 'open']);
        $this->assertStringContainsString('drawer-open', $result);
    }

    public function testWithOverlayLabel(): void
    {
        $result = $this->helper->drawer(
            'my-drawer',
            '<p>Content</p>',
            '<ul></ul>',
            ['overlayLabel' => 'Custom Label'],
        );
        $this->assertStringContainsString('aria-label="Custom Label"', $result);
    }

    public function testClassAppended(): void
    {
        $result = $this->helper->drawer('my-drawer', '<p>Content</p>', '<ul></ul>', ['class' => 'extra']);
        $this->assertStringContainsString('drawer extra', $result);
    }

    public function testAttributePassthrough(): void
    {
        $result = $this->helper->drawer(
            'my-drawer',
            '<p>Content</p>',
            '<ul></ul>',
            ['id' => 'my-id', 'data-test' => 'value'],
        );
        $this->assertStringContainsString('id="my-id"', $result);
        $this->assertStringContainsString('data-test="value"', $result);
    }

    public function testDrawerButton(): void
    {
        $result = $this->helper->drawerButton('Open', 'my-drawer');
        $this->assertStringContainsString('<label', $result);
        $this->assertStringContainsString('for="my-drawer"', $result);
        $this->assertStringContainsString('drawer-button', $result);
        $this->assertStringContainsString('>Open</label>', $result);
    }

    public function testDrawerButtonWithColor(): void
    {
        $result = $this->helper->drawerButton('Open', 'my-drawer', ['color' => 'primary']);
        $this->assertStringContainsString('btn-primary', $result);
        $this->assertStringContainsString('drawer-button', $result);
    }

    public function testDrawerButtonWithSize(): void
    {
        $result = $this->helper->drawerButton('Open', 'my-drawer', ['size' => 'lg']);
        $this->assertStringContainsString('btn-lg', $result);
    }

    public function testDrawerButtonClassAppended(): void
    {
        $result = $this->helper->drawerButton('Open', 'my-drawer', ['class' => 'extra']);
        $this->assertStringContainsString('extra', $result);
    }

    public function testDrawerButtonUnknownColorThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->drawerButton('Open', 'my-drawer', ['color' => 'nope']);
    }

    public function testDrawerClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['drawer.base' => 'drawer custom']);
        ClassMap::reset();

        $result = $this->helper->drawer('my-drawer', '<p>Content</p>', '<ul></ul>');
        $this->assertStringContainsString('drawer custom', $result);
    }
}
