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
 * Phase 18: textarea controls.
 */
class TextareaTest extends TestCase
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
            'schema' => ['bio' => ['type' => 'text']],
            'defaults' => ['bio' => '<b>hi</b>'],
            'errors' => ['bad' => ['Too short']],
        ]);
    }

    public function testTextColumnRendersDaisyUiTextarea(): void
    {
        $this->assertHtml([
            'div' => ['class' => 'fieldset'],
            'label' => ['class' => 'fieldset-legend', 'for' => 'bio'],
            'Bio',
            '/label',
            'textarea' => ['name' => 'bio', 'class' => 'textarea', 'id' => 'bio', 'rows' => '5'],
            '&lt;b&gt;hi&lt;/b&gt;',
            '/textarea',
            '/div',
        ], $this->form->control('bio'));
    }

    public function testModifiers(): void
    {
        $this->assertStringContainsString(
            'class="textarea textarea-secondary textarea-lg textarea-ghost"',
            $this->form->control('bio', ['color' => 'secondary', 'size' => 'lg', 'appearance' => 'ghost']),
        );
    }

    public function testErrorColor(): void
    {
        $html = $this->form->control('bad', ['type' => 'textarea', 'color' => 'primary']);

        $this->assertStringContainsString('class="textarea textarea-error"', $html);
    }

    public function testUnknownValueThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->form->control('bio', ['color' => 'nope']);
    }

    public function testRowsAttributePassesThrough(): void
    {
        $this->assertStringContainsString('rows="3"', $this->form->control('bio', ['rows' => 3]));
    }
}
