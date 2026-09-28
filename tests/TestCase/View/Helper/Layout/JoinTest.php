<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Layout;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\LayoutHelper;

class JoinTest extends TestCase
{
    private LayoutHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new LayoutHelper(new View());
    }

    public function testDefault(): void
    {
        $this->assertSame(
            '<div class="join"><button>A</button></div>',
            $this->helper->join('<button>A</button>'),
        );
    }

    public function testDirectionVertical(): void
    {
        $this->assertSame(
            '<div class="join join-vertical"><button>A</button></div>',
            $this->helper->join('<button>A</button>', ['direction' => 'vertical']),
        );
    }

    public function testDirectionHorizontal(): void
    {
        $this->assertSame(
            '<div class="join join-horizontal"><button>A</button></div>',
            $this->helper->join('<button>A</button>', ['direction' => 'horizontal']),
        );
    }

    public function testUnknownDirectionThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->join('<button>A</button>', ['direction' => 'nope']);
    }

    public function testClassAppended(): void
    {
        $this->assertSame(
            '<div class="join extra"><button>A</button></div>',
            $this->helper->join('<button>A</button>', ['class' => 'extra']),
        );
    }

    public function testAttributePassthrough(): void
    {
        $this->assertSame(
            '<div class="join" id="j1" data-test="value"><button>A</button></div>',
            $this->helper->join('<button>A</button>', ['id' => 'j1', 'data-test' => 'value']),
        );
    }

    public function testJoinItemClass(): void
    {
        $this->assertSame('join-item', $this->helper->joinItemClass());
    }

    public function testJoinItemClassOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['joinItem.base' => 'join-item custom']);
        ClassMap::reset();

        $this->assertSame('join-item custom', $this->helper->joinItemClass());
    }
}
