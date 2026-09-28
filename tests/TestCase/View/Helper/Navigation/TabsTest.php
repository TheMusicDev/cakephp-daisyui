<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Navigation;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\NavigationHelper;

class TabsTest extends TestCase
{
    private NavigationHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new NavigationHelper(new View());
    }

    public function testLinkModeDefault(): void
    {
        $html = $this->helper->tabs([
            ['title' => 'Tab 1'],
            ['title' => 'Tab 2'],
        ]);
        $this->assertStringContainsString('type="button"', $html);
        $this->assertStringContainsString('Tab 1', $html);
        $this->assertStringContainsString('Tab 2', $html);
    }

    public function testLinkModeWithUrl(): void
    {
        $html = $this->helper->tabs([
            ['title' => 'Link Tab', 'url' => '/path'],
        ]);
        $this->assertStringContainsString('<a', $html);
        $this->assertStringContainsString('/path', $html);
        $this->assertStringContainsString('Link Tab', $html);
    }

    public function testLinkModeActiveTab(): void
    {
        $html = $this->helper->tabs([
            ['title' => 'Tab 1', 'active' => true],
        ]);
        $this->assertStringContainsString('tab-active', $html);
    }

    public function testLinkModeDisabledTab(): void
    {
        $html = $this->helper->tabs([
            ['title' => 'Tab 1', 'disabled' => true],
        ]);
        $this->assertStringContainsString('tab-disabled', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertStringContainsString('tabindex="-1"', $html);
    }

    public function testContentModeDefault(): void
    {
        $html = $this->helper->tabs([
            ['title' => 'Tab 1', 'content' => '<p>Content 1</p>'],
            ['title' => 'Tab 2', 'content' => '<p>Content 2</p>'],
        ]);
        $this->assertStringContainsString('<input type="radio"', $html);
        $this->assertStringContainsString('tab-content', $html);
        $this->assertStringContainsString('<p>Content 1</p>', $html);
        $this->assertStringContainsString('<p>Content 2</p>', $html);
    }

    public function testContentModeGeneratedName(): void
    {
        $html1 = $this->helper->tabs([
            ['title' => 'Tab 1', 'content' => 'Content 1'],
        ]);
        $html2 = $this->helper->tabs([
            ['title' => 'Tab 2', 'content' => 'Content 2'],
        ]);
        // Each call should get a different generated name
        $this->assertStringContainsString('name="tabs-1"', $html1);
        $this->assertStringContainsString('name="tabs-2"', $html2);
    }

    public function testContentModeExplicitName(): void
    {
        $html = $this->helper->tabs([
            ['title' => 'Tab 1', 'content' => 'Content 1'],
        ], ['name' => 'my-tabs']);
        $this->assertStringContainsString('name="my-tabs"', $html);
    }

    public function testContentModeActiveTab(): void
    {
        $html = $this->helper->tabs([
            ['title' => 'Tab 1', 'content' => 'Content 1', 'active' => true],
        ]);
        $this->assertStringContainsString('checked="checked"', $html);
    }

    public function testContentModeDisabledTab(): void
    {
        $html = $this->helper->tabs([
            ['title' => 'Tab 1', 'content' => 'Content 1', 'disabled' => true],
        ]);
        $this->assertStringContainsString('disabled="disabled"', $html);
    }

    public function testAppearanceBox(): void
    {
        $html = $this->helper->tabs([['title' => 'Tab']], ['appearance' => 'box']);
        $this->assertStringContainsString('tabs-box', $html);
    }

    public function testAppearanceBorder(): void
    {
        $html = $this->helper->tabs([['title' => 'Tab']], ['appearance' => 'border']);
        $this->assertStringContainsString('tabs-border', $html);
    }

    public function testAppearanceLift(): void
    {
        $html = $this->helper->tabs([['title' => 'Tab']], ['appearance' => 'lift']);
        $this->assertStringContainsString('tabs-lift', $html);
    }

    public function testSizeXs(): void
    {
        $html = $this->helper->tabs([['title' => 'Tab']], ['size' => 'xs']);
        $this->assertStringContainsString('tabs-xs', $html);
    }

    public function testPlacementTop(): void
    {
        $html = $this->helper->tabs([['title' => 'Tab']], ['placement' => 'top']);
        $this->assertStringContainsString('tabs-top', $html);
    }

    public function testPlacementBottom(): void
    {
        $html = $this->helper->tabs([['title' => 'Tab']], ['placement' => 'bottom']);
        $this->assertStringContainsString('tabs-bottom', $html);
    }

    public function testComponentClassAppended(): void
    {
        $html = $this->helper->tabs([['title' => 'Tab']], ['class' => 'extra-class']);
        $this->assertStringContainsString('tabs extra-class', $html);
    }

    public function testAttributePassthrough(): void
    {
        $html = $this->helper->tabs([['title' => 'Tab']], ['data-foo' => 'bar', 'id' => 'tabs-1']);
        $this->assertStringContainsString('data-foo="bar"', $html);
        $this->assertStringContainsString('id="tabs-1"', $html);
    }

    public function testTitleEscaped(): void
    {
        $html = $this->helper->tabs([['title' => '<b>']]);
        $this->assertStringContainsString('&lt;b&gt;', $html);
        $this->assertStringNotContainsString('<b>', $html);
    }

    public function testUnknownAppearanceThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->tabs([['title' => 'Tab']], ['appearance' => 'nope']);
    }

    public function testUnknownSizeThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->tabs([['title' => 'Tab']], ['size' => 'nope']);
    }

    public function testUnknownPlacementThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->tabs([['title' => 'Tab']], ['placement' => 'nope']);
    }

    public function testRoleTablist(): void
    {
        $html = $this->helper->tabs([['title' => 'Tab']]);
        $this->assertStringContainsString('role="tablist"', $html);
    }

    public function testContentModeAriaLabelIsEscapedOnce(): void
    {
        $html = $this->helper->tabs([['title' => 'Q&A <x>', 'content' => 'c']]);

        $this->assertStringContainsString('role="tab" aria-label="Q&amp;A &lt;x&gt;"', $html);
        $this->assertStringNotContainsString('&amp;amp;', $html);
    }

    public function testActiveLinkTabIsAriaSelected(): void
    {
        $this->assertStringContainsString(
            'aria-selected="true"',
            $this->helper->tabs([['title' => 'One', 'active' => true], ['title' => 'Two']]),
        );
    }

    public function testDisabledTabsAreReallyDisabled(): void
    {
        $html = $this->helper->tabs([
            ['title' => 'Billing', 'url' => '/billing', 'disabled' => true],
            ['title' => 'Settings', 'disabled' => true],
        ]);

        $this->assertStringNotContainsString('href=', $html);
        $this->assertStringContainsString('type="button" disabled="disabled">Settings</button>', $html);
    }
}
