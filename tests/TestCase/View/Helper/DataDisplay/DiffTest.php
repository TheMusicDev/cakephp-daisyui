<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class DiffTest extends TestCase
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
        $html = $this->helper->diff('<img src="a.png">', '<img src="b.png">');
        $this->assertStringContainsString('class="diff"', $html);
        $this->assertStringContainsString('tabindex="0"', $html);
        $this->assertStringContainsString('diff-item-1', $html);
        $this->assertStringContainsString('diff-item-2', $html);
        $this->assertStringContainsString('diff-resizer', $html);
    }

    public function testClassAppended(): void
    {
        $html = $this->helper->diff('A', 'B', ['class' => 'aspect-16/9']);
        $this->assertStringContainsString('class="diff aspect-16/9"', $html);
    }

    public function testAttributePassthrough(): void
    {
        $html = $this->helper->diff('A', 'B', ['id' => 'diff-1']);
        $this->assertStringContainsString('id="diff-1"', $html);
    }
}
