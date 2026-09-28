<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Actions;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\Helper\ActionsHelper;

class SwapTest extends TestCase
{
    private ActionsHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        Configure::delete('DaisyUi');
        $this->helper = new ActionsHelper(new View());
    }

    public function testDefaultRender(): void
    {
        $html = $this->helper->swap('<svg>on</svg>', '<svg>off</svg>');

        $this->assertStringContainsString('<label class="swap">', $html);
        $this->assertStringContainsString('<input type="checkbox" aria-label="Toggle"', $html);
        $this->assertStringContainsString('<div class="swap-on"><svg>on</svg></div>', $html);
        $this->assertStringContainsString('<div class="swap-off"><svg>off</svg></div>', $html);
        $this->assertStringContainsString('</label>', $html);
    }

    public function testCheckedAttribute(): void
    {
        $html = $this->helper->swap('On', 'Off', ['checked' => true]);

        $this->assertStringContainsString('<input type="checkbox" aria-label="Toggle" checked="checked"', $html);
    }

    public function testCustomLabel(): void
    {
        $html = $this->helper->swap('On', 'Off', ['label' => 'Click me']);

        $this->assertStringContainsString('aria-label="Click me"', $html);
    }

    public function testLabelWithAmpersandEscapedOnce(): void
    {
        $html = $this->helper->swap('On', 'Off', ['label' => 'Save & Exit']);

        $this->assertStringContainsString('aria-label="Save &amp; Exit"', $html);
        // Ensure not double-escaped
        $this->assertStringNotContainsString('&amp;amp;', $html);
    }

    public function testIndeterminateDiv(): void
    {
        $html = $this->helper->swap('On', 'Off', ['indeterminate' => '<span>Maybe</span>']);

        $this->assertStringContainsString('<div class="swap-indeterminate"><span>Maybe</span></div>', $html);
    }

    public function testAppearanceRotate(): void
    {
        $html = $this->helper->swap('On', 'Off', ['appearance' => 'rotate']);

        $this->assertStringContainsString('class="swap swap-rotate"', $html);
    }

    public function testAppearanceFlip(): void
    {
        $html = $this->helper->swap('On', 'Off', ['appearance' => 'flip']);

        $this->assertStringContainsString('class="swap swap-flip"', $html);
    }

    public function testModifierActive(): void
    {
        $html = $this->helper->swap('On', 'Off', ['modifier' => 'active']);

        $this->assertStringContainsString('class="swap swap-active"', $html);
    }

    public function testClassAppended(): void
    {
        $html = $this->helper->swap('On', 'Off', ['class' => 'my-swap']);

        $this->assertStringContainsString('class="swap my-swap"', $html);
    }

    public function testAttributePassthrough(): void
    {
        $html = $this->helper->swap('On', 'Off', ['id' => 'toggle-1', 'data-test' => 'value']);

        $this->assertStringContainsString('id="toggle-1"', $html);
        $this->assertStringContainsString('data-test="value"', $html);
    }

    public function testUnknownAppearanceThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->swap('On', 'Off', ['appearance' => 'nope']);
    }

    public function testUnknownModifierThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->swap('On', 'Off', ['modifier' => 'nope']);
    }

    public function testRawHtmlPassthrough(): void
    {
        $html = $this->helper->swap('<b>bold on</b>', '<i>italic off</i>');

        $this->assertStringContainsString('<div class="swap-on"><b>bold on</b></div>', $html);
        $this->assertStringContainsString('<div class="swap-off"><i>italic off</i></div>', $html);
    }
}
