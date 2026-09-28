<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class StatusTest extends TestCase
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
            ['span' => ['class' => 'status', 'aria-hidden' => 'true']],
            $this->helper->status(),
        );
    }

    public function testColor(): void
    {
        $this->assertHtml(
            ['span' => ['class' => 'status status-primary', 'aria-hidden' => 'true']],
            $this->helper->status(['color' => 'primary']),
        );
    }

    public function testSize(): void
    {
        $this->assertHtml(
            ['span' => ['class' => 'status status-sm', 'aria-hidden' => 'true']],
            $this->helper->status(['size' => 'sm']),
        );
    }

    public function testColorAndSize(): void
    {
        $this->assertHtml(
            ['span' => ['class' => 'status status-success status-lg', 'aria-hidden' => 'true', 'id' => 's1']],
            $this->helper->status([
                'color' => 'success',
                'size' => 'lg',
                'id' => 's1',
            ]),
        );
    }

    public function testClassAppended(): void
    {
        $this->assertHtml(
            ['span' => ['class' => 'status extra', 'aria-hidden' => 'true']],
            $this->helper->status(['class' => 'extra']),
        );
    }

    public function testWithAriaLabel(): void
    {
        $this->assertHtml(
            ['span' => ['class' => 'status', 'role' => 'img', 'aria-label' => 'Online']],
            $this->helper->status(['aria-label' => 'Online']),
        );
    }

    public function testWithAriaLabelAndColor(): void
    {
        $html = $this->helper->status(['color' => 'success', 'aria-label' => 'Online']);
        $this->assertStringContainsString('aria-label="Online"', $html);
        $this->assertStringNotContainsString('aria-hidden', $html);
        $this->assertStringContainsString('status-success', $html);
        $this->assertStringContainsString('role="img"', $html);
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['status.color.primary' => 'status-primary custom']);
        ClassMap::reset();

        $html = $this->helper->status(['color' => 'primary']);
        $this->assertStringContainsString('status-primary custom', $html);
    }

    public function testUnknownColorThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->status(['color' => 'nope']);
    }

    public function testUnknownSizeThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->status(['size' => 'nope']);
    }

    public function testUnlabelledStatusHasNoRole(): void
    {
        $this->assertStringNotContainsString('role=', $this->helper->status());
    }
}
