<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use PHPUnit\Framework\Attributes\DataProvider;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;
use TheMusicDev\DaisyUi\View\Helper\FeedbackHelper;
use TheMusicDev\DaisyUi\View\Helper\LayoutHelper;

/**
 * Regression (M1 review): methods without an escapable text parameter must
 * swallow `escape`, or Html->tag() escapes the markup they built.
 */
class EscapeOptionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
    }

    /**
     * @return array<string, array{callable(\Cake\View\View): string, string}>
     */
    public static function calls(): array
    {
        return [
            'avatar' => [fn(View $v) => (new DataDisplayHelper($v))->avatar('a.png', ['escape' => true]), '<img'],
            'avatarGroup' => [fn(View $v) => (new DataDisplayHelper($v))->avatarGroup('<i>x</i>', ['escape' => true]), '<i>x</i>'],
            'status' => [fn(View $v) => (new DataDisplayHelper($v))->status(['escape' => true]), '<span class="status"'],
            'loading' => [fn(View $v) => (new FeedbackHelper($v))->loading(['escape' => true]), '<span class="loading"'],
            'progress' => [fn(View $v) => (new FeedbackHelper($v))->progress(5, ['escape' => true]), '<progress class="progress"'],
            'radialProgress' => [
                fn(View $v) => (new FeedbackHelper($v))->radialProgress(50, ['text' => '<b>', 'escape' => true]),
                '>&lt;b&gt;</div>',
            ],
            'join' => [fn(View $v) => (new LayoutHelper($v))->join('<i>x</i>', ['escape' => true]), '<i>x</i>'],
        ];
    }

    /**
     * @param callable(\Cake\View\View): string $call Helper call.
     * @param string $expected Fragment that must survive unescaped once.
     * @return void
     */
    #[DataProvider('calls')]
    public function testEscapeOptionIsNotPassedToHtmlTag(callable $call, string $expected): void
    {
        $html = $call(new View());

        $this->assertStringContainsString($expected, $html);
        $this->assertStringNotContainsString('escape=', $html);
        $this->assertStringNotContainsString('&amp;lt;', $html);
    }
}
