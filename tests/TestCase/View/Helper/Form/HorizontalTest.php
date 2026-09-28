<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Form;

use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\FormHelper;

/**
 * Phase 28: `create(..., ['align' => 'horizontal'])`.
 */
class HorizontalTest extends TestCase
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

    public function testControlsGetTwoColumnContainerAndOffsetHelp(): void
    {
        $this->form->create(null, ['align' => 'horizontal']);
        $html = $this->form->control('name', ['help' => 'Full name']);

        $this->assertStringStartsWith('<div class="fieldset grid-cols-[10rem_1fr] items-center gap-x-4">', $html);
        $this->assertStringContainsString('<p class="label col-start-2" id="name-help">', $html);
    }

    public function testErrorIsOffset(): void
    {
        $this->form->create(['schema' => [], 'errors' => ['bad' => ['Nope']]], ['align' => 'horizontal']);

        $this->assertStringContainsString(
            '<p class="label text-error col-start-2" id="bad-error">Nope</p>',
            $this->form->control('bad', ['type' => 'text']),
        );
    }

    public function testCheckboxGoesInTheFieldColumn(): void
    {
        $this->form->create(null, ['align' => 'horizontal']);

        $this->assertStringContainsString(
            '<label class="label col-start-2" for="agree">',
            $this->form->control('agree', ['type' => 'checkbox']),
        );
    }

    public function testGroupsStayStacked(): void
    {
        $this->form->create(null, ['align' => 'horizontal']);

        $this->assertStringStartsWith(
            '<fieldset class="fieldset">',
            $this->form->control('size', ['type' => 'radio', 'options' => ['s' => 'S']]),
        );
    }

    public function testAlignIsNotRenderedAndResetsAfterEnd(): void
    {
        $this->assertStringNotContainsString('align', $this->form->create(null, ['align' => 'horizontal']));
        $this->form->end();

        $this->assertStringStartsWith('<div class="fieldset">', $this->form->control('name'));
    }

    public function testVerticalByDefault(): void
    {
        $this->form->create(null);

        $this->assertStringStartsWith('<div class="fieldset">', $this->form->control('name'));
    }
}
