<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View;

use Cake\Core\Configure;
use OutOfBoundsException;
use RuntimeException;

/**
 * Resolves class-map keys to daisyUI class strings (spec §5.3).
 */
final class ClassMap
{
    /**
     * @var array<string, string>|null
     */
    private static ?array $map = null;

    /**
     * @param string $key Dotted class-map key, e.g. `badge.color.primary`.
     * @return string
     * @throws \OutOfBoundsException When the key does not exist.
     */
    public static function get(string $key): string
    {
        self::$map ??= self::load();
        if (!array_key_exists($key, self::$map)) {
            throw new OutOfBoundsException(sprintf('Unknown DaisyUi class map key "%s".', $key));
        }

        return self::$map[$key];
    }

    /**
     * @param string $key Dotted class-map key.
     * @return bool Whether the active map defines the key.
     */
    public static function has(string $key): bool
    {
        self::$map ??= self::load();

        return array_key_exists($key, self::$map);
    }

    /**
     * Resolves several keys and joins the non-empty results with spaces.
     *
     * @param string ...$keys Class-map keys.
     * @return string
     */
    public static function classes(string ...$keys): string
    {
        $classes = array_map(self::get(...), $keys);

        return implode(' ', array_filter($classes, fn(string $class): bool => $class !== ''));
    }

    /**
     * Forgets the loaded map (tests, or after changing Configure at runtime).
     *
     * @return void
     */
    public static function reset(): void
    {
        self::$map = null;
    }

    /**
     * App `config/class_maps/{name}.php` first, then the plugin's.
     *
     * @return array<string, string>
     */
    private static function load(): array
    {
        $name = (string)Configure::read('DaisyUi.classMap', 'daisyui');
        $candidates = [
            CONFIG . 'class_maps/' . $name . '.php',
            dirname(__DIR__, 2) . '/config/class_maps/' . $name . '.php',
        ];
        foreach ($candidates as $file) {
            if (is_file($file)) {
                return array_replace(require $file, (array)Configure::read('DaisyUi.classMapOverrides', []));
            }
        }

        throw new RuntimeException(sprintf('DaisyUi class map "%s" not found.', $name));
    }
}
