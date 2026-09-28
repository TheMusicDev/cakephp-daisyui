<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Mockup;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\MockupHelper;

class CodeTest extends TestCase
{
    private MockupHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new MockupHelper(new View());
    }

    public function testDefault(): void
    {
        $html = $this->helper->code(['Line 1', 'Line 2']);
        $this->assertStringContainsString('class="mockup-code"', $html);
        $this->assertStringContainsString('Line 1', $html);
    }

    public function testStringInput(): void
    {
        $html = $this->helper->code("Line 1\nLine 2");
        $this->assertStringContainsString('Line 1', $html);
        $this->assertStringContainsString('Line 2', $html);
    }

    public function testNumbered(): void
    {
        $html = $this->helper->code(['Line 1'], ['numbered' => true]);
        $this->assertStringContainsString('data-prefix="1"', $html);
    }

    public function testLinesHaveNoStrayClass(): void
    {
        $this->assertSame(
            '<div class="mockup-code"><pre data-prefix="$"><code>npm i &lt;x&gt;</code></pre></div>',
            $this->helper->code('npm i <x>', ['prefix' => '$']),
        );
    }
}
