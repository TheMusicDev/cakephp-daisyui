<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class CollapseTest extends TestCase
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
        $html = $this->helper->collapse('Title', 'Content');
        $this->assertStringContainsString('<details class="collapse">', $html);
        $this->assertStringContainsString('collapse-title">Title', $html);
        $this->assertStringContainsString('collapse-content">Content', $html);
    }

    public function testModifierArrow(): void
    {
        $html = $this->helper->collapse('Title', 'Content', ['modifier' => 'arrow']);
        $this->assertStringContainsString('collapse-arrow', $html);
    }

    public function testModifierPlus(): void
    {
        $html = $this->helper->collapse('Title', 'Content', ['modifier' => 'plus']);
        $this->assertStringContainsString('collapse-plus', $html);
    }

    public function testModifierOpen(): void
    {
        $html = $this->helper->collapse('Title', 'Content', ['modifier' => 'open']);
        $this->assertStringContainsString('collapse-open', $html);
    }

    public function testModifierClose(): void
    {
        $html = $this->helper->collapse('Title', 'Content', ['modifier' => 'close']);
        $this->assertStringContainsString('collapse-close', $html);
    }

    public function testModifierArray(): void
    {
        $html = $this->helper->collapse('Title', 'Content', ['modifier' => ['arrow', 'open']]);
        $this->assertStringContainsString('collapse-arrow', $html);
        $this->assertStringContainsString('collapse-open', $html);
    }

    public function testOpenAttribute(): void
    {
        $html = $this->helper->collapse('Title', 'Content', ['open' => true]);
        $this->assertStringContainsString('open="open"', $html);
    }

    public function testOpenFalseOmitted(): void
    {
        $html = $this->helper->collapse('Title', 'Content', ['open' => false]);
        $this->assertStringNotContainsString('open=', $html);
    }

    public function testComponentClassAppended(): void
    {
        $html = $this->helper->collapse('Title', 'Content', ['class' => 'extra-class']);
        $this->assertStringContainsString('collapse extra-class', $html);
    }

    public function testAttributePassthrough(): void
    {
        $html = $this->helper->collapse('Title', 'Content', ['data-foo' => 'bar', 'id' => 'collapse-1']);
        $this->assertStringContainsString('data-foo="bar"', $html);
        $this->assertStringContainsString('id="collapse-1"', $html);
    }

    public function testTitleEscaped(): void
    {
        $this->assertSame(
            '<details class="collapse"><summary class="collapse-title">&lt;b&gt;</summary><div class="collapse-content">content</div></details>',
            $this->helper->collapse('<b>', 'content'),
        );
    }

    public function testContentNotEscaped(): void
    {
        $html = $this->helper->collapse('Title', '<span>html</span>');
        $this->assertStringContainsString('<span>html</span>', $html);
    }

    public function testUnknownModifierThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->collapse('Title', 'Content', ['modifier' => 'nope']);
    }
}
