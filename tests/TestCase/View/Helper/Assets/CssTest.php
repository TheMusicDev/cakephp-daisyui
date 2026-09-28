<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Assets;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
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

        $this->assertSame('', $this->helper->css());
    }

    public function testUrlsOverridableThroughConfigure(): void
    {
        Configure::write('DaisyUi.cdn', [
            'daisyui' => 'https://example.com/daisy.css',
            'tailwind' => 'https://example.com/tw.js',
        ]);
        $html = $this->helper->css();

        $this->assertStringContainsString('href="https://example.com/daisy.css"', $html);
        $this->assertStringContainsString('src="https://example.com/tw.js"', $html);
    }
}
