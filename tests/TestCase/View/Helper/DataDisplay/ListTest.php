<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class ListTest extends TestCase
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
        $this->assertSame(
            '<ul class="list"><li class="list-row">Item 1</li><li class="list-row">Item 2</li></ul>',
            $this->helper->list(['Item 1', 'Item 2']),
        );
    }

    public function testArrayItemsWithText(): void
    {
        $this->assertSame(
            '<ul class="list"><li class="list-row">Text 1</li><li class="list-row">Text 2</li></ul>',
            $this->helper->list([
                ['text' => 'Text 1'],
                ['text' => 'Text 2'],
            ]),
        );
    }

    public function testArrayItemsWithContent(): void
    {
        $html = $this->helper->list([
            ['content' => '<b>Bold</b>'],
            ['content' => '<i>Italic</i>'],
        ]);
        $this->assertStringContainsString('<b>Bold</b>', $html);
        $this->assertStringContainsString('<i>Italic</i>', $html);
    }

    public function testItemClassAppended(): void
    {
        $html = $this->helper->list([
            ['text' => 'Item 1', 'class' => 'extra-class'],
        ]);
        $this->assertStringContainsString('list-row extra-class', $html);
    }

    public function testItemClassArray(): void
    {
        $html = $this->helper->list([
            ['text' => 'Item 1', 'class' => ['extra', 'classes']],
        ]);
        $this->assertStringContainsString('list-row extra classes', $html);
    }

    public function testComponentClassAppended(): void
    {
        $html = $this->helper->list(['Item'], ['class' => 'extra-class', 'id' => 'my-list']);
        $this->assertStringContainsString('class="list extra-class"', $html);
        $this->assertStringContainsString('id="my-list"', $html);
    }

    public function testAttributePassthrough(): void
    {
        $html = $this->helper->list(['Item'], ['data-foo' => 'bar', 'id' => 'list-1']);
        $this->assertStringContainsString('data-foo="bar"', $html);
        $this->assertStringContainsString('id="list-1"', $html);
    }

    public function testEscapesByDefault(): void
    {
        $this->assertSame(
            '<ul class="list"><li class="list-row">&lt;b&gt;</li></ul>',
            $this->helper->list(['<b>']),
        );
    }

    public function testEscapeFalse(): void
    {
        $html = $this->helper->list([['content' => '<b>bold</b>']], ['escape' => false]);
        $this->assertStringContainsString('<b>bold</b>', $html);
    }

    public function testContentWinsOverText(): void
    {
        $html = $this->helper->list([
            ['text' => 'Text', 'content' => '<span>Content</span>'],
        ]);
        $this->assertStringContainsString('<span>Content</span>', $html);
        $this->assertStringNotContainsString('Text', $html);
    }

    public function testListColumnClassGrow(): void
    {
        $this->assertSame('list-col-grow', $this->helper->listColumnClass('grow'));
    }

    public function testListColumnClassWrap(): void
    {
        $this->assertSame('list-col-wrap', $this->helper->listColumnClass('wrap'));
    }

    public function testListColumnClassUnknownThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->listColumnClass('nope');
    }
}
