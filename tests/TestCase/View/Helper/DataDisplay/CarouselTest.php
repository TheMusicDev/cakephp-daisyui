<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class CarouselTest extends TestCase
{
    private DataDisplayHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new DataDisplayHelper(new View());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        ClassMap::reset();
    }

    public function testDefault(): void
    {
        $this->assertSame(
            '<div class="carousel"><div class="carousel-item">Item 1</div><div class="carousel-item">Item 2</div></div>',
            $this->helper->carousel(['Item 1', 'Item 2']),
        );
    }

    public function testStringItems(): void
    {
        $html = $this->helper->carousel(['<b>Bold</b>', '<i>Italic</i>']);
        $this->assertStringContainsString('<b>Bold</b>', $html);
        $this->assertStringContainsString('<i>Italic</i>', $html);
    }

    public function testArrayItemsWithContent(): void
    {
        $html = $this->helper->carousel([
            ['content' => '<span>Item 1</span>'],
            ['content' => '<span>Item 2</span>'],
        ]);
        $this->assertStringContainsString('<span>Item 1</span>', $html);
        $this->assertStringContainsString('<span>Item 2</span>', $html);
    }

    public function testItemWithClass(): void
    {
        $html = $this->helper->carousel([
            ['content' => 'Item 1', 'class' => 'extra-class'],
        ]);
        $this->assertStringContainsString('carousel-item extra-class', $html);
    }

    public function testItemWithId(): void
    {
        $html = $this->helper->carousel([
            ['content' => 'Item 1', 'id' => 'item-1'],
        ]);
        $this->assertStringContainsString('id="item-1"', $html);
    }

    public function testModifierStart(): void
    {
        $html = $this->helper->carousel(['Item'], ['modifier' => 'start']);
        $this->assertStringContainsString('carousel carousel-start', $html);
    }

    public function testModifierCenter(): void
    {
        $html = $this->helper->carousel(['Item'], ['modifier' => 'center']);
        $this->assertStringContainsString('carousel carousel-center', $html);
    }

    public function testModifierEnd(): void
    {
        $html = $this->helper->carousel(['Item'], ['modifier' => 'end']);
        $this->assertStringContainsString('carousel carousel-end', $html);
    }

    public function testDirectionHorizontal(): void
    {
        $html = $this->helper->carousel(['Item'], ['direction' => 'horizontal']);
        $this->assertStringContainsString('carousel carousel-horizontal', $html);
    }

    public function testDirectionVertical(): void
    {
        $html = $this->helper->carousel(['Item'], ['direction' => 'vertical']);
        $this->assertStringContainsString('carousel carousel-vertical', $html);
    }

    public function testModifierAndDirection(): void
    {
        $html = $this->helper->carousel(['Item'], ['modifier' => 'start', 'direction' => 'vertical']);
        $this->assertStringContainsString('carousel carousel-start carousel-vertical', $html);
    }

    public function testItemClass(): void
    {
        $html = $this->helper->carousel(['Item 1', 'Item 2'], ['itemClass' => 'w-full']);
        $this->assertStringContainsString('carousel-item w-full', $html);
    }

    public function testComponentClassAppended(): void
    {
        $html = $this->helper->carousel(['Item'], ['class' => 'extra-class', 'id' => 'carousel-1']);
        $this->assertStringContainsString('class="carousel extra-class"', $html);
        $this->assertStringContainsString('id="carousel-1"', $html);
    }

    public function testAttributePassthrough(): void
    {
        $html = $this->helper->carousel(['Item'], ['data-foo' => 'bar', 'id' => 'carousel-1']);
        $this->assertStringContainsString('data-foo="bar"', $html);
        $this->assertStringContainsString('id="carousel-1"', $html);
    }

    public function testUnknownModifierThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->carousel(['Item'], ['modifier' => 'nope']);
    }

    public function testUnknownDirectionThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->carousel(['Item'], ['direction' => 'nope']);
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['carousel.modifier.start' => 'carousel-start custom']);
        ClassMap::reset();

        $html = $this->helper->carousel(['Item'], ['modifier' => 'start']);
        $this->assertStringContainsString('carousel carousel-start custom', $html);
    }
}
