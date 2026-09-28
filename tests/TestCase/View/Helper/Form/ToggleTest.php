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
 * Phase 22: toggles (`type => toggle`).
 */
class ToggleTest extends TestCase
{
    private FormHelper $form;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        Router::reload();
        Router::setRequest(new ServerRequest());
        $this->form = new FormHelper(new View());
        $this->form->create(['schema' => ['active' => ['type' => 'boolean']], 'defaults' => ['active' => true], 'errors' => ['bad' => ['On please']]]);
    }

    public function testToggleIsCheckboxWithToggleClass(): void
    {
        $this->assertSame(
            '<div class="fieldset"><input type="hidden" name="active" value="0">'
            . '<label class="label" for="active"><input type="checkbox" name="active" value="1" class="toggle" id="active" checked="checked">Active</label>'
            . '</div>',
            $this->form->control('active', ['type' => 'toggle']),
        );
    }

    public function testModifiers(): void
    {
        $this->assertStringContainsString(
            'class="toggle toggle-primary toggle-sm"',
            $this->form->control('active', ['type' => 'toggle', 'color' => 'primary', 'size' => 'sm']),
        );
    }

    public function testErrorColor(): void
    {
        $this->assertStringContainsString(
            'class="toggle toggle-error"',
            $this->form->control('bad', ['type' => 'toggle']),
        );
    }

    public function testUnknownValueThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->form->control('active', ['type' => 'toggle', 'size' => 'nope']);
    }
}
