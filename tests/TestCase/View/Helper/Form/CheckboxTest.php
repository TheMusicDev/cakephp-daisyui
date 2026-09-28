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
 * Phase 20: single checkboxes and multi-checkbox groups.
 */
class CheckboxTest extends TestCase
{
    private FormHelper $form;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        Router::reload();
        Router::setRequest(new ServerRequest());
        $this->form = new FormHelper(new View());
        $this->form->create(['schema' => ['agree' => ['type' => 'boolean']], 'errors' => ['bad' => ['Required']]]);
    }

    public function testBooleanRendersCheckboxInsideLabel(): void
    {
        $this->assertSame(
            '<div class="fieldset"><input type="hidden" name="agree" value="0">'
            . '<label class="label" for="agree"><input type="checkbox" name="agree" value="1" class="checkbox" id="agree">Agree</label>'
            . '</div>',
            $this->form->control('agree'),
        );
    }

    public function testLabelTextIsEscaped(): void
    {
        $this->assertStringContainsString('>&lt;b&gt;</label>', $this->form->control('agree', ['label' => '<b>']));
    }

    public function testModifiers(): void
    {
        $this->assertStringContainsString(
            'class="checkbox checkbox-success checkbox-lg"',
            $this->form->control('agree', ['color' => 'success', 'size' => 'lg']),
        );
    }

    public function testErrorColorAndMessage(): void
    {
        $html = $this->form->control('bad', ['type' => 'checkbox', 'color' => 'primary']);

        $this->assertStringContainsString('class="checkbox checkbox-error"', $html);
        $this->assertStringContainsString('<p class="label text-error" id="bad-error">Required</p></div>', $html);
    }

    public function testMultiCheckboxIsFieldsetWithLegend(): void
    {
        $html = $this->form->control('tags', ['multiple' => 'checkbox', 'options' => ['a' => 'A', 'b' => '<B>']]);

        $this->assertStringStartsWith(
            '<fieldset class="fieldset"><legend class="fieldset-legend">Tags</legend>',
            $html,
        );
        $this->assertStringContainsString(
            '<label class="label" for="tags-a"><input type="checkbox" name="tags[]" value="a" id="tags-a" class="checkbox">A</label>',
            $html,
        );
        $this->assertStringContainsString('>&lt;B&gt;</label>', $html);
        $this->assertStringNotContainsString('<div class="checkbox">', $html);
        $this->assertStringEndsWith('</fieldset>', $html);
    }

    public function testMultiCheckboxLegendTextAndHelp(): void
    {
        $html = $this->form->control('tags', [
            'multiple' => 'checkbox',
            'options' => ['a' => 'A'],
            'label' => 'Pick <tags>',
            'help' => 'Any number',
        ]);

        $this->assertStringContainsString('<legend class="fieldset-legend">Pick &lt;tags&gt;</legend>', $html);
        $this->assertStringContainsString('<p class="label" id="tags-help">Any number</p></fieldset>', $html);
    }

    public function testMultiCheckboxWithoutLegend(): void
    {
        $html = $this->form->control('tags', ['multiple' => 'checkbox', 'options' => ['a' => 'A'], 'label' => false]);

        $this->assertStringNotContainsString('<legend', $html);
    }

    public function testUnknownValueThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->form->control('agree', ['color' => 'nope']);
    }
}
