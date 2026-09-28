<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class CardTest extends TestCase
{
    private DataDisplayHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new DataDisplayHelper(new View());
    }

    public function testBodyOnly(): void
    {
        $this->assertHtml(
            [
                ['div' => ['class' => 'card']],
                ['div' => ['class' => 'card-body']],
                ['p' => []],
                'hello',
                '/p',
                '/div',
                '/div',
            ],
            $this->helper->card('hello'),
        );
    }

    public function testBodyEscaped(): void
    {
        $this->assertSame(
            '<div class="card"><div class="card-body"><p>&lt;b&gt;</p></div></div>',
            $this->helper->card('<b>'),
        );
    }

    public function testEscapeFalseRawWithoutParagraph(): void
    {
        $this->assertSame(
            '<div class="card"><div class="card-body"><b>raw</b></div></div>',
            $this->helper->card('<b>raw</b>', ['escape' => false]),
        );
    }

    public function testTitleEscapedEvenWithEscapeFalse(): void
    {
        $result = $this->helper->card('body', ['title' => '<b>', 'escape' => false]);
        $this->assertStringContainsString('<h2 class="card-title">&lt;b&gt;</h2>', $result);
        $this->assertStringNotContainsString('<p>', $result);
    }

    public function testActionsRawHtml(): void
    {
        $result = $this->helper->card('x', ['actions' => '<a href="/buy">Buy</a>']);
        $this->assertHtml(
            [
                ['div' => ['class' => 'card']],
                ['div' => ['class' => 'card-body']],
                ['p' => []],
                'x',
                '/p',
                ['div' => ['class' => 'card-actions']],
                ['a' => ['href' => '/buy']],
                'Buy',
                '/a',
                '/div',
                '/div',
                '/div',
            ],
            $result,
        );
    }

    public function testImage(): void
    {
        $this->assertHtml(
            [
                ['div' => ['class' => 'card']],
                ['figure' => []],
                ['img' => ['src' => '/img/shoes.jpg', 'alt' => 'Shoes']],
                '/figure',
                ['div' => ['class' => 'card-body']],
                ['p' => []],
                'x',
                '/p',
                '/div',
                '/div',
            ],
            $this->helper->card('x', ['image' => '/img/shoes.jpg', 'imageAlt' => 'Shoes']),
        );
    }

    public function testPartsOmittedWhenUnset(): void
    {
        $result = $this->helper->card('x', ['image' => '/img/a.jpg']);
        $this->assertStringNotContainsString('card-title', $result);
        $this->assertStringNotContainsString('card-actions', $result);
        $this->assertHtml(
            [
                ['div' => ['class' => 'card']],
                ['figure' => []],
                ['img' => ['src' => '/img/a.jpg', 'alt' => '']],
                '/figure',
                ['div' => ['class' => 'card-body']],
                ['p' => []],
                'x',
                '/p',
                '/div',
                '/div',
            ],
            $result,
        );
    }

    public function testModifiersClassAndAttributes(): void
    {
        $this->assertSame(
            '<div class="card card-md card-border card-side image-full card-border extra" id="c1">'
            . '<div class="card-body"><p>x</p></div></div>',
            $this->helper->card('x', [
                'size' => 'md',
                'modifier' => ['side', 'image-full'],
                'appearance' => 'border',
                'class' => 'card-border extra',
                'id' => 'c1',
            ]),
        );
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['card.part.body' => 'card-body custom']);
        ClassMap::reset();

        $this->assertSame(
            '<div class="card"><div class="card-body custom"><p>x</p></div></div>',
            $this->helper->card('x'),
        );
    }

    public function testUnknownSizeThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->card('x', ['size' => 'nope']);
    }
}
