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
 * Phase 25: rating widget (`type => rating`).
 */
class RatingTest extends TestCase
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
            'schema' => ['stars' => ['type' => 'integer']],
            'defaults' => ['stars' => 2],
            'errors' => ['bad' => ['Rate it']],
        ]);
    }

    public function testRatingIsFieldsetWithStarRadios(): void
    {
        $html = $this->form->control('stars', ['type' => 'rating', 'max' => 3]);

        $this->assertStringStartsWith(
            '<fieldset class="fieldset"><legend class="fieldset-legend">Stars</legend><div class="rating">',
            $html,
        );
        $this->assertSame(3, substr_count($html, 'class="mask mask-star-2"'));
        $this->assertStringContainsString('value="2" class="mask mask-star-2" aria-label="2 stars" id="stars-2" checked="checked"', $html);
        $this->assertStringContainsString('aria-label="1 star"', $html);
        $this->assertStringNotContainsString('<label', $html);
        $this->assertStringEndsWith('</div></fieldset>', $html);
    }

    public function testDefaultMaxIsFive(): void
    {
        $this->assertSame(5, substr_count($this->form->control('stars', ['type' => 'rating']), 'mask-star-2'));
    }

    public function testClearRadioUnlessRequired(): void
    {
        $this->assertStringContainsString(
            'value="" class="rating-hidden" aria-label="Clear rating"',
            $this->form->control('stars', ['type' => 'rating']),
        );
        $this->assertStringNotContainsString(
            'rating-hidden',
            $this->form->control('stars', ['type' => 'rating', 'required' => true]),
        );
    }

    public function testHalfStars(): void
    {
        $html = $this->form->control('stars', ['type' => 'rating', 'max' => 2, 'modifier' => 'half']);

        $this->assertStringContainsString('<div class="rating rating-half">', $html);
        $this->assertStringContainsString('value="0.5" class="mask mask-star-2 mask-half-1"', $html);
        $this->assertStringContainsString('value="1" class="mask mask-star-2 mask-half-2"', $html);
        $this->assertSame(4, substr_count($html, 'mask-star-2'));
    }

    public function testSizeAndClassGoOnTheRatingDiv(): void
    {
        $this->assertStringContainsString(
            '<div class="rating rating-sm gap-1">',
            $this->form->control('stars', ['type' => 'rating', 'size' => 'sm', 'class' => 'gap-1']),
        );
    }

    public function testErrorMessageAndAria(): void
    {
        $html = $this->form->control('bad', ['type' => 'rating', 'max' => 1]);

        $this->assertStringContainsString('aria-invalid="true"', $html);
        $this->assertStringContainsString('<p class="label text-error" id="bad-error">Rate it</p></fieldset>', $html);
    }

    public function testLegendTextAndHelp(): void
    {
        $html = $this->form->control('stars', ['type' => 'rating', 'label' => 'Your <b>rating</b>', 'help' => 'Be honest']);

        $this->assertStringContainsString('<legend class="fieldset-legend">Your &lt;b&gt;rating&lt;/b&gt;</legend>', $html);
        $this->assertStringContainsString('<p class="label" id="stars-help">Be honest</p></fieldset>', $html);
    }

    public function testUnknownValueThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->form->control('stars', ['type' => 'rating', 'size' => 'nope']);
    }
}
