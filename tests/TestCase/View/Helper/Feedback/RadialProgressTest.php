<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Feedback;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use InvalidArgumentException;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\FeedbackHelper;

class RadialProgressTest extends TestCase
{
    private FeedbackHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new FeedbackHelper(new View());
    }

    public function testDefaultMarkup(): void
    {
        $this->assertSame(
            '<div role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="--value:70;" class="radial-progress">70%</div>',
            $this->helper->radialProgress(70),
        );
    }

    public function testZeroValue(): void
    {
        $this->assertSame(
            '<div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="--value:0;" class="radial-progress">0%</div>',
            $this->helper->radialProgress(0),
        );
    }

    public function testHundredValue(): void
    {
        $this->assertSame(
            '<div role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="--value:100;" class="radial-progress">100%</div>',
            $this->helper->radialProgress(100),
        );
    }

    public function testCustomText(): void
    {
        $this->assertSame(
            '<div role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="--value:50;" class="radial-progress">Done</div>',
            $this->helper->radialProgress(50, ['text' => 'Done']),
        );
    }

    public function testTextEscaping(): void
    {
        $this->assertSame(
            '<div role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="--value:50;" class="radial-progress">&lt;b&gt;</div>',
            $this->helper->radialProgress(50, ['text' => '<b>']),
        );
    }

    public function testDiameter(): void
    {
        $this->assertSame(
            '<div role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="--value:60;--size:8rem;" class="radial-progress">60%</div>',
            $this->helper->radialProgress(60, ['diameter' => '8rem']),
        );
    }

    public function testThickness(): void
    {
        $this->assertSame(
            '<div role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="--value:80;--thickness:0.5rem;" class="radial-progress">80%</div>',
            $this->helper->radialProgress(80, ['thickness' => '0.5rem']),
        );
    }

    public function testDiameterAndThickness(): void
    {
        $this->assertSame(
            '<div role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="--value:45;--size:10rem;--thickness:1px;" class="radial-progress">45%</div>',
            $this->helper->radialProgress(45, ['diameter' => '10rem', 'thickness' => '1px']),
        );
    }

    public function testUserStyleAppended(): void
    {
        $this->assertSame(
            '<div role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="--value:70;color:red;" class="radial-progress">70%</div>',
            $this->helper->radialProgress(70, ['style' => 'color:red;']),
        );
    }

    public function testUserStyleAppendedAfterDiameter(): void
    {
        $this->assertSame(
            '<div role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="--value:50;--size:6rem;color:blue;" class="radial-progress">50%</div>',
            $this->helper->radialProgress(50, ['diameter' => '6rem', 'style' => 'color:blue;']),
        );
    }

    public function testRoleOverride(): void
    {
        $this->assertSame(
            '<div role="status" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="--value:50;" class="radial-progress">50%</div>',
            $this->helper->radialProgress(50, ['role' => 'status']),
        );
    }

    public function testAriaValuenowOverride(): void
    {
        $this->assertSame(
            '<div aria-valuenow="custom" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="--value:50;" class="radial-progress">50%</div>',
            $this->helper->radialProgress(50, ['aria-valuenow' => 'custom']),
        );
    }

    public function testClassOption(): void
    {
        $this->assertSame(
            '<div role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="--value:70;" class="radial-progress text-primary">70%</div>',
            $this->helper->radialProgress(70, ['class' => 'text-primary']),
        );
    }

    public function testAttributePassthrough(): void
    {
        $this->assertSame(
            '<div id="rp1" data-test="foo" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="--value:50;" class="radial-progress">50%</div>',
            $this->helper->radialProgress(50, ['id' => 'rp1', 'data-test' => 'foo']),
        );
    }

    public function testFloatValue(): void
    {
        $this->assertSame(
            '<div role="progressbar" aria-valuenow="75.5" aria-valuemin="0" aria-valuemax="100" style="--value:75.5;" class="radial-progress">75.5%</div>',
            $this->helper->radialProgress(75.5),
        );
    }

    public function testNegativeValueThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Radial progress value must be between 0 and 100, got -1.');
        $this->helper->radialProgress(-1);
    }

    public function testValueAbove100Throws(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Radial progress value must be between 0 and 100, got 101.');
        $this->helper->radialProgress(101);
    }

    public function testSizeOptionThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->radialProgress(50, ['size' => 'lg']);
    }

    public function testColorOptionThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->radialProgress(50, ['color' => 'primary']);
    }
}
