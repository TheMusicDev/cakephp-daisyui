<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Feedback;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\FeedbackHelper;

class TooltipTest extends TestCase
{
    private FeedbackHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new FeedbackHelper(new View());
    }

    public function testDefault(): void
    {
        $this->assertSame(
            '<div class="tooltip" data-tip="Tooltip text"><button>Click me</button></div>',
            $this->helper->tooltip('<button>Click me</button>', 'Tooltip text'),
        );
    }

    public function testDataTipEscaping(): void
    {
        $this->assertSame(
            '<div class="tooltip" data-tip="&lt;b&gt;"><button>Click me</button></div>',
            $this->helper->tooltip('<button>Click me</button>', '<b>'),
        );
    }

    public function testContentRaw(): void
    {
        $this->assertSame(
            '<div class="tooltip" data-tip="Hover"><b>Bold</b></div>',
            $this->helper->tooltip('<b>Bold</b>', 'Hover'),
        );
    }

    public function testEscapeFalse(): void
    {
        $this->assertSame(
            '<div class="tooltip"><div class="tooltip-content"><b>Rich tip</b></div><button>Click me</button></div>',
            $this->helper->tooltip('<button>Click me</button>', '<b>Rich tip</b>', ['escape' => false]),
        );
    }

    public function testPlacementTop(): void
    {
        $this->assertSame(
            '<div class="tooltip tooltip-top" data-tip="Tip"><button>Click</button></div>',
            $this->helper->tooltip('<button>Click</button>', 'Tip', ['placement' => 'top']),
        );
    }

    public function testPlacementBottom(): void
    {
        $this->assertSame(
            '<div class="tooltip tooltip-bottom" data-tip="Tip"><button>Click</button></div>',
            $this->helper->tooltip('<button>Click</button>', 'Tip', ['placement' => 'bottom']),
        );
    }

    public function testPlacementLeft(): void
    {
        $this->assertSame(
            '<div class="tooltip tooltip-left" data-tip="Tip"><button>Click</button></div>',
            $this->helper->tooltip('<button>Click</button>', 'Tip', ['placement' => 'left']),
        );
    }

    public function testPlacementRight(): void
    {
        $this->assertSame(
            '<div class="tooltip tooltip-right" data-tip="Tip"><button>Click</button></div>',
            $this->helper->tooltip('<button>Click</button>', 'Tip', ['placement' => 'right']),
        );
    }

    public function testAlignmentStart(): void
    {
        $this->assertSame(
            '<div class="tooltip tooltip-start" data-tip="Tip"><button>Click</button></div>',
            $this->helper->tooltip('<button>Click</button>', 'Tip', ['alignment' => 'start']),
        );
    }

    public function testAlignmentCenter(): void
    {
        $this->assertSame(
            '<div class="tooltip tooltip-center" data-tip="Tip"><button>Click</button></div>',
            $this->helper->tooltip('<button>Click</button>', 'Tip', ['alignment' => 'center']),
        );
    }

    public function testAlignmentEnd(): void
    {
        $this->assertSame(
            '<div class="tooltip tooltip-end" data-tip="Tip"><button>Click</button></div>',
            $this->helper->tooltip('<button>Click</button>', 'Tip', ['alignment' => 'end']),
        );
    }

    public function testColorPrimary(): void
    {
        $this->assertSame(
            '<div class="tooltip tooltip-primary" data-tip="Tip"><button>Click</button></div>',
            $this->helper->tooltip('<button>Click</button>', 'Tip', ['color' => 'primary']),
        );
    }

    public function testColorSecondary(): void
    {
        $this->assertSame(
            '<div class="tooltip tooltip-secondary" data-tip="Tip"><button>Click</button></div>',
            $this->helper->tooltip('<button>Click</button>', 'Tip', ['color' => 'secondary']),
        );
    }

    public function testColorInfo(): void
    {
        $this->assertSame(
            '<div class="tooltip tooltip-info" data-tip="Tip"><button>Click</button></div>',
            $this->helper->tooltip('<button>Click</button>', 'Tip', ['color' => 'info']),
        );
    }

    public function testModifierOpen(): void
    {
        $this->assertSame(
            '<div class="tooltip tooltip-open" data-tip="Tip"><button>Click</button></div>',
            $this->helper->tooltip('<button>Click</button>', 'Tip', ['modifier' => 'open']),
        );
    }

    public function testClassOption(): void
    {
        $this->assertSame(
            '<div class="tooltip extra" data-tip="Tip"><button>Click</button></div>',
            $this->helper->tooltip('<button>Click</button>', 'Tip', ['class' => 'extra']),
        );
    }

    public function testMultiplePlacementAndAlignment(): void
    {
        $this->assertSame(
            '<div class="tooltip tooltip-top tooltip-start" data-tip="Tip"><button>Click</button></div>',
            $this->helper->tooltip('<button>Click</button>', 'Tip', ['placement' => 'top', 'alignment' => 'start']),
        );
    }

    public function testAttributePassthrough(): void
    {
        $this->assertSame(
            '<div class="tooltip" data-tip="Tip" id="t1" data-test="foo"><button>Click</button></div>',
            $this->helper->tooltip('<button>Click</button>', 'Tip', ['id' => 't1', 'data-test' => 'foo']),
        );
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['tooltip.color.primary' => 'tooltip-primary custom']);
        ClassMap::reset();

        $this->assertSame(
            '<div class="tooltip tooltip-primary custom" data-tip="Tip"><button>Click</button></div>',
            $this->helper->tooltip('<button>Click</button>', 'Tip', ['color' => 'primary']),
        );
    }

    public function testUnknownColorThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->tooltip('<button>Click</button>', 'Tip', ['color' => 'nope']);
    }

    public function testUnknownPlacementThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->tooltip('<button>Click</button>', 'Tip', ['placement' => 'nope']);
    }

    public function testUnknownAlignmentThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->tooltip('x', 'tip', ['alignment' => 'nope']);
    }
}
