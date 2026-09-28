<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Layout;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\LayoutHelper;

class HeroTest extends TestCase
{
    private LayoutHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new LayoutHelper(new View());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        ClassMap::reset();
    }

    public function testDefault(): void
    {
        $result = $this->helper->hero('<p>Content</p>');

        $this->assertStringContainsString('class="hero', $result);
        $this->assertStringContainsString('hero-content', $result);
        $this->assertStringContainsString('<p>Content</p>', $result);
    }

    public function testWithOverlay(): void
    {
        $result = $this->helper->hero('<p>Content</p>', ['overlay' => true]);

        $this->assertStringContainsString('hero-overlay', $result);
    }

    public function testWithContentClass(): void
    {
        $result = $this->helper->hero('<p>Content</p>', ['contentClass' => 'text-center']);

        $this->assertStringContainsString('hero-content text-center', $result);
    }

    public function testWithContentClassArray(): void
    {
        $result = $this->helper->hero('<p>Content</p>', ['contentClass' => ['text-center', 'text-white']]);

        $this->assertStringContainsString('hero-content text-center text-white', $result);
    }

    public function testContentRaw(): void
    {
        $result = $this->helper->hero('<div><b>Bold</b></div>');

        $this->assertStringContainsString('<div><b>Bold</b></div>', $result);
    }

    public function testClassAppended(): void
    {
        $result = $this->helper->hero('<p>Content</p>', ['class' => 'extra']);

        $this->assertStringContainsString('hero extra', $result);
    }

    public function testAttributePassthrough(): void
    {
        $result = $this->helper->hero('<p>Content</p>', ['id' => 'my-hero']);

        $this->assertStringContainsString('id="my-hero"', $result);
    }

    public function testStylePassthrough(): void
    {
        $result = $this->helper->hero('<p>Content</p>', ['style' => 'background-color: red']);

        $this->assertStringContainsString('style="background-color: red"', $result);
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['hero.base' => 'hero custom']);
        ClassMap::reset();

        $result = $this->helper->hero('<p>Content</p>');

        $this->assertStringContainsString('class="hero custom', $result);
    }
}
