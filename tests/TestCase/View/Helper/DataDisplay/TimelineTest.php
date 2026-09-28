<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class TimelineTest extends TestCase
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
        $html = $this->helper->timeline([
            ['start' => 'Start 1', 'end' => 'End 1'],
            ['start' => 'Start 2', 'end' => 'End 2'],
        ]);
        $this->assertStringContainsString('<ul class="timeline">', $html);
        $this->assertStringContainsString('timeline-start">Start 1', $html);
        $this->assertStringContainsString('timeline-end">End 1', $html);
        // Should have hr dividers
        $this->assertStringContainsString('<hr>', $html);
    }

    public function testAllParts(): void
    {
        $html = $this->helper->timeline([
            ['start' => 'Start', 'middle' => '<circle>M</circle>', 'end' => 'End'],
        ]);
        $this->assertStringContainsString('timeline-start">Start', $html);
        $this->assertStringContainsString('timeline-middle"><circle>M</circle>', $html);
        $this->assertStringContainsString('timeline-end">End', $html);
    }

    public function testDirectionVertical(): void
    {
        $html = $this->helper->timeline([['start' => 'S']], ['direction' => 'vertical']);
        $this->assertStringContainsString('timeline-vertical', $html);
    }

    public function testDirectionHorizontal(): void
    {
        $html = $this->helper->timeline([['start' => 'S']], ['direction' => 'horizontal']);
        $this->assertStringContainsString('timeline-horizontal', $html);
    }

    public function testModifierSnapIcon(): void
    {
        $html = $this->helper->timeline([['start' => 'S']], ['modifier' => 'snap-icon']);
        $this->assertStringContainsString('timeline-snap-icon', $html);
    }

    public function testModifierCompact(): void
    {
        $html = $this->helper->timeline([['start' => 'S']], ['modifier' => 'compact']);
        $this->assertStringContainsString('timeline-compact', $html);
    }

    public function testModifierArray(): void
    {
        $html = $this->helper->timeline([['start' => 'S']], ['modifier' => ['snap-icon', 'compact']]);
        $this->assertStringContainsString('timeline-snap-icon', $html);
        $this->assertStringContainsString('timeline-compact', $html);
    }

    public function testConnectTrue(): void
    {
        $html = $this->helper->timeline([
            ['start' => 'Item 1'],
            ['start' => 'Item 2'],
        ], ['connect' => true]);
        // Should have hr dividers between items
        $this->assertStringContainsString('<hr>', $html);
    }

    public function testConnectFalse(): void
    {
        $html = $this->helper->timeline([
            ['start' => 'Item 1'],
            ['start' => 'Item 2'],
        ], ['connect' => false]);
        // Should not have hr dividers
        $this->assertStringNotContainsString('<hr>', $html);
    }

    public function testConnectDefault(): void
    {
        $html = $this->helper->timeline([
            ['start' => 'Item 1'],
            ['start' => 'Item 2'],
        ]);
        // Default is connect = true
        $this->assertStringContainsString('<hr>', $html);
    }

    public function testBoxStart(): void
    {
        $html = $this->helper->timeline([['start' => 'Boxed', 'box' => 'start']]);
        $this->assertStringContainsString('timeline-start', $html);
        $this->assertStringContainsString('timeline-box', $html);
    }

    public function testBoxEnd(): void
    {
        $html = $this->helper->timeline([['end' => 'Boxed', 'box' => 'end']]);
        $this->assertStringContainsString('timeline-end', $html);
        $this->assertStringContainsString('timeline-box', $html);
    }

    public function testComponentClassAppended(): void
    {
        $html = $this->helper->timeline([['start' => 'S']], ['class' => 'extra-class']);
        $this->assertStringContainsString('timeline extra-class', $html);
    }

    public function testAttributePassthrough(): void
    {
        $html = $this->helper->timeline([['start' => 'S']], ['data-foo' => 'bar', 'id' => 'timeline-1']);
        $this->assertStringContainsString('data-foo="bar"', $html);
        $this->assertStringContainsString('id="timeline-1"', $html);
    }

    public function testStartEscaped(): void
    {
        $this->assertSame(
            '<ul class="timeline"><li><div class="timeline-start">&lt;b&gt;</div></li></ul>',
            $this->helper->timeline([['start' => '<b>']]),
        );
    }

    public function testEndEscaped(): void
    {
        $this->assertSame(
            '<ul class="timeline"><li><div class="timeline-end">&lt;script&gt;</div></li></ul>',
            $this->helper->timeline([['end' => '<script>']]),
        );
    }

    public function testStartEscapeFalse(): void
    {
        $html = $this->helper->timeline([['start' => '<b>bold</b>', 'escape' => false]]);
        $this->assertStringContainsString('<b>bold</b>', $html);
    }

    public function testEndEscapeFalse(): void
    {
        $html = $this->helper->timeline([['end' => '<i>italic</i>', 'escape' => false]]);
        $this->assertStringContainsString('<i>italic</i>', $html);
    }

    public function testMiddleNotEscaped(): void
    {
        $html = $this->helper->timeline([['middle' => '<svg>icon</svg>']]);
        $this->assertStringContainsString('<svg>icon</svg>', $html);
    }

    public function testUnknownDirectionThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->timeline([['start' => 'S']], ['direction' => 'nope']);
    }

    public function testUnknownModifierThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->timeline([['start' => 'S']], ['modifier' => 'nope']);
    }

    public function testHrFirstItemOmitted(): void
    {
        $html = $this->helper->timeline([
            ['start' => 'First'],
            ['start' => 'Second'],
        ]);
        // Count hr occurrences - should be 1 (between items), not 2
        // First item shouldn't have leading hr
        $count = substr_count($html, '<hr>');
        $this->assertSame(1, $count);
    }

    public function testHrLastItemOmitted(): void
    {
        $html = $this->helper->timeline([
            ['start' => 'First'],
            ['start' => 'Second'],
        ]);
        // Last item shouldn't have trailing hr, which we already test above
        // Just verify the count
        $count = substr_count($html, '<hr>');
        $this->assertSame(1, $count);
    }
}
