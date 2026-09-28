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
 * Phase 23: file inputs.
 */
class FileInputTest extends TestCase
{
    private FormHelper $form;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        Router::reload();
        Router::setRequest(new ServerRequest());
        $this->form = new FormHelper(new View());
        $this->form->create(['schema' => ['photo' => ['type' => 'string']], 'errors' => ['bad' => ['Too big']]], ['type' => 'file']);
    }

    public function testFileInputClass(): void
    {
        $this->assertHtml([
            'div' => ['class' => 'fieldset'],
            'label' => ['class' => 'fieldset-legend', 'for' => 'photo'],
            'Photo',
            '/label',
            'input' => ['type' => 'file', 'name' => 'photo', 'class' => 'file-input', 'id' => 'photo'],
            '/div',
        ], $this->form->control('photo', ['type' => 'file']));
    }

    public function testModifiers(): void
    {
        $this->assertStringContainsString(
            'class="file-input file-input-primary file-input-sm file-input-ghost"',
            $this->form->control('photo', ['type' => 'file', 'color' => 'primary', 'size' => 'sm', 'appearance' => 'ghost']),
        );
    }

    public function testErrorColor(): void
    {
        $this->assertStringContainsString('class="file-input file-input-error"', $this->form->control('bad', ['type' => 'file']));
    }

    public function testAcceptPassesThrough(): void
    {
        $this->assertStringContainsString('accept="image/*"', $this->form->control('photo', ['type' => 'file', 'accept' => 'image/*']));
    }

    public function testUnknownValueThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->form->control('photo', ['type' => 'file', 'color' => 'nope']);
    }
}
