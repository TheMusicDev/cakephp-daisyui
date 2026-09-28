<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Form;

use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\FormHelper;

/**
 * Regressions from the M3 review.
 */
class ReviewFixesTest extends TestCase
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

    /**
     * @return array<string, array{array<string, mixed>}>
     */
    public static function featureOptions(): array
    {
        return [
            'plain' => [[]],
            'floating' => [['floating' => true]],
            'input group' => [['prepend' => '$']],
            'help' => [['help' => 'Help']],
        ];
    }

    /**
     * @param array<string, mixed> $options Feature options.
     * @return void
     */
    #[DataProvider('featureOptions')]
    public function testTemplateFileNameWorksWithEveryFeature(array $options): void
    {
        $html = $this->form->control('name', ['templates' => 'test_form_templates'] + $options);

        $this->assertStringStartsWith('<section class="from-file">', $html);
    }

    public function testTemplateFileNameWorksWithGroupsAndHorizontal(): void
    {
        $this->form->create(null, ['align' => 'horizontal']);

        $this->assertStringContainsString(
            'class="input"',
            $this->form->control('name', ['templates' => 'test_form_templates']),
        );
        $this->assertStringContainsString(
            'class="radio"',
            $this->form->control('size', ['type' => 'radio', 'options' => ['s' => 'S'], 'templates' => 'test_form_templates']),
        );
    }

    public function testHelperConfigTemplateFileWinsOverDefaults(): void
    {
        $form = new FormHelper(new View(), ['templates' => 'test_form_templates']);
        $form->create(null);

        $this->assertStringStartsWith('<section class="from-file">', $form->control('name'));
    }

    public function testRatingRejectsValidator(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('not "rating"');
        $this->form->control('score', ['type' => 'rating', 'validator' => true, 'hint' => 'x']);
    }

    public function testRatingDropsStrayHint(): void
    {
        $this->assertStringNotContainsString('hint=', $this->form->control('score', ['type' => 'rating', 'hint' => 'x']));
    }

    public function testYearIsASelect(): void
    {
        $this->assertMatchesRegularExpression('/<select name="born" class="select"/', $this->form->control('born', ['type' => 'year']));
    }

    public function testOtpJoinsTheHorizontalGrid(): void
    {
        $this->form->create(null, ['align' => 'horizontal']);
        $html = $this->form->control('code', ['type' => 'otp', 'help' => 'From your app']);

        $this->assertStringStartsWith('<div class="fieldset grid-cols-[10rem_1fr] items-center gap-x-4">', $html);
        $this->assertStringContainsString('<p class="label col-start-2" id="code-help">', $html);
    }

    public function testStackedControlsInHorizontalFormHaveNoGridOffset(): void
    {
        $this->form->create(null, ['align' => 'horizontal']);

        foreach (['radio', 'filter', 'rating'] as $type) {
            $html = $this->form->control('f', ['type' => $type, 'options' => ['a' => 'A'], 'help' => 'Help']);
            $this->assertStringNotContainsString('col-start-2', $html, $type);
        }
    }

    public function testCallerTemplatesWinForFilterAndRating(): void
    {
        foreach (['filter', 'rating'] as $type) {
            $html = $this->form->control('f', [
                'type' => $type,
                'options' => ['a' => 'A'],
                'templates' => ['inputContainer' => '<div class="mine">{{content}}</div>'],
            ]);
            $this->assertStringStartsWith('<div class="mine">', $html, $type);
        }
    }
}
