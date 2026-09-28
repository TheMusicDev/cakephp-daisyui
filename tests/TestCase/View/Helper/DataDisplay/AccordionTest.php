<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class AccordionTest extends TestCase
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
        $html = $this->helper->accordion([
            ['title' => 'Item 1', 'content' => 'Content 1'],
            ['title' => 'Item 2', 'content' => 'Content 2'],
        ]);
        $this->assertStringContainsString('type="radio"', $html);
        $this->assertStringContainsString('name="accordion-1"', $html);
        $this->assertStringContainsString('collapse-title">Item 1', $html);
        $this->assertStringContainsString('collapse-content">Content 1', $html);
    }

    public function testGeneratedNames(): void
    {
        $html1 = $this->helper->accordion([
            ['title' => 'Item 1', 'content' => 'Content 1'],
        ]);
        $html2 = $this->helper->accordion([
            ['title' => 'Item 2', 'content' => 'Content 2'],
        ]);
        $this->assertStringContainsString('name="accordion-1"', $html1);
        $this->assertStringContainsString('name="accordion-2"', $html2);
    }

    public function testExplicitName(): void
    {
        $html = $this->helper->accordion([
            ['title' => 'Item 1', 'content' => 'Content 1'],
        ], ['name' => 'my-accordion']);
        $this->assertStringContainsString('name="my-accordion"', $html);
    }

    public function testOpenItem(): void
    {
        $html = $this->helper->accordion([
            ['title' => 'Item 1', 'content' => 'Content 1', 'open' => true],
        ]);
        $this->assertStringContainsString('checked="checked"', $html);
    }

    public function testOpenFalseOmitted(): void
    {
        $html = $this->helper->accordion([
            ['title' => 'Item 1', 'content' => 'Content 1', 'open' => false],
        ]);
        $this->assertStringNotContainsString('checked=', $html);
    }

    public function testModifierArrowAppliedToAll(): void
    {
        $html = $this->helper->accordion([
            ['title' => 'Item 1', 'content' => 'Content 1'],
            ['title' => 'Item 2', 'content' => 'Content 2'],
        ], ['modifier' => 'arrow']);
        // Both items should have the arrow modifier
        $count = substr_count($html, 'collapse-arrow');
        $this->assertGreaterThanOrEqual(2, $count);
    }

    public function testModifierPlusAppliedToAll(): void
    {
        $html = $this->helper->accordion([
            ['title' => 'Item 1', 'content' => 'Content 1'],
            ['title' => 'Item 2', 'content' => 'Content 2'],
        ], ['modifier' => 'plus']);
        // Both items should have the plus modifier
        $count = substr_count($html, 'collapse-plus');
        $this->assertGreaterThanOrEqual(2, $count);
    }

    public function testModifierArray(): void
    {
        $html = $this->helper->accordion([
            ['title' => 'Item 1', 'content' => 'Content 1'],
        ], ['modifier' => ['arrow', 'open']]);
        $this->assertStringContainsString('collapse-arrow', $html);
        $this->assertStringContainsString('collapse-open', $html);
    }

    public function testItemClassAppliedToAll(): void
    {
        $html = $this->helper->accordion([
            ['title' => 'Item 1', 'content' => 'Content 1'],
            ['title' => 'Item 2', 'content' => 'Content 2'],
        ], ['class' => 'extra-class']);
        // Both items should have the extra class
        $count = substr_count($html, 'extra-class');
        $this->assertGreaterThanOrEqual(2, $count);
    }

    public function testTitleEscaped(): void
    {
        $html = $this->helper->accordion([
            ['title' => '<b>', 'content' => 'Content'],
        ]);
        $this->assertStringContainsString('&lt;b&gt;', $html);
        $this->assertStringNotContainsString('<b>', $html);
    }

    public function testContentNotEscaped(): void
    {
        $html = $this->helper->accordion([
            ['title' => 'Title', 'content' => '<span>html</span>'],
        ]);
        $this->assertStringContainsString('<span>html</span>', $html);
    }

    public function testUnknownModifierThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->accordion([
            ['title' => 'Title', 'content' => 'Content'],
        ], ['modifier' => 'nope']);
    }

    public function testAriaLabelIsEscapedOnce(): void
    {
        $html = $this->helper->accordion([['title' => 'Q&A', 'content' => 'c']]);

        $this->assertStringContainsString('aria-label="Q&amp;A"', $html);
        $this->assertStringNotContainsString('&amp;amp;', $html);
    }
}
