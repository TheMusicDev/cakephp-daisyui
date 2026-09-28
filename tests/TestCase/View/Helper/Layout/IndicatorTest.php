<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Layout;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\LayoutHelper;

class IndicatorTest extends TestCase
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
        $result = $this->helper->indicator('<div>Content</div>', '<span>Badge</span>');

        $this->assertStringContainsString('class="indicator', $result);
        $this->assertStringContainsString('indicator-item', $result);
        $this->assertStringContainsString('<span>Badge</span>', $result);
        $this->assertStringContainsString('<div>Content</div>', $result);
    }

    public function testWithPlacementString(): void
    {
        $result = $this->helper->indicator('<div>Content</div>', '<span>Badge</span>', ['placement' => 'start']);

        $this->assertStringContainsString('indicator-start', $result);
    }

    public function testWithPlacementArray(): void
    {
        $result = $this->helper->indicator('<div>Content</div>', '<span>Badge</span>', ['placement' => ['start', 'top']]);

        $this->assertStringContainsString('indicator-start', $result);
        $this->assertStringContainsString('indicator-top', $result);
    }

    public function testWithItemClass(): void
    {
        $result = $this->helper->indicator('<div>Content</div>', '<span>Badge</span>', ['itemClass' => 'extra']);

        $this->assertStringContainsString('indicator-item extra', $result);
    }

    public function testContentRaw(): void
    {
        $result = $this->helper->indicator('<div><b>Bold</b></div>', '<span>Badge</span>');

        $this->assertStringContainsString('<div><b>Bold</b></div>', $result);
    }

    public function testItemRaw(): void
    {
        $result = $this->helper->indicator('<div>Content</div>', '<span><b>Bold</b></span>');

        $this->assertStringContainsString('<span><b>Bold</b></span>', $result);
    }

    public function testClassAppended(): void
    {
        $result = $this->helper->indicator('<div>Content</div>', '<span>Badge</span>', ['class' => 'extra']);

        $this->assertStringContainsString('indicator extra', $result);
    }

    public function testAttributePassthrough(): void
    {
        $result = $this->helper->indicator('<div>Content</div>', '<span>Badge</span>', ['id' => 'my-indicator']);

        $this->assertStringContainsString('id="my-indicator"', $result);
    }

    public function testUnknownPlacementThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);

        $this->helper->indicator('<div>Content</div>', '<span>Badge</span>', ['placement' => 'nope']);
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['indicator.base' => 'indicator custom']);
        ClassMap::reset();

        $result = $this->helper->indicator('<div>Content</div>', '<span>Badge</span>');

        $this->assertStringContainsString('class="indicator custom', $result);
    }
}
