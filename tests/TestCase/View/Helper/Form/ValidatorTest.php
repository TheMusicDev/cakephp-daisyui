<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Form;

use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use InvalidArgumentException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\FormHelper;

/**
 * Phase 26: native-validation styling (`validator => true`).
 */
class ValidatorTest extends TestCase
{
    private FormHelper $form;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        Router::reload();
        Router::setRequest(new ServerRequest());
        $this->form = new FormHelper(new View());
        $this->form->create(null);
    }

    public function testValidatorClassOnInputSelectTextarea(): void
    {
        $this->assertStringContainsString('class="input validator"', $this->form->control('a', ['validator' => true]));
        $this->assertStringContainsString(
            'class="select validator"',
            $this->form->control('b', ['options' => ['x'], 'validator' => true]),
        );
        $this->assertStringContainsString(
            'class="textarea validator"',
            $this->form->control('c', ['type' => 'textarea', 'validator' => true]),
        );
    }

    public function testHintIsEscapedAndDescribesTheField(): void
    {
        $html = $this->form->control('email', ['type' => 'email', 'validator' => true, 'hint' => 'A <valid> email']);

        $this->assertStringContainsString('aria-describedby="email-hint"', $html);
        $this->assertStringContainsString('<p class="validator-hint" id="email-hint">A &lt;valid&gt; email</p></div>', $html);
    }

    public function testHintFollowsHelp(): void
    {
        $html = $this->form->control('email', ['validator' => true, 'hint' => 'Hint', 'help' => 'Help']);

        $this->assertStringContainsString('aria-describedby="email-help email-hint"', $html);
        $this->assertLessThan(strpos($html, 'email-hint">'), strpos($html, 'email-help">'));
    }

    public function testNoValidatorNoHint(): void
    {
        $html = $this->form->control('email', ['hint' => 'Ignored without validator']);

        $this->assertStringNotContainsString('validator', $html);
        $this->assertStringNotContainsString('hint=', $html);
    }

    public function testOtherFieldTypesAreRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('not "checkbox"');
        $this->form->control('agree', ['type' => 'checkbox', 'validator' => true]);
    }
}
