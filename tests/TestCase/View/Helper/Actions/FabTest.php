<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Actions;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use InvalidArgumentException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\ActionsHelper;

class FabTest extends TestCase
{
    private ActionsHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new ActionsHelper(new View());
    }

    public function testDefault(): void
    {
        $html = $this->helper->fab('<svg></svg>', [
            ['icon' => '<svg></svg>', 'label' => 'Action 1'],
        ]);
        $this->assertStringContainsString('class="fab"', $html);
        $this->assertStringContainsString('aria-label="Open actions"', $html);
    }

    public function testModifierFlower(): void
    {
        $html = $this->helper->fab('<svg></svg>', [
            ['icon' => '<svg></svg>', 'label' => 'Action 1'],
        ], ['modifier' => 'flower']);
        $this->assertStringContainsString('fab-flower', $html);
        $this->assertStringContainsString('tooltip', $html);
    }

    public function testActionWithColor(): void
    {
        $html = $this->helper->fab('<svg></svg>', [
            ['icon' => '<svg></svg>', 'label' => 'Delete', 'color' => 'error'],
        ]);
        $this->assertStringContainsString('btn-error', $html);
    }

    public function testMissingLabelThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->helper->fab('<svg></svg>', [
            ['icon' => '<svg></svg>'],
        ]);
    }

    public function testActionButtonsDoNotSubmitForms(): void
    {
        $this->assertStringContainsString(
            '<button type="button"',
            $this->helper->fab('+', [['icon' => 'x', 'label' => 'Add']]),
        );
    }

    public function testFlowerModifierAsList(): void
    {
        $this->assertStringContainsString(
            'data-tip="Add"',
            $this->helper->fab('+', [['icon' => 'x', 'label' => 'Add']], ['modifier' => ['flower']]),
        );
    }
}
