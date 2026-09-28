<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class StatTest extends TestCase
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
        $this->assertSame(
            '<div class="stats"><div class="stat"><div class="stat-title">Title</div><div class="stat-value">100</div></div></div>',
            $this->helper->stats([['title' => 'Title', 'value' => '100']]),
        );
    }

    public function testAllParts(): void
    {
        $html = $this->helper->stats([
            [
                'figure' => '<svg>icon</svg>',
                'title' => 'Title',
                'value' => 'Value',
                'desc' => 'Description',
                'actions' => '<button>Action</button>',
            ],
        ]);
        $this->assertStringContainsString('<svg>icon</svg>', $html);
        $this->assertStringContainsString('stat-title">Title', $html);
        $this->assertStringContainsString('stat-value">Value', $html);
        $this->assertStringContainsString('stat-desc">Description', $html);
        $this->assertStringContainsString('<button>Action</button>', $html);
    }

    public function testDirectionHorizontal(): void
    {
        $html = $this->helper->stats([['title' => 'Title']], ['direction' => 'horizontal']);
        $this->assertStringContainsString('stats-horizontal', $html);
    }

    public function testDirectionVertical(): void
    {
        $html = $this->helper->stats([['title' => 'Title']], ['direction' => 'vertical']);
        $this->assertStringContainsString('stats-vertical', $html);
    }

    public function testItemClassAppended(): void
    {
        $html = $this->helper->stats([
            ['title' => 'Title', 'class' => 'extra-class'],
        ]);
        $this->assertStringContainsString('stat extra-class', $html);
    }

    public function testItemClassArray(): void
    {
        $html = $this->helper->stats([
            ['title' => 'Title', 'class' => ['extra', 'classes']],
        ]);
        $this->assertStringContainsString('stat extra classes', $html);
    }

    public function testComponentClassAppended(): void
    {
        $html = $this->helper->stats([['title' => 'Title']], ['class' => 'extra-class']);
        $this->assertStringContainsString('stats extra-class', $html);
    }

    public function testAttributePassthrough(): void
    {
        $html = $this->helper->stats([['title' => 'Title']], ['data-foo' => 'bar', 'id' => 'stats-1']);
        $this->assertStringContainsString('data-foo="bar"', $html);
        $this->assertStringContainsString('id="stats-1"', $html);
    }

    public function testTitleEscaped(): void
    {
        $this->assertSame(
            '<div class="stats"><div class="stat"><div class="stat-title">&lt;b&gt;</div></div></div>',
            $this->helper->stats([['title' => '<b>']]),
        );
    }

    public function testValueEscaped(): void
    {
        $this->assertSame(
            '<div class="stats"><div class="stat"><div class="stat-value">&lt;script&gt;</div></div></div>',
            $this->helper->stats([['value' => '<script>']]),
        );
    }

    public function testDescEscaped(): void
    {
        $this->assertSame(
            '<div class="stats"><div class="stat"><div class="stat-desc">&lt;i&gt;</div></div></div>',
            $this->helper->stats([['desc' => '<i>']]),
        );
    }

    public function testFigureNotEscaped(): void
    {
        $html = $this->helper->stats([['figure' => '<span>icon</span>']]);
        $this->assertStringContainsString('<span>icon</span>', $html);
    }

    public function testActionsNotEscaped(): void
    {
        $html = $this->helper->stats([['actions' => '<button>Click</button>']]);
        $this->assertStringContainsString('<button>Click</button>', $html);
    }

    public function testUnknownDirectionThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->stats([['title' => 'Title']], ['direction' => 'nope']);
    }
}
