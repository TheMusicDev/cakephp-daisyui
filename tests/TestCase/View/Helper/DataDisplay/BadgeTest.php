<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class BadgeTest extends TestCase
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
            ['span' => ['class' => 'badge'], 'New', '/span'],
            $this->helper->badge('New'),
        );
    }

    public function testModifiersClassAndAttributes(): void
    {
        $this->assertHtml(
            ['span' => ['class' => 'badge badge-primary badge-sm badge-outline extra', 'id' => 'b1'], 'New', '/span'],
            $this->helper->badge('New', [
                'color' => 'primary',
                'size' => 'sm',
                'appearance' => 'outline',
                'class' => 'extra',
                'id' => 'b1',
            ]),
        );
    }

    public function testEscapesByDefault(): void
    {
        $this->assertSame('<span class="badge">&lt;b&gt;</span>', $this->helper->badge('<b>'));
    }

    public function testEscapeFalse(): void
    {
        $this->assertSame('<span class="badge"><b>x</b></span>', $this->helper->badge('<b>x</b>', ['escape' => false]));
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['badge.color.primary' => 'badge-primary custom']);
        ClassMap::reset();

        $this->assertSame(
            '<span class="badge badge-primary custom">x</span>',
            $this->helper->badge('x', ['color' => 'primary']),
        );
    }

    public function testUnknownColorThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->badge('x', ['color' => 'nope']);
    }
}
