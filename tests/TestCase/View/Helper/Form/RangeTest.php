<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Form;

use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\FormHelper;

/**
 * Phase 24: range sliders.
 */
class RangeTest extends TestCase
{
    private FormHelper $form;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        Router::reload();
        Router::setRequest(new ServerRequest());
        $this->form = new FormHelper(new View());
        $this->form->create(['schema' => ['volume' => ['type' => 'integer']], 'defaults' => ['volume' => 40], 'errors' => ['bad' => ['Nope']]]);
    }

    public function testRangeGetsClassAndDefaultMinMax(): void
    {
        $html = $this->form->control('volume', ['type' => 'range']);

        $this->assertMatchesRegularExpression('/<input type="range" name="volume"[^>]* class="range"/', $html);
        $this->assertStringContainsString('min="0"', $html);
        $this->assertStringContainsString('max="100"', $html);
        $this->assertStringContainsString('value="40"', $html);
    }

    public function testCustomMinMaxWin(): void
    {
        $html = $this->form->control('volume', ['type' => 'range', 'min' => 10, 'max' => 20]);

        $this->assertStringContainsString('min="10"', $html);
        $this->assertStringContainsString('max="20"', $html);
    }

    public function testModifiersIncludingVertical(): void
    {
        $this->assertStringContainsString(
            'class="range range-accent range-lg range-vertical"',
            $this->form->control('volume', ['type' => 'range', 'color' => 'accent', 'size' => 'lg', 'direction' => 'vertical']),
        );
    }

    public function testErrorColor(): void
    {
        $this->assertStringContainsString('class="range range-error"', $this->form->control('bad', ['type' => 'range']));
    }

    public function testUnknownValueThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->form->control('volume', ['type' => 'range', 'direction' => 'nope']);
    }
}
