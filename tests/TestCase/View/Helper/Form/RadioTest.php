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
 * Phase 21: radio groups.
 */
class RadioTest extends TestCase
{
    private FormHelper $form;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        Router::reload();
        Router::setRequest(new ServerRequest());
        $this->form = new FormHelper(new View());
        $this->form->create([
            'schema' => ['size' => ['type' => 'string']],
            'defaults' => ['size' => 'm'],
            'errors' => ['bad' => ['Pick one']],
        ]);
    }

    public function testRadioGroupIsFieldsetWithLegend(): void
    {
        $html = $this->form->control('size', ['type' => 'radio', 'options' => ['s' => 'Small', 'm' => 'Medium']]);

        $this->assertStringStartsWith('<fieldset class="fieldset"><legend class="fieldset-legend">Size</legend>', $html);
        $this->assertStringContainsString(
            '<label class="label" for="size-s"><input type="radio" name="size" value="s" id="size-s" class="radio">Small</label>',
            $html,
        );
        $this->assertStringContainsString('checked="checked" class="radio">Medium</label>', $html);
        $this->assertStringEndsWith('</fieldset>', $html);
    }

    public function testOptionLabelsAreEscaped(): void
    {
        $this->assertStringContainsString(
            '>&lt;S&gt;</label>',
            $this->form->control('size', ['type' => 'radio', 'options' => ['s' => '<S>']]),
        );
    }

    public function testModifiers(): void
    {
        $this->assertStringContainsString(
            'class="radio radio-warning radio-xs"',
            $this->form->control('size', ['type' => 'radio', 'options' => ['s' => 'S'], 'color' => 'warning', 'size' => 'xs']),
        );
    }

    public function testErrorColorAndMessage(): void
    {
        $html = $this->form->control('bad', ['type' => 'radio', 'options' => ['a' => 'A']]);

        $this->assertStringContainsString('class="radio radio-error"', $html);
        $this->assertStringContainsString('<p class="label text-error" id="bad-error">Pick one</p></fieldset>', $html);
    }

    public function testLegendText(): void
    {
        $this->assertStringContainsString(
            '<legend class="fieldset-legend">T-shirt size</legend>',
            $this->form->control('size', ['type' => 'radio', 'options' => ['s' => 'S'], 'label' => 'T-shirt size']),
        );
    }

    public function testUnknownValueThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->form->control('size', ['type' => 'radio', 'options' => ['s' => 'S'], 'color' => 'nope']);
    }
}
