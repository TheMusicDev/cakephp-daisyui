<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Form;

use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use PHPUnit\Framework\Attributes\DataProvider;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\FormHelper;

/**
 * Phase 17: text-like inputs and their daisyUI modifiers.
 */
class InputTest extends TestCase
{
    private FormHelper $form;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        Router::reload();
        Router::setRequest(new ServerRequest());
        $this->form = new FormHelper(new View());
        $this->form->create(['schema' => ['name' => ['type' => 'string']], 'errors' => ['bad' => ['Nope']]]);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function inputTypes(): array
    {
        return array_map(fn(string $t): array => [$t], array_combine(
            $types = ['text', 'email', 'password', 'number', 'tel', 'url', 'search', 'date', 'time', 'datetime-local', 'month', 'week'],
            $types,
        ));
    }

    #[DataProvider('inputTypes')]
    public function testTextLikeTypesGetInputClass(string $type): void
    {
        $this->assertMatchesRegularExpression(
            '/<input type="' . $type . '"[^>]* class="input"/',
            $this->form->control('field', ['type' => $type]),
        );
    }

    public function testColorSizeAppearance(): void
    {
        $this->assertStringContainsString(
            'class="input input-primary input-sm input-ghost"',
            $this->form->control('name', ['color' => 'primary', 'size' => 'sm', 'appearance' => 'ghost']),
        );
    }

    public function testErrorColorReplacesCallerColor(): void
    {
        $html = $this->form->control('bad', ['type' => 'text', 'color' => 'primary']);

        $this->assertStringContainsString('class="input input-error"', $html);
        $this->assertStringNotContainsString('input-primary', $html);
    }

    public function testUnknownValueThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->form->control('name', ['size' => 'nope']);
    }

    public function testAttributesPassThrough(): void
    {
        $this->assertStringContainsString(
            'placeholder="Jane"',
            $this->form->control('name', ['placeholder' => 'Jane']),
        );
    }
}
