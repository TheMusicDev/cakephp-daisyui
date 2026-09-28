<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use OutOfBoundsException;
use RuntimeException;
use TheMusicDev\DaisyUi\View\ClassMap;

class ClassMapTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
    }

    /**
     * Writes an app-side class map into CONFIG and returns its path.
     *
     * @param array<string, string> $map Map contents.
     * @param string $name Map file name (without .php).
     * @return string
     */
    private function writeAppMap(array $map, string $name = 'custom'): string
    {
        $dir = CONFIG . 'class_maps/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $path = $dir . $name . '.php';
        file_put_contents($path, "<?php\nreturn " . var_export($map, true) . ";\n");

        return $path;
    }

    public function testAppMapTakesPriority(): void
    {
        $name = 'app-' . uniqid();
        $this->writeAppMap(['button.base' => 'btn'], $name);
        Configure::write('DaisyUi.classMap', $name);
        ClassMap::reset();

        $this->assertSame('btn', ClassMap::get('button.base'));
    }

    public function testMissingKeyThrows(): void
    {
        Configure::delete('DaisyUi.classMap');
        ClassMap::reset();

        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Unknown DaisyUi class map key');
        ClassMap::get('badge.color.primary');
    }

    public function testOverridesApplyOnTopOfTheChosenMap(): void
    {
        $name = 'override-' . uniqid();
        $this->writeAppMap(['badge.color.primary' => 'badge-primary'], $name);
        Configure::write('DaisyUi.classMap', $name);
        Configure::write('DaisyUi.classMapOverrides', ['badge.color.primary' => 'badge-primary custom']);
        ClassMap::reset();

        $this->assertSame('badge-primary custom', ClassMap::get('badge.color.primary'));
    }

    public function testSwappedMapReplacesTheDefault(): void
    {
        $name = 'swapped-' . uniqid();
        $this->writeAppMap(['card.part.body' => 'custom-body'], $name);
        Configure::write('DaisyUi.classMap', $name);
        ClassMap::reset();

        $this->assertSame('custom-body', ClassMap::get('card.part.body'));
    }

    public function testClassesJoinsNonEmptyResults(): void
    {
        $name = 'classes-' . uniqid();
        $this->writeAppMap(['badge.base' => 'badge', 'badge.size.sm' => 'badge-sm', 'badge.part.icon' => ''], $name);
        Configure::write('DaisyUi.classMap', $name);
        ClassMap::reset();

        $this->assertSame('badge badge-sm', ClassMap::classes('badge.base', 'badge.part.icon', 'badge.size.sm'));
    }

    public function testUnknownMapNameThrows(): void
    {
        Configure::write('DaisyUi.classMap', 'does-not-exist-' . uniqid());
        ClassMap::reset();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('not found');
        ClassMap::get('badge.base');
    }
}
