<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use RuntimeException;
use TheMusicDev\DaisyUi\View\ClassMap;

class ClassMapTest extends TestCase
{
    private string $written = '';

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
    }

    protected function tearDown(): void
    {
        if ($this->written !== '' && file_exists($this->written)) {
            unlink($this->written);
        }
        $this->written = '';

        parent::tearDown();
    }

    public function testAppMapShadowsPluginMapOfSameName(): void
    {
        $path = CONFIG . 'class_maps/daisyui.php';
        file_put_contents($path, "<?php\nreturn ['button.base' => 'app-btn'];\n");
        $this->written = $path;
        ClassMap::reset();

        $this->assertSame('app-btn', ClassMap::get('button.base'));
    }

    public function testFallsBackToPluginMap(): void
    {
        Configure::delete('DaisyUi.classMap');
        ClassMap::reset();

        $this->assertSame('btn', ClassMap::get('button.base'));
    }

    public function testOverridesApplyOnTopOfTheChosenMap(): void
    {
        Configure::write('DaisyUi.classMap', 'custom');
        Configure::write('DaisyUi.classMapOverrides', ['badge.color.primary' => 'badge-primary custom']);
        ClassMap::reset();

        $this->assertSame('badge-primary custom', ClassMap::get('badge.color.primary'));
    }

    public function testSwappedMapReplacesTheDefault(): void
    {
        Configure::write('DaisyUi.classMap', 'custom');
        ClassMap::reset();

        $this->assertSame('custom-body', ClassMap::get('card.part.body'));
    }

    public function testClassesJoinsNonEmptyResults(): void
    {
        Configure::write('DaisyUi.classMap', 'custom');
        ClassMap::reset();

        $this->assertSame('badge badge-sm', ClassMap::classes('badge.base', 'badge.part.icon', 'badge.size.sm'));
    }

    public function testUnknownMapNameThrows(): void
    {
        Configure::write('DaisyUi.classMap', 'does-not-exist');
        ClassMap::reset();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('not found');
        ClassMap::get('badge.base');
    }

    public function testHas(): void
    {
        $this->assertTrue(ClassMap::has('button.base'));
        $this->assertFalse(ClassMap::has('nope.base'));
    }
}
