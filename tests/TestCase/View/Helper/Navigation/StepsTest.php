<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Navigation;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\NavigationHelper;

class StepsTest extends TestCase
{
    private NavigationHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new NavigationHelper(new View());
    }

    public function testDefault(): void
    {
        $this->assertSame(
            '<ul class="steps"><li class="step">Step 1</li><li class="step">Step 2</li></ul>',
            $this->helper->steps(['Step 1', 'Step 2']),
        );
    }

    public function testArrayItemsWithText(): void
    {
        $this->assertSame(
            '<ul class="steps"><li class="step">Text 1</li><li class="step">Text 2</li></ul>',
            $this->helper->steps([
                ['text' => 'Text 1'],
                ['text' => 'Text 2'],
            ]),
        );
    }

    public function testColorPrimary(): void
    {
        $html = $this->helper->steps([['text' => 'Step 1', 'color' => 'primary']]);
        $this->assertStringContainsString('step-primary', $html);
    }

    public function testIconRendered(): void
    {
        $html = $this->helper->steps([['text' => 'Step 1', 'icon' => '<svg>icon</svg>']]);
        $this->assertStringContainsString('<span class="step-icon"><svg>icon</svg></span>', $html);
        $this->assertStringContainsString('Step 1', $html);
    }

    public function testContentAttribute(): void
    {
        $html = $this->helper->steps([['text' => 'Step 1', 'content' => 'Custom Content']]);
        $this->assertStringContainsString('data-content="Custom Content"', $html);
    }

    public function testCurrentAttribute(): void
    {
        $html = $this->helper->steps([['text' => 'Step 1', 'current' => true]]);
        $this->assertStringContainsString('aria-current="step"', $html);
    }

    public function testCurrentFalseOmitted(): void
    {
        $html = $this->helper->steps([['text' => 'Step 1', 'current' => false]]);
        $this->assertStringNotContainsString('aria-current', $html);
    }

    public function testDirectionVertical(): void
    {
        $html = $this->helper->steps(['Step 1'], ['direction' => 'vertical']);
        $this->assertStringContainsString('steps-vertical', $html);
    }

    public function testDirectionHorizontal(): void
    {
        $html = $this->helper->steps(['Step 1'], ['direction' => 'horizontal']);
        $this->assertStringContainsString('steps-horizontal', $html);
    }

    public function testComponentClassAppended(): void
    {
        $html = $this->helper->steps(['Step 1'], ['class' => 'extra-class']);
        $this->assertStringContainsString('steps extra-class', $html);
    }

    public function testAttributePassthrough(): void
    {
        $html = $this->helper->steps(['Step 1'], ['data-foo' => 'bar', 'id' => 'steps-1']);
        $this->assertStringContainsString('data-foo="bar"', $html);
        $this->assertStringContainsString('id="steps-1"', $html);
    }

    public function testEscapesByDefault(): void
    {
        $this->assertSame(
            '<ul class="steps"><li class="step">&lt;b&gt;</li></ul>',
            $this->helper->steps(['<b>']),
        );
    }

    public function testUnknownColorThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->steps([['text' => 'Step 1', 'color' => 'nope']]);
    }

    public function testAllColors(): void
    {
        $colors = ['neutral', 'primary', 'secondary', 'accent', 'info', 'success', 'warning', 'error'];
        foreach ($colors as $color) {
            $html = $this->helper->steps([['text' => 'Step', 'color' => $color]]);
            $this->assertStringContainsString("step-$color", $html);
        }
    }
}
