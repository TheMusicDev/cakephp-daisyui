<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Layout;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\LayoutHelper;

class StackTest extends TestCase
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
        $result = $this->helper->stack('<div>Item 1</div><div>Item 2</div>');

        $this->assertStringContainsString('class="stack', $result);
        $this->assertStringContainsString('<div>Item 1</div>', $result);
        $this->assertStringContainsString('<div>Item 2</div>', $result);
    }

    public function testWithModifier(): void
    {
        $result = $this->helper->stack('<div>Item 1</div>', ['modifier' => 'top']);

        $this->assertStringContainsString('stack-top', $result);
    }

    public function testContentRaw(): void
    {
        $result = $this->helper->stack('<div><b>Bold</b></div>');

        $this->assertStringContainsString('<div><b>Bold</b></div>', $result);
    }

    public function testClassAppended(): void
    {
        $result = $this->helper->stack('<div>Item</div>', ['class' => 'extra']);

        $this->assertStringContainsString('stack extra', $result);
    }

    public function testAttributePassthrough(): void
    {
        $result = $this->helper->stack('<div>Item</div>', ['id' => 'my-stack']);

        $this->assertStringContainsString('id="my-stack"', $result);
    }

    public function testUnknownModifierThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);

        $this->helper->stack('<div>Item</div>', ['modifier' => 'nope']);
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['stack.base' => 'stack custom']);
        ClassMap::reset();

        $result = $this->helper->stack('<div>Item</div>');

        $this->assertStringContainsString('class="stack custom', $result);
    }
}
