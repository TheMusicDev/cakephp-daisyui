<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Navigation;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\NavigationHelper;

class NavbarTest extends TestCase
{
    private NavigationHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new NavigationHelper(new View());
    }

    protected function tearDown(): void
    {
        ClassMap::reset();
        parent::tearDown();
    }

    public function testDefault(): void
    {
        $result = $this->helper->navbar([]);
        $this->assertHtml(
            ['div' => ['class' => 'navbar'], '/div'],
            $result,
        );
    }

    public function testWithStart(): void
    {
        $result = $this->helper->navbar(['start' => '<button>Menu</button>']);
        $this->assertStringContainsString('navbar-start', $result);
        $this->assertStringContainsString('<button>Menu</button>', $result);
    }

    public function testWithCenter(): void
    {
        $result = $this->helper->navbar(['center' => '<a href="/">Logo</a>']);
        $this->assertStringContainsString('navbar-center', $result);
        $this->assertStringContainsString('<a href="/">Logo</a>', $result);
    }

    public function testWithEnd(): void
    {
        $result = $this->helper->navbar(['end' => '<a href="/login">Login</a>']);
        $this->assertStringContainsString('navbar-end', $result);
        $this->assertStringContainsString('<a href="/login">Login</a>', $result);
    }

    public function testWithAllSections(): void
    {
        $result = $this->helper->navbar([
            'start' => '<button>Menu</button>',
            'center' => '<a href="/">Logo</a>',
            'end' => '<a href="/login">Login</a>',
        ]);
        $this->assertStringContainsString('navbar-start', $result);
        $this->assertStringContainsString('navbar-center', $result);
        $this->assertStringContainsString('navbar-end', $result);
        $this->assertStringContainsString('<button>Menu</button>', $result);
        $this->assertStringContainsString('<a href="/">Logo</a>', $result);
        $this->assertStringContainsString('<a href="/login">Login</a>', $result);
    }

    public function testClassAppended(): void
    {
        $result = $this->helper->navbar([], ['class' => 'extra']);
        $this->assertStringContainsString('navbar extra', $result);
    }

    public function testAttributePassthrough(): void
    {
        $result = $this->helper->navbar([], ['id' => 'my-navbar', 'data-test' => 'value']);
        $this->assertStringContainsString('id="my-navbar"', $result);
        $this->assertStringContainsString('data-test="value"', $result);
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['navbar.base' => 'navbar custom']);
        ClassMap::reset();

        $result = $this->helper->navbar([]);
        $this->assertStringContainsString('navbar custom', $result);
    }
}
