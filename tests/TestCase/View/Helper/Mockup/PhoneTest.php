<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Mockup;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\MockupHelper;

class PhoneTest extends TestCase
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
        $html = $this->helper->phone('<p>Content</p>');
        $this->assertStringContainsString('class="mockup-phone"', $html);
        $this->assertStringContainsString('mockup-phone-camera', $html);
        $this->assertStringContainsString('mockup-phone-display', $html);
        $this->assertStringContainsString('<p>Content</p>', $html);
    }
}
