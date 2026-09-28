<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Actions;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\ActionsHelper;

/**
 * Regression: swap/dropdown part classes come from the class map (overridable).
 */
class ClassMapUsageTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        // Don't leak the override map into later tests.
        ClassMap::reset();
    }

    public function testSwapAndDropdownPartsHonourOverrides(): void
    {
        Configure::write('DaisyUi.classMapOverrides', [
            'swap.part.on' => 'swap-on x-on',
            'swap.part.off' => 'swap-off x-off',
            'swap.part.indeterminate' => 'swap-indeterminate x-ind',
            'dropdown.part.content' => 'dropdown-content x-content',
            'button.base' => 'btn x-btn',
        ]);
        ClassMap::reset();
        $helper = new ActionsHelper(new View());

        $swap = $helper->swap('on', 'off', ['indeterminate' => '?']);
        $this->assertStringContainsString('class="swap-on x-on"', $swap);
        $this->assertStringContainsString('class="swap-off x-off"', $swap);
        $this->assertStringContainsString('class="swap-indeterminate x-ind"', $swap);

        $dropdown = $helper->dropdown('Menu', '<ul></ul>');
        $this->assertStringContainsString('<summary class="btn x-btn">', $dropdown);
        $this->assertStringContainsString('class="dropdown-content x-content"', $dropdown);
    }
}
