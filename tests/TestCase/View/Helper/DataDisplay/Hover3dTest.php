<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class Hover3dTest extends TestCase
{
    private DataDisplayHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new DataDisplayHelper(new View());
    }

    public function testDefault(): void
    {
        $html = $this->helper->hover3d('<p>Content</p>');
        $this->assertStringContainsString('class="hover-3d"', $html);
        $this->assertStringContainsString('<p>Content</p>', $html);
        $this->assertStringContainsString('<div></div>', $html);
    }

    public function testWithUrl(): void
    {
        $html = $this->helper->hover3d('<p>Content</p>', ['url' => '/page']);
        $this->assertStringContainsString('<a', $html);
        $this->assertStringContainsString('href="/page"', $html);
    }

    public function testClassAppended(): void
    {
        $html = $this->helper->hover3d('Content', ['class' => 'extra']);
        $this->assertStringContainsString('class="hover-3d extra"', $html);
    }

    public function testLinkModeKeepsAttributesEscaped(): void
    {
        $html = $this->helper->hover3d('<figure></figure>', ['url' => '/x', 'data-x' => '"><b>', 'escape' => false]);

        $this->assertStringContainsString('data-x="&quot;&gt;&lt;b&gt;"', $html);
    }
}
