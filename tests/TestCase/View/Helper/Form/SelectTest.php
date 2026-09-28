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
 * Phase 19: select controls.
 */
class SelectTest extends TestCase
{
    private FormHelper $form;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        Router::reload();
        Router::setRequest(new ServerRequest());
        $this->form = new FormHelper(new View());
        $this->form->create(['schema' => ['color' => ['type' => 'string']], 'errors' => ['bad' => ['Pick one']]]);
    }

    public function testOptionsRenderDaisyUiSelect(): void
    {
        $this->assertHtml([
            'div' => ['class' => 'fieldset'],
            'label' => ['class' => 'fieldset-legend', 'for' => 'color'],
            'Color',
            '/label',
            'select' => ['name' => 'color', 'class' => 'select', 'id' => 'color'],
            ['option' => ['value' => 'r']], 'Red', '/option',
            ['option' => ['value' => 'b']], '&lt;Blue&gt;', '/option',
            '/select',
            '/div',
        ], $this->form->control('color', ['options' => ['r' => 'Red', 'b' => '<Blue>']]));
    }

    public function testModifiers(): void
    {
        $this->assertStringContainsString(
            'class="select select-info select-xs select-ghost"',
            $this->form->control('color', ['options' => ['a'], 'color' => 'info', 'size' => 'xs', 'appearance' => 'ghost']),
        );
    }

    public function testErrorColor(): void
    {
        $this->assertStringContainsString(
            'class="select select-error"',
            $this->form->control('bad', ['options' => ['a'], 'color' => 'info']),
        );
    }

    public function testMultipleSelectKeepsSelectClass(): void
    {
        $this->assertStringContainsString(
            'class="select"',
            $this->form->control('color', ['options' => ['a', 'b'], 'multiple' => true]),
        );
    }

    public function testMultipleCheckboxIsNotASelect(): void
    {
        $this->assertStringNotContainsString(
            'class="select',
            $this->form->control('color', ['options' => ['a', 'b'], 'multiple' => 'checkbox']),
        );
    }

    public function testUnknownValueThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->form->control('color', ['options' => ['a'], 'size' => 'nope']);
    }
}
