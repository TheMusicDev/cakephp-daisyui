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
 * Phase 27: `prepend` / `append` input groups.
 */
class InputGroupTest extends TestCase
{
    private FormHelper $form;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        Router::reload();
        Router::setRequest(new ServerRequest());
        $this->form = new FormHelper(new View());
        $this->form->create(['schema' => ['price' => ['type' => 'decimal']], 'errors' => ['bad' => ['Nope']]]);
    }

    public function testInputClassMovesToWrapperLabel(): void
    {
        $this->assertStringContainsString(
            '<label class="input">$<input type="number" name="price" id="price">USD</label>',
            $this->form->control('price', ['prepend' => '$', 'append' => 'USD']),
        );
    }

    public function testModifiersAndClassGoOnWrapper(): void
    {
        $this->assertStringContainsString(
            '<label class="input input-primary input-sm w-full">',
            $this->form->control('price', ['prepend' => '$', 'color' => 'primary', 'size' => 'sm', 'class' => 'w-full']),
        );
    }

    public function testSidesAreEscapedByDefault(): void
    {
        $this->assertStringContainsString(
            '&lt;b&gt;<input',
            $this->form->control('price', ['prepend' => '<b>']),
        );
    }

    public function testRawMarkupSide(): void
    {
        $this->assertStringContainsString(
            '<label class="input"><svg></svg><input',
            $this->form->control('price', ['prepend' => ['text' => '<svg></svg>', 'escape' => false]]),
        );
    }

    public function testAppendOnly(): void
    {
        $this->assertStringContainsString(
            '<label class="input"><input type="number" name="price" id="price">kg</label>',
            $this->form->control('price', ['append' => 'kg']),
        );
    }

    public function testErrorColorOnWrapperNotInnerInput(): void
    {
        $html = $this->form->control('bad', ['type' => 'text', 'prepend' => '@']);

        $this->assertStringContainsString('<label class="input input-error">@<input', $html);
        $this->assertStringNotContainsString('class="input-error"', $html);
        $this->assertStringContainsString('<p class="label text-error" id="bad-error">Nope</p>', $html);
    }

    public function testLegendLabelStillPointsAtInput(): void
    {
        $this->assertStringContainsString(
            '<label class="fieldset-legend" for="price">Price</label>',
            $this->form->control('price', ['prepend' => '$']),
        );
    }
}
