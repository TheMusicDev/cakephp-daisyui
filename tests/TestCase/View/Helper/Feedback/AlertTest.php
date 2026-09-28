<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Feedback;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\FeedbackHelper;

class AlertTest extends TestCase
{
    private FeedbackHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new FeedbackHelper(new View());
    }

    public function testDefault(): void
    {
        $this->assertHtml(
            ['div' => ['role' => 'alert', 'class' => 'alert'], 'Message', '/div'],
            $this->helper->alert('Message'),
        );
    }

    public function testModifiersClassAndAttributes(): void
    {
        $this->assertHtml(
            ['div' => [
                'role' => 'alert',
                'class' => 'alert alert-info alert-soft alert-horizontal extra',
                'id' => 'a1',
            ], 'Message', '/div'],
            $this->helper->alert('Message', [
                'color' => 'info',
                'appearance' => 'soft',
                'direction' => 'horizontal',
                'class' => 'extra',
                'id' => 'a1',
            ]),
        );
    }

    public function testEscapesByDefault(): void
    {
        $this->assertSame('<div class="alert" role="alert">&lt;b&gt;</div>', $this->helper->alert('<b>'));
    }

    public function testEscapeFalse(): void
    {
        $this->assertSame(
            '<div class="alert" role="alert"><b>x</b></div>',
            $this->helper->alert('<b>x</b>', ['escape' => false]),
        );
    }

    public function testRoleDefaultsToAlert(): void
    {
        $this->assertSame('<div class="alert" role="alert">x</div>', $this->helper->alert('x'));
    }

    public function testRoleOverride(): void
    {
        $this->assertSame('<div class="alert" role="status">x</div>', $this->helper->alert('x', ['role' => 'status']));
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['alert.color.info' => 'alert-info custom']);
        ClassMap::reset();

        $this->assertSame(
            '<div class="alert alert-info custom" role="alert">x</div>',
            $this->helper->alert('x', ['color' => 'info']),
        );
    }

    public function testUnknownColorThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->alert('x', ['color' => 'nope']);
    }
}
