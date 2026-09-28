<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use InvalidArgumentException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class CountdownTest extends TestCase
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
        $html = $this->helper->countdown(42);
        $this->assertStringContainsString('class="countdown"', $html);
        $this->assertStringContainsString('--value:42', $html);
        $this->assertStringContainsString('aria-live="polite"', $html);
        $this->assertStringContainsString('aria-label="42"', $html);
    }

    public function testMinValue(): void
    {
        $html = $this->helper->countdown(0);
        $this->assertStringContainsString('--value:0', $html);
    }

    public function testMaxValue(): void
    {
        $html = $this->helper->countdown(999);
        $this->assertStringContainsString('--value:999', $html);
    }

    public function testClassAppended(): void
    {
        $html = $this->helper->countdown(10, ['class' => 'extra', 'id' => 'cd-1']);
        $this->assertStringContainsString('class="countdown extra"', $html);
        $this->assertStringContainsString('id="cd-1"', $html);
    }

    public function testAttributePassthrough(): void
    {
        $html = $this->helper->countdown(5, ['data-foo' => 'bar']);
        $this->assertStringContainsString('data-foo="bar"', $html);
    }

    public function testBelowMinThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->helper->countdown(-1);
    }

    public function testAboveMaxThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->helper->countdown(1000);
    }
}
