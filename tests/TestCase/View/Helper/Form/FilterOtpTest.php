<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Form;

use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use InvalidArgumentException;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\FormHelper;

/**
 * Phases 64 (filter) and 65 (OTP).
 */
class FilterOtpTest extends TestCase
{
    private FormHelper $form;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        Router::reload();
        Router::setRequest(new ServerRequest());
        $this->form = new FormHelper(new View());
        $this->form->create(['schema' => [], 'defaults' => ['os' => 'mac'], 'errors' => ['bad' => ['Wrong code']]]);
    }

    public function testFilterRendersResetAndButtonRadios(): void
    {
        $html = $this->form->control('os', ['type' => 'filter', 'options' => ['win' => 'Windows', 'mac' => '<Mac>']]);

        $this->assertStringStartsWith('<fieldset class="fieldset"><legend class="fieldset-legend">Os</legend><div class="filter">', $html);
        $this->assertStringContainsString('value="" aria-label="×" class="btn filter-reset"', $html);
        $this->assertStringContainsString('value="win" aria-label="Windows" class="btn"', $html);
        $this->assertStringContainsString('aria-label="&lt;Mac&gt;"', $html);
        $this->assertStringContainsString('id="os-mac" checked="checked"', $html);
        $this->assertStringNotContainsString('<label', $html);
        $this->assertStringNotContainsString('<form', $html);
    }

    public function testFilterResetTextAndClass(): void
    {
        $html = $this->form->control('os', ['type' => 'filter', 'options' => ['a' => 'A'], 'reset' => 'All', 'class' => 'gap-2']);

        $this->assertStringContainsString('<div class="filter gap-2">', $html);
        $this->assertStringContainsString('aria-label="All"', $html);
    }

    public function testOtpMarkup(): void
    {
        $this->assertStringContainsString(
            '<label class="otp otp-sm otp-joined"><span></span><span></span><span></span><span></span>'
            . '<input type="text" name="code" autocomplete="one-time-code" inputmode="numeric" maxlength="4" pattern="[0-9]{4}" id="code"></label>',
            $this->form->control('code', ['type' => 'otp', 'length' => 4, 'size' => 'sm', 'modifier' => 'joined']),
        );
    }

    public function testOtpDefaultsToSixDigitsAndKeepsLegend(): void
    {
        $html = $this->form->control('code', ['type' => 'otp']);

        $this->assertStringContainsString('<label class="fieldset-legend" for="code">Code</label>', $html);
        $this->assertSame(6, substr_count($html, '<span></span>'));
        $this->assertStringContainsString('pattern="[0-9]{6}"', $html);
    }

    public function testOtpErrorColor(): void
    {
        $html = $this->form->control('bad', ['type' => 'otp', 'color' => 'primary']);

        $this->assertStringContainsString('<label class="otp otp-error">', $html);
        $this->assertStringContainsString('<p class="label text-error" id="bad-error">Wrong code</p>', $html);
    }

    public function testOtpLengthOutOfRangeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->form->control('code', ['type' => 'otp', 'length' => 3]);
    }

    public function testOtpUnknownValueThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->form->control('code', ['type' => 'otp', 'size' => 'nope']);
    }
}
