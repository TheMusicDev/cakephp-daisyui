<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Form;

use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\FormHelper;

/**
 * Phase 16: the control container, label, help line and error.
 */
class FieldsetTest extends TestCase
{
    private FormHelper $form;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        Router::reload();
        Router::setRequest(new ServerRequest());
        $this->form = new FormHelper(new View());
    }

    /**
     * @param array<string, array<string>> $errors Field errors.
     * @return void
     */
    private function createForm(array $errors = []): void
    {
        $this->form->create([
            'schema' => ['email' => ['type' => 'string'], 'name' => ['type' => 'string']],
            'errors' => $errors,
        ]);
    }

    public function testControlIsFieldsetWithLegendLabel(): void
    {
        $this->createForm();

        $this->assertHtml([
            'div' => ['class' => 'fieldset'],
            'label' => ['class' => 'fieldset-legend', 'for' => 'name'],
            'Name',
            '/label',
            'input' => ['type' => 'text', 'name' => 'name', 'class' => 'input', 'id' => 'name'],
            '/div',
        ], $this->form->control('name'));
    }

    public function testLabelTextAndArrayOptions(): void
    {
        $this->createForm();

        $this->assertStringContainsString(
            '<label class="fieldset-legend" for="name">Your name</label>',
            $this->form->control('name', ['label' => 'Your name']),
        );
        $this->assertStringContainsString(
            '<label class="big fieldset-legend" for="name">Your name</label>',
            $this->form->control('name', ['label' => ['text' => 'Your name', 'class' => 'big']]),
        );
    }

    public function testLabelIsEscaped(): void
    {
        $this->createForm();

        $this->assertStringContainsString('&lt;b&gt;', $this->form->control('name', ['label' => '<b>']));
    }

    public function testLabelFalse(): void
    {
        $this->createForm();

        $this->assertStringNotContainsString('<label', $this->form->control('name', ['label' => false]));
    }

    public function testHelpLineIsEscapedAndDescribesTheField(): void
    {
        $this->createForm();
        $html = $this->form->control('email', ['help' => 'We <never> share it.']);

        $this->assertStringContainsString('aria-describedby="email-help"', $html);
        $this->assertStringContainsString(
            '<p class="label" id="email-help">We &lt;never&gt; share it.</p></div>',
            $html,
        );
    }

    public function testErrorGetsErrorClassesAndAria(): void
    {
        $this->createForm(['email' => ['Please enter an email.']]);
        $html = $this->form->control('email');

        $this->assertStringContainsString('class="input input-error"', $html);
        $this->assertStringContainsString('aria-invalid="true"', $html);
        $this->assertStringContainsString('aria-describedby="email-error"', $html);
        $this->assertStringContainsString(
            '<p class="label text-error" id="email-error">Please enter an email.</p></div>',
            $html,
        );
    }

    public function testHelpAndErrorBothDescribeTheField(): void
    {
        $this->createForm(['email' => ['Required.']]);
        $html = $this->form->control('email', ['help' => 'Work address']);

        $this->assertStringContainsString('aria-describedby="email-help email-error"', $html);
        $this->assertLessThan(strpos($html, 'email-error">'), strpos($html, 'email-help">'));
    }

    public function testClassIsAppended(): void
    {
        $this->createForm();

        $this->assertStringContainsString('class="input w-full"', $this->form->control('name', ['class' => 'w-full']));
    }

    public function testInlineValidityJavascriptLikeCore(): void
    {
        $this->form->create(['schema' => ['name' => ['type' => 'string']], 'required' => ['name' => 'Fill it in']]);

        $this->assertStringContainsString('oninvalid', $this->form->control('name'));
    }

    public function testInlineValidityJavascriptCanBeTurnedOff(): void
    {
        $form = new FormHelper(new View(), ['autoSetCustomValidity' => false]);
        $form->create(['schema' => ['name' => ['type' => 'string']], 'required' => ['name' => 'Fill it in']]);
        $html = $form->control('name');

        $this->assertStringContainsString('required="required"', $html);
        $this->assertStringNotContainsString('oninvalid', $html);
    }

    public function testUserTemplatesWin(): void
    {
        $form = new FormHelper(new View(), ['templates' => ['inputContainer' => '<section>{{content}}</section>']]);
        $form->create(['schema' => ['name' => ['type' => 'string']]]);

        $this->assertStringStartsWith('<section>', $form->control('name'));
    }

    public function testHiddenAndSubmitAreLeftToCore(): void
    {
        $this->createForm();

        $this->assertSame('<input type="hidden" name="name" id="name">', $this->form->control('name', ['type' => 'hidden']));
    }

    public function testClassMapOverrideReachesContainer(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['fieldset.base' => 'fieldset max-w-md']);
        ClassMap::reset();
        $form = new FormHelper(new View());
        $form->create(['schema' => ['name' => ['type' => 'string']]]);

        $this->assertStringStartsWith('<div class="fieldset max-w-md">', $form->control('name'));
    }

    public function testFloatingLabel(): void
    {
        $this->createForm();
        $html = $this->form->control('email', ['floating' => true]);

        $this->assertHtml([
            'div' => ['class' => 'fieldset'],
            'label' => ['class' => 'floating-label'],
            'input' => [
                'type' => 'email', 'name' => 'email', 'placeholder' => 'Email',
                'aria-label' => 'Email', 'class' => 'input', 'id' => 'email',
            ],
            'span' => [],
            'Email',
            '/span',
            '/label',
            '/div',
        ], $html);
    }

    public function testFloatingLabelTextIsEscapedAndPlaceholderKept(): void
    {
        $this->createForm();
        $html = $this->form->control('name', ['floating' => true, 'label' => '<b>Name</b>', 'placeholder' => 'Jane']);

        $this->assertStringContainsString('<span>&lt;b&gt;Name&lt;/b&gt;</span>', $html);
        $this->assertStringContainsString('placeholder="Jane"', $html);
        // The accessible name is the label, not the placeholder.
        $this->assertStringContainsString('aria-label="&lt;b&gt;Name&lt;/b&gt;"', $html);
        $this->assertStringNotContainsString('floating=', $html);
    }
}
