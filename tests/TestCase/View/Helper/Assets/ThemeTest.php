<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Assets;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use InvalidArgumentException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\ActionsHelper;
use TheMusicDev\DaisyUi\View\Helper\AssetsHelper;

/**
 * Phase 41: theme controller, themes.css and the persistence script (spec §5.9).
 */
class ThemeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        Configure::delete('DaisyUi');
    }

    public function testLightDarkNeedsNoThemesCss(): void
    {
        $this->assertStringNotContainsString('themes.css', (new AssetsHelper(new View()))->css());
    }

    public function testOtherThemesLoadThemesCss(): void
    {
        Configure::write('DaisyUi.themes', ['light', 'cupcake']);

        $this->assertStringContainsString('daisyui@5.7.46/themes.css', (new AssetsHelper(new View()))->css());
    }

    public function testPersistenceScriptByDefault(): void
    {
        $html = (new AssetsHelper(new View()))->css();

        $this->assertStringContainsString("<script>(function(){\nvar k='daisyui-theme',t=[\"light\",\"dark\"]", $html);
        $this->assertStringContainsString('prefers-color-scheme: dark', $html);
        $this->assertStringContainsString('input[data-theme-controller]', $html);
    }

    public function testPersistenceCanBeTurnedOff(): void
    {
        Configure::write('DaisyUi.persistTheme', false);

        $this->assertStringNotContainsString('<script>', (new AssetsHelper(new View()))->css());
    }

    public function testInvalidThemeNamesThrow(): void
    {
        $this->expectException(InvalidArgumentException::class);
        AssetsHelper::themes(['light', "dark'</script>"]);
    }

    public function testTwoThemesRenderAToggle(): void
    {
        $this->assertSame(
            '<input type="checkbox" value="dark" class="theme-controller toggle" aria-label="Theme" data-theme-controller="1">',
            (new ActionsHelper(new View()))->themeController(),
        );
    }

    public function testToggleOptionsLabelClassAndPlainCheckbox(): void
    {
        $html = (new ActionsHelper(new View()))->themeController(['winter', 'night'], [
            'label' => 'Dark <mode>',
            'toggle' => false,
            'class' => 'ml-2',
        ]);

        $this->assertStringContainsString('value="night" class="theme-controller ml-2" aria-label="Dark &lt;mode&gt;"', $html);
    }

    public function testThreeOrMoreThemesRenderADropdownOfRadios(): void
    {
        $html = (new ActionsHelper(new View()))->themeController(['light', 'dark', 'cupcake'], ['placement' => 'end']);

        $this->assertStringStartsWith('<details class="dropdown dropdown-end"><summary class="btn">Theme</summary>', $html);
        $this->assertSame(3, substr_count($html, 'type="radio" name="theme-controller-1"'));
        $this->assertStringContainsString('value="cupcake" class="theme-controller btn btn-ghost btn-sm btn-block" aria-label="Cupcake"', $html);
        $this->assertStringNotContainsString('</input>', $html);
    }

    public function testConfiguredThemesAreTheDefault(): void
    {
        Configure::write('DaisyUi.themes', ['light', 'dark', 'retro']);

        $this->assertStringContainsString('value="retro"', (new ActionsHelper(new View()))->themeController());
    }

    public function testFewerThanTwoThemesThrow(): void
    {
        $this->expectException(InvalidArgumentException::class);
        (new ActionsHelper(new View()))->themeController(['light']);
    }

    public function testEachDropdownControllerGetsItsOwnRadioGroup(): void
    {
        $helper = new ActionsHelper(new View());
        $first = $helper->themeController(['light', 'dark', 'cupcake']);
        $second = $helper->themeController(['light', 'dark', 'cupcake']);

        $this->assertStringContainsString('name="theme-controller-1"', $first);
        $this->assertStringContainsString('name="theme-controller-2"', $second);
    }

    public function testMenuClassOption(): void
    {
        $this->assertStringContainsString(
            '<ul class="menu w-40">',
            (new ActionsHelper(new View()))->themeController(['a', 'b', 'c'], ['menuClass' => 'menu w-40']),
        );
    }

    public function testScriptResyncsEveryControllerOnChange(): void
    {
        $html = (new AssetsHelper(new View()))->css();

        $this->assertStringContainsString('function sync(){', $html);
        $this->assertSame(3, substr_count($html, 'sync()'));
    }
}
