<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Assets;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use InvalidArgumentException;
use TheMusicDev\DaisyUi\View\Helper\AssetsHelper;

class CssTest extends TestCase
{
    private AssetsHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        Configure::delete('DaisyUi');
        $this->helper = new AssetsHelper(new View());
    }

    public function testEmitsPinnedCdnTags(): void
    {
        $html = $this->helper->css();

        $this->assertStringContainsString(
            '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daisyui@5.7.46/daisyui.css"',
            $html,
        );
        $this->assertStringContainsString('integrity="sha384-bbGkD3MAh/9AO9eBt/6ReKyGTu78VjNCrlo1uLqxHFOrFr8lRuS4H0sC04ucGill"', $html);
        $this->assertStringContainsString('crossorigin="anonymous"', $html);
        $this->assertStringContainsString(
            '<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4.3.3"',
            $html,
        );
        $this->assertStringContainsString('integrity="sha384-aJ9rL4k6lF+91guGvUFVSkpIcge7Zd9EiI4TQDLoK9kFaFJgKHgjEXVvG/qA5COj"', $html);
    }

    public function testCdnFalseDisablesTags(): void
    {
        Configure::write('DaisyUi.cdn', false);

        $html = $this->helper->css();
        $this->assertStringNotContainsString('<link', $html);
        $this->assertStringNotContainsString('<script src', $html);
    }

    public function testCdnFalseAndNoPersistenceEmitsNothing(): void
    {
        Configure::write('DaisyUi.cdn', false);
        Configure::write('DaisyUi.persistTheme', false);

        $this->assertSame('', $this->helper->css());
    }

    public function testOverriddenEntryWithoutIntegrityHasNoIntegrity(): void
    {
        Configure::write('DaisyUi.cdn', [
            'daisyui' => ['url' => 'https://example.com/daisy.css'],
        ]);
        $html = $this->helper->css();

        $this->assertStringContainsString('<link rel="stylesheet" href="https://example.com/daisy.css"', $html);
        $link = (string)strstr($html, '<script', true);
        $this->assertStringNotContainsString('integrity', $link);
        $this->assertStringNotContainsString('crossorigin', $link);
        $this->assertStringContainsString('src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4.3.3"', $html);
        $this->assertStringContainsString('integrity="sha384-aJ9rL4k6lF+91guGvUFVSkpIcge7Zd9EiI4TQDLoK9kFaFJgKHgjEXVvG/qA5COj"', $html);
    }

    public function testOverriddenEntryWithIntegrityUsesIt(): void
    {
        Configure::write('DaisyUi.cdn', [
            'tailwind' => [
                'url' => 'https://example.com/tw.js',
                'integrity' => 'sha384-test',
            ],
        ]);
        $html = $this->helper->css();

        $this->assertStringContainsString('src="https://example.com/tw.js"', $html);
        $this->assertStringContainsString('integrity="sha384-test"', $html);
    }

    public function testEntryWithoutUrlThrows(): void
    {
        Configure::write('DaisyUi.cdn', ['daisyui' => ['integrity' => 'sha384-test']]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('DaisyUi.cdn.daisyui needs a non-empty \'url\'');
        $this->helper->css();
    }
}
