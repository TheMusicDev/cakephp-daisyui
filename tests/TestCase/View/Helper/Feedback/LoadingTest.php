<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Feedback;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\FeedbackHelper;

class LoadingTest extends TestCase
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
            ['span' => ['class' => 'loading', 'role' => 'status', 'aria-label' => 'Loading']],
            $this->helper->loading(),
        );
    }

    public function testAppearanceSpinner(): void
    {
        $this->assertHtml(
            ['span' => ['class' => 'loading loading-spinner', 'role' => 'status', 'aria-label' => 'Loading']],
            $this->helper->loading(['appearance' => 'spinner']),
        );
    }

    public function testAppearanceDots(): void
    {
        $this->assertHtml(
            ['span' => ['class' => 'loading loading-dots', 'role' => 'status', 'aria-label' => 'Loading']],
            $this->helper->loading(['appearance' => 'dots']),
        );
    }

    public function testAppearanceRing(): void
    {
        $this->assertHtml(
            ['span' => ['class' => 'loading loading-ring', 'role' => 'status', 'aria-label' => 'Loading']],
            $this->helper->loading(['appearance' => 'ring']),
        );
    }

    public function testAppearanceBall(): void
    {
        $this->assertHtml(
            ['span' => ['class' => 'loading loading-ball', 'role' => 'status', 'aria-label' => 'Loading']],
            $this->helper->loading(['appearance' => 'ball']),
        );
    }

    public function testAppearanceBars(): void
    {
        $this->assertHtml(
            ['span' => ['class' => 'loading loading-bars', 'role' => 'status', 'aria-label' => 'Loading']],
            $this->helper->loading(['appearance' => 'bars']),
        );
    }

    public function testAppearanceInfinity(): void
    {
        $this->assertHtml(
            ['span' => ['class' => 'loading loading-infinity', 'role' => 'status', 'aria-label' => 'Loading']],
            $this->helper->loading(['appearance' => 'infinity']),
        );
    }

    public function testSize(): void
    {
        $this->assertHtml(
            ['span' => ['class' => 'loading loading-sm', 'role' => 'status', 'aria-label' => 'Loading']],
            $this->helper->loading(['size' => 'sm']),
        );
    }

    public function testAppearanceAndSize(): void
    {
        $html = $this->helper->loading([
            'appearance' => 'spinner',
            'size' => 'lg',
            'id' => 'l1',
        ]);
        $this->assertStringContainsString('loading loading-lg loading-spinner', $html);
        $this->assertStringContainsString('role="status"', $html);
        $this->assertStringContainsString('aria-label="Loading"', $html);
        $this->assertStringContainsString('id="l1"', $html);
    }

    public function testClassAppended(): void
    {
        $this->assertHtml(
            ['span' => ['class' => 'loading extra', 'role' => 'status', 'aria-label' => 'Loading']],
            $this->helper->loading(['class' => 'extra']),
        );
    }

    public function testOverrideRole(): void
    {
        $html = $this->helper->loading(['role' => 'presentation']);
        $this->assertStringContainsString('role="presentation"', $html);
        $this->assertStringContainsString('aria-label="Loading"', $html);
    }

    public function testOverrideAriaLabel(): void
    {
        $html = $this->helper->loading(['aria-label' => 'Processing']);
        $this->assertStringContainsString('aria-label="Processing"', $html);
        $this->assertStringContainsString('role="status"', $html);
    }

    public function testOverrideBoth(): void
    {
        $html = $this->helper->loading(['role' => 'presentation', 'aria-label' => 'Please wait']);
        $this->assertStringContainsString('role="presentation"', $html);
        $this->assertStringContainsString('aria-label="Please wait"', $html);
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['loading.appearance.spinner' => 'loading-spinner custom']);
        ClassMap::reset();

        $html = $this->helper->loading(['appearance' => 'spinner']);
        $this->assertStringContainsString('loading-spinner custom', $html);
    }

    public function testUnknownAppearanceThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->loading(['appearance' => 'nope']);
    }

    public function testUnknownSizeThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->loading(['size' => 'nope']);
    }
}
