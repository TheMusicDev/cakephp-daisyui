<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class KbdTest extends TestCase
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
        $this->assertHtml(
            ['kbd' => ['class' => 'kbd'], 'K', '/kbd'],
            $this->helper->kbd('K'),
        );
    }

    public function testSize(): void
    {
        $this->assertHtml(
            ['kbd' => ['class' => 'kbd kbd-sm'], 'Shift', '/kbd'],
            $this->helper->kbd('Shift', ['size' => 'sm']),
        );
    }

    public function testMultipleSizeAndClass(): void
    {
        $this->assertHtml(
            ['kbd' => ['class' => 'kbd kbd-lg extra', 'id' => 'k1'], 'Del', '/kbd'],
            $this->helper->kbd('Del', [
                'size' => 'lg',
                'class' => 'extra',
                'id' => 'k1',
            ]),
        );
    }

    public function testEscapesByDefault(): void
    {
        $this->assertSame('<kbd class="kbd">&lt;b&gt;</kbd>', $this->helper->kbd('<b>'));
    }

    public function testEscapeFalse(): void
    {
        $this->assertSame('<kbd class="kbd"><b>x</b></kbd>', $this->helper->kbd('<b>x</b>', ['escape' => false]));
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['kbd.size.sm' => 'kbd-sm custom']);
        ClassMap::reset();

        $this->assertSame(
            '<kbd class="kbd kbd-sm custom">x</kbd>',
            $this->helper->kbd('x', ['size' => 'sm']),
        );
    }

    public function testUnknownSizeThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->kbd('x', ['size' => 'nope']);
    }
}
