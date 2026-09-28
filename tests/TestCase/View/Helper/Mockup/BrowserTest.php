<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Mockup;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\MockupHelper;

class BrowserTest extends TestCase
{
    private MockupHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new MockupHelper(new View());
    }

    public function testDefault(): void
    {
        $html = $this->helper->browser('<p>Content</p>');
        $this->assertStringContainsString('class="mockup-browser"', $html);
        $this->assertStringContainsString('<p>Content</p>', $html);
    }

    public function testWithUrl(): void
    {
        $html = $this->helper->browser('<p>Content</p>', ['url' => 'example.com']);
        $this->assertStringContainsString('example.com', $html);
    }

    public function testUrlIsEscaped(): void
    {
        $html = $this->helper->browser('x', ['url' => '<script>alert(1)</script>']);

        $this->assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script>', $html);
    }
}
