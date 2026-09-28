<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Actions;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\Helper\ActionsHelper;

class DropdownTest extends TestCase
{
    private ActionsHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        Configure::delete('DaisyUi');
        $this->helper = new ActionsHelper(new View());
    }

    public function testDefaultRender(): void
    {
        $html = $this->helper->dropdown('Menu', '<ul><li>Item</li></ul>');

        $this->assertStringContainsString('<details class="dropdown">', $html);
        $this->assertStringContainsString('<summary class="btn">Menu</summary>', $html);
        $this->assertStringContainsString('<div class="dropdown-content"><ul><li>Item</li></ul></div>', $html);
        $this->assertStringContainsString('</details>', $html);
    }

    public function testDefaultSummaryClassIsBtnByDefault(): void
    {
        $html = $this->helper->dropdown('Menu', '<div>Content</div>');

        $this->assertStringContainsString('<summary class="btn">', $html);
    }

    public function testCustomButtonClass(): void
    {
        $html = $this->helper->dropdown('Menu', '<div>Content</div>', ['buttonClass' => 'custom-btn']);

        $this->assertStringContainsString('<summary class="custom-btn">', $html);
    }

    public function testButtonClassAsArray(): void
    {
        $html = $this->helper->dropdown('Menu', '<div>Content</div>', ['buttonClass' => ['btn', 'btn-primary']]);

        $this->assertStringContainsString('<summary class="btn btn-primary">', $html);
    }

    public function testOpenAttribute(): void
    {
        $html = $this->helper->dropdown('Menu', '<div>Content</div>', ['open' => true]);

        $this->assertStringContainsString('<details class="dropdown" open="open">', $html);
    }

    public function testPlacementSingle(): void
    {
        $html = $this->helper->dropdown('Menu', '<div>Content</div>', ['placement' => 'end']);

        $this->assertStringContainsString('class="dropdown dropdown-end"', $html);
    }

    public function testPlacementArray(): void
    {
        $html = $this->helper->dropdown('Menu', '<div>Content</div>', ['placement' => ['top', 'end']]);

        $this->assertStringContainsString('class="dropdown dropdown-top dropdown-end"', $html);
    }

    public function testModifierHover(): void
    {
        $html = $this->helper->dropdown('Menu', '<div>Content</div>', ['modifier' => 'hover']);

        $this->assertStringContainsString('class="dropdown dropdown-hover"', $html);
    }

    public function testModifierOpen(): void
    {
        $html = $this->helper->dropdown('Menu', '<div>Content</div>', ['modifier' => 'open']);

        $this->assertStringContainsString('class="dropdown dropdown-open"', $html);
    }

    public function testButtonTextIsEscapedByDefault(): void
    {
        $html = $this->helper->dropdown('Save & <b>exit</b>', '<div>Content</div>');

        $this->assertStringContainsString('<summary class="btn">Save &amp; &lt;b&gt;exit&lt;/b&gt;</summary>', $html);
    }

    public function testEscapeFalse(): void
    {
        $html = $this->helper->dropdown('<b>Bold</b>', '<div>Content</div>', ['escape' => false]);

        $this->assertStringContainsString('<summary class="btn"><b>Bold</b></summary>', $html);
    }

    public function testContentIsRawHtml(): void
    {
        $html = $this->helper->dropdown('Menu', '<ul class="menu"><li><a href="#">Item 1</a></li></ul>');

        $this->assertStringContainsString('<div class="dropdown-content"><ul class="menu"><li><a href="#">Item 1</a></li></ul></div>', $html);
    }

    public function testClassAppended(): void
    {
        $html = $this->helper->dropdown('Menu', '<div>Content</div>', ['class' => 'my-dropdown']);

        $this->assertStringContainsString('class="dropdown my-dropdown"', $html);
    }

    public function testAttributePassthrough(): void
    {
        $html = $this->helper->dropdown('Menu', '<div>Content</div>', ['id' => 'menu-1', 'data-test' => 'value']);

        $this->assertStringContainsString('id="menu-1"', $html);
        $this->assertStringContainsString('data-test="value"', $html);
    }

    public function testUnknownPlacementThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->dropdown('Menu', '<div>Content</div>', ['placement' => 'nope']);
    }

    public function testUnknownModifierThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->dropdown('Menu', '<div>Content</div>', ['modifier' => 'nope']);
    }
}
