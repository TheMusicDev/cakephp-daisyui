<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Navigation;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\NavigationHelper;

class LinkTest extends TestCase
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
        $this->assertHtml(
            ['a' => ['href' => '/', 'class' => 'link'], 'Click me', '/a'],
            $this->helper->link('Click me', '/'),
        );
    }

    public function testWithColor(): void
    {
        $this->assertHtml(
            ['a' => ['href' => '/', 'class' => 'link link-primary'], 'Home', '/a'],
            $this->helper->link('Home', '/', ['color' => 'primary']),
        );
    }

    public function testWithAppearance(): void
    {
        $this->assertHtml(
            ['a' => ['href' => '/', 'class' => 'link link-hover'], 'Hover', '/a'],
            $this->helper->link('Hover', '/', ['appearance' => 'hover']),
        );
    }

    public function testClassAppended(): void
    {
        $this->assertHtml(
            ['a' => ['href' => '/test', 'class' => 'link link-secondary extra'], 'Link', '/a'],
            $this->helper->link('Link', '/test', ['color' => 'secondary', 'class' => 'extra']),
        );
    }

    public function testAttributePassthrough(): void
    {
        $this->assertHtml(
            ['a' => ['href' => '/', 'class' => 'link', 'id' => 'my-link', 'data-test' => 'value'], 'Text', '/a'],
            $this->helper->link('Text', '/', ['id' => 'my-link', 'data-test' => 'value']),
        );
    }

    public function testEscapesByDefault(): void
    {
        $this->assertSame('<a href="/" class="link">&lt;b&gt;</a>', $this->helper->link('<b>', '/'));
    }

    public function testEscapeFalse(): void
    {
        $this->assertSame('<a href="/" class="link"><b>text</b></a>', $this->helper->link('<b>text</b>', '/', ['escape' => false]));
    }

    public function testUnknownColorThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->link('Link', '/', ['color' => 'nope']);
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['link.color.primary' => 'link-primary custom']);
        ClassMap::reset();

        $this->assertSame(
            '<a href="/" class="link link-primary custom">Home</a>',
            $this->helper->link('Home', '/', ['color' => 'primary']),
        );
    }

    public function testAllColors(): void
    {
        $colors = ['neutral', 'primary', 'secondary', 'accent', 'success', 'info', 'warning', 'error'];
        foreach ($colors as $color) {
            $result = $this->helper->link('Link', '/', ['color' => $color]);
            $this->assertStringContainsString("link-$color", $result);
        }
    }
}
