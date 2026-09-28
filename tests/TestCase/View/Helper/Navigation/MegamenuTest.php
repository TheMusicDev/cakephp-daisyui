<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Navigation;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use InvalidArgumentException;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\NavigationHelper;

class MegamenuTest extends TestCase
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
            ['label' => 'Item 1', 'content' => '<p>Content 1</p>'],
        ];

        $result = $this->helper->megamenu('my-menu', $items);

        $this->assertStringContainsString('class="megamenu', $result);
        $this->assertStringContainsString('id="my-menu"', $result);
        $this->assertStringContainsString('popover="auto"', $result);
        $this->assertStringContainsString('megamenu-active', $result);
    }

    public function testItemLabel(): void
    {
        $items = [
            ['label' => 'Item 1', 'content' => '<p>Content 1</p>'],
        ];

        $result = $this->helper->megamenu('my-menu', $items);

        $this->assertStringContainsString('Item 1', $result);
    }

    public function testItemContent(): void
    {
        $items = [
            ['label' => 'Item 1', 'content' => '<p>Content 1</p>'],
        ];

        $result = $this->helper->megamenu('my-menu', $items);

        $this->assertStringContainsString('<p>Content 1</p>', $result);
    }

    public function testPopoverIds(): void
    {
        $items = [
            ['label' => 'Item 1', 'content' => 'Content 1'],
            ['label' => 'Item 2', 'content' => 'Content 2'],
        ];

        $result = $this->helper->megamenu('my-menu', $items);

        $this->assertStringContainsString('id="my-menu-1"', $result);
        $this->assertStringContainsString('id="my-menu-2"', $result);
        $this->assertStringContainsString('popovertarget="my-menu-1"', $result);
        $this->assertStringContainsString('popovertarget="my-menu-2"', $result);
    }

    public function testSize(): void
    {
        $items = [
            ['label' => 'Item 1', 'content' => 'Content 1'],
        ];

        $result = $this->helper->megamenu('my-menu', $items, ['size' => 'sm']);

        $this->assertStringContainsString('megamenu-sm', $result);
    }

    public function testModifier(): void
    {
        $items = [
            ['label' => 'Item 1', 'content' => 'Content 1'],
        ];

        $result = $this->helper->megamenu('my-menu', $items, ['modifier' => 'wide']);

        $this->assertStringContainsString('megamenu-wide', $result);
    }

    public function testDirection(): void
    {
        $items = [
            ['label' => 'Item 1', 'content' => 'Content 1'],
        ];

        $result = $this->helper->megamenu('my-menu', $items, ['direction' => 'vertical']);

        $this->assertStringContainsString('megamenu-vertical', $result);
    }

    public function testClassAppended(): void
    {
        $items = [
            ['label' => 'Item 1', 'content' => 'Content 1'],
        ];

        $result = $this->helper->megamenu('my-menu', $items, ['class' => 'extra']);

        $this->assertStringContainsString('megamenu extra', $result);
    }

    public function testAttributePassthrough(): void
    {
        $items = [
            ['label' => 'Item 1', 'content' => 'Content 1'],
        ];

        $result = $this->helper->megamenu('my-menu', $items, ['data-test' => 'value']);

        $this->assertStringContainsString('data-test="value"', $result);
    }

    public function testLabelEscaped(): void
    {
        $items = [
            ['label' => '<b>Item 1</b>', 'content' => 'Content 1'],
        ];

        $result = $this->helper->megamenu('my-menu', $items);

        $this->assertStringContainsString('&lt;b&gt;Item 1&lt;/b&gt;', $result);
    }

    public function testContentRaw(): void
    {
        $items = [
            ['label' => 'Item 1', 'content' => '<p><b>Bold</b></p>'],
        ];

        $result = $this->helper->megamenu('my-menu', $items);

        $this->assertStringContainsString('<p><b>Bold</b></p>', $result);
    }

    public function testMoreThan10ItemsThrows(): void
    {
        $items = [];
        for ($i = 1; $i <= 11; $i++) {
            $items[] = ['label' => "Item {$i}", 'content' => "Content {$i}"];
        }

        $this->expectException(InvalidArgumentException::class);

        $this->helper->megamenu('my-menu', $items);
    }

    public function testUnknownSizeThrows(): void
    {
        $items = [
            ['label' => 'Item 1', 'content' => 'Content 1'],
        ];

        $this->expectException(OutOfBoundsException::class);

        $this->helper->megamenu('my-menu', $items, ['size' => 'nope']);
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['megamenu.base' => 'megamenu custom']);
        ClassMap::reset();

        $items = [
            ['label' => 'Item 1', 'content' => 'Content 1'],
        ];

        $result = $this->helper->megamenu('my-menu', $items);

        $this->assertStringContainsString('class="megamenu custom', $result);
    }

    public function testMegamenuButton(): void
    {
        $result = $this->helper->megamenuButton('Menu', 'my-menu');

        $this->assertStringContainsString('<button type="button"', $result);
        $this->assertStringContainsString('popovertarget="my-menu"', $result);
        $this->assertStringContainsString('Menu', $result);
    }

    public function testMegamenuButtonEscaped(): void
    {
        $result = $this->helper->megamenuButton('<b>Menu</b>', 'my-menu');

        $this->assertStringContainsString('&lt;b&gt;Menu&lt;/b&gt;', $result);
    }

    public function testMegamenuButtonClass(): void
    {
        $result = $this->helper->megamenuButton('Menu', 'my-menu', ['class' => 'sm:hidden']);

        $this->assertStringContainsString('sm:hidden', $result);
    }

    public function testStringKeyedItemsGetUniqueIds(): void
    {
        $html = $this->helper->megamenu('m', ['a' => ['label' => 'A'], 'b' => ['label' => 'B']]);

        $this->assertStringContainsString('id="m-1"', $html);
        $this->assertStringContainsString('id="m-2"', $html);
    }

    public function testButtonEscapeFalse(): void
    {
        $this->assertStringContainsString('<i>x</i>', $this->helper->megamenuButton('<i>x</i>', 'm', ['escape' => false]));
    }
}
