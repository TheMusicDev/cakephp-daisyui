<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Layout;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\LayoutHelper;

class DividerTest extends TestCase
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
        ClassMap::reset();
        parent::tearDown();
    }

    public function testDefault(): void
    {
        $result = $this->helper->divider();
        $this->assertHtml(
            ['div' => ['class' => 'divider', 'role' => 'separator'], '/div'],
            $result,
        );
    }

    public function testWithText(): void
    {
        $result = $this->helper->divider('OR');
        $this->assertHtml(
            ['div' => ['class' => 'divider', 'role' => 'separator'], 'OR', '/div'],
            $result,
        );
    }

    public function testWithColor(): void
    {
        $result = $this->helper->divider('', ['color' => 'primary']);
        $this->assertStringContainsString('divider-primary', $result);
    }

    public function testWithDirection(): void
    {
        $result = $this->helper->divider('', ['direction' => 'vertical']);
        $this->assertStringContainsString('divider-vertical', $result);
    }

    public function testWithPlacement(): void
    {
        $result = $this->helper->divider('', ['placement' => 'start']);
        $this->assertStringContainsString('divider-start', $result);
    }

    public function testEscapesByDefault(): void
    {
        $result = $this->helper->divider('<b>');
        $this->assertSame('<div class="divider" role="separator">&lt;b&gt;</div>', $result);
    }

    public function testEscapeFalse(): void
    {
        $result = $this->helper->divider('<b>text</b>', ['escape' => false]);
        $this->assertSame('<div class="divider" role="separator"><b>text</b></div>', $result);
    }

    public function testClassAppended(): void
    {
        $result = $this->helper->divider('', ['class' => 'extra']);
        $this->assertStringContainsString('divider extra', $result);
    }

    public function testAttributePassthrough(): void
    {
        $result = $this->helper->divider('', ['id' => 'my-divider', 'data-test' => 'value']);
        $this->assertStringContainsString('id="my-divider"', $result);
        $this->assertStringContainsString('data-test="value"', $result);
    }

    public function testRoleOverride(): void
    {
        $result = $this->helper->divider('', ['role' => 'presentation']);
        $this->assertStringContainsString('role="presentation"', $result);
        $this->assertStringNotContainsString('role="separator"', $result);
    }

    public function testUnknownColorThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->divider('', ['color' => 'nope']);
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['divider.base' => 'divider custom']);
        ClassMap::reset();

        $result = $this->helper->divider('');
        $this->assertStringContainsString('divider custom', $result);
    }

    public function testAllColors(): void
    {
        $colors = ['neutral', 'primary', 'secondary', 'accent', 'success', 'warning', 'info', 'error'];
        foreach ($colors as $color) {
            $result = $this->helper->divider('', ['color' => $color]);
            $this->assertStringContainsString("divider-$color", $result);
        }
    }
}
