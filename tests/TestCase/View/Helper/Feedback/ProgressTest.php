<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Feedback;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\FeedbackHelper;

class ProgressTest extends TestCase
{
    private FeedbackHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new FeedbackHelper(new View());
    }

    public function testDefaultValue(): void
    {
        $this->assertSame(
            '<progress class="progress" max="100" value="50"></progress>',
            $this->helper->progress(50),
        );
    }

    public function testIndeterminateProgressBar(): void
    {
        $this->assertSame(
            '<progress class="progress" max="100"></progress>',
            $this->helper->progress(null),
        );
    }

    public function testCustomMax(): void
    {
        $this->assertSame(
            '<progress class="progress" max="200" value="40"></progress>',
            $this->helper->progress(40, ['max' => 200]),
        );
    }

    public function testFloatValue(): void
    {
        $this->assertSame(
            '<progress class="progress" max="100" value="50.5"></progress>',
            $this->helper->progress(50.5),
        );
    }

    public function testFloatMax(): void
    {
        $this->assertSame(
            '<progress class="progress" max="100.5" value="25"></progress>',
            $this->helper->progress(25, ['max' => 100.5]),
        );
    }

    public function testColorOption(): void
    {
        $this->assertSame(
            '<progress class="progress progress-primary" max="100" value="70"></progress>',
            $this->helper->progress(70, ['color' => 'primary']),
        );
    }

    public function testMultipleColors(): void
    {
        $this->assertSame(
            '<progress class="progress progress-primary progress-success" max="100" value="50"></progress>',
            $this->helper->progress(50, ['color' => ['primary', 'success']]),
        );
    }

    public function testClassOption(): void
    {
        $this->assertSame(
            '<progress class="progress extra" max="100" value="50"></progress>',
            $this->helper->progress(50, ['class' => 'extra']),
        );
    }

    public function testAttributePassthrough(): void
    {
        $this->assertSame(
            '<progress class="progress" max="100" id="p1" data-test="foo" value="50"></progress>',
            $this->helper->progress(50, ['id' => 'p1', 'data-test' => 'foo']),
        );
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['progress.color.primary' => 'progress-primary custom']);
        ClassMap::reset();

        $this->assertSame(
            '<progress class="progress progress-primary custom" max="100" value="50"></progress>',
            $this->helper->progress(50, ['color' => 'primary']),
        );
    }

    public function testUnknownColorThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->progress(50, ['color' => 'nope']);
    }
}
