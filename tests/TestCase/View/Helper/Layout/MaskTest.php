<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Layout;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use InvalidArgumentException;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\LayoutHelper;

class MaskTest extends TestCase
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

    public function testWithAppearance(): void
    {
        $result = $this->helper->mask('image.png', ['appearance' => 'circle']);

        $this->assertStringContainsString('class="mask mask-circle', $result);
        $this->assertStringContainsString('src="/img/image.png"', $result);
    }

    public function testWithModifier(): void
    {
        $result = $this->helper->mask('image.png', ['appearance' => 'hexagon', 'modifier' => 'half-1']);

        $this->assertStringContainsString('mask mask-hexagon mask-half-1', $result);
    }

    public function testWithAlt(): void
    {
        $result = $this->helper->mask('image.png', ['appearance' => 'circle', 'alt' => 'My Image']);

        $this->assertStringContainsString('alt="My Image"', $result);
    }

    public function testDefaultAlt(): void
    {
        $result = $this->helper->mask('image.png', ['appearance' => 'circle']);

        $this->assertStringContainsString('alt=""', $result);
    }

    public function testWithClass(): void
    {
        $result = $this->helper->mask('image.png', ['appearance' => 'circle', 'class' => 'w-24']);

        $this->assertStringContainsString('w-24', $result);
    }

    public function testAttributePassthrough(): void
    {
        $result = $this->helper->mask('image.png', ['appearance' => 'circle', 'id' => 'my-mask']);

        $this->assertStringContainsString('id="my-mask"', $result);
    }

    public function testWithoutShapeThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->helper->mask('image.png', []);
    }

    public function testUnknownAppearanceThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);

        $this->helper->mask('image.png', ['appearance' => 'nope']);
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['mask.base' => 'mask custom']);
        ClassMap::reset();

        $result = $this->helper->mask('image.png', ['appearance' => 'circle']);

        $this->assertStringContainsString('class="mask custom mask-circle', $result);
    }

    public function testEscapeOptionDoesNotDisableAttributeEscaping(): void
    {
        $html = $this->helper->mask('a.png', ['appearance' => 'circle', 'data-x' => '"><b>', 'escape' => false]);

        $this->assertStringContainsString('data-x="&quot;&gt;&lt;b&gt;"', $html);
    }
}
