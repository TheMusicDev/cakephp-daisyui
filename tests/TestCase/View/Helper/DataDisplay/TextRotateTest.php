<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use InvalidArgumentException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class TextRotateTest extends TestCase
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
        $html = $this->helper->textRotate(['Line 1', 'Line 2']);
        $this->assertStringContainsString('class="text-rotate"', $html);
        $this->assertStringContainsString('Line 1', $html);
        $this->assertStringContainsString('Line 2', $html);
    }

    public function testWithInnerClass(): void
    {
        $html = $this->helper->textRotate(['A', 'B'], ['innerClass' => 'custom']);
        $this->assertStringContainsString('class="custom"', $html);
    }

    public function testTooFewThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->helper->textRotate(['Only one']);
    }

    public function testTooManyThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->helper->textRotate(array_fill(0, 7, 'Line'));
    }

    public function testPerLineClass(): void
    {
        $this->assertStringContainsString(
            '<span class="bg-teal-400">Designers</span>',
            $this->helper->textRotate([['text' => 'Designers', 'class' => 'bg-teal-400'], 'Developers']),
        );
    }
}
