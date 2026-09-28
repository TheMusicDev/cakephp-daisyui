<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Mockup;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\MockupHelper;

class WindowTest extends TestCase
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
        $html = $this->helper->window('<p>Content</p>');
        $this->assertStringContainsString('class="mockup-window"', $html);
        $this->assertStringContainsString('<p>Content</p>', $html);
    }
}
