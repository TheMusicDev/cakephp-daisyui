<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use TheMusicDev\DaisyUi\View\ClassMap;
use function Cake\Core\h;

/**
 * Shared option handling for component helpers (spec §5.3, §5.6).
 */
trait ComponentTrait
{
    /**
     * Options that map to class-map groups: `{component}.{group}.{value}`.
     */
    private const MODIFIER_OPTIONS = [
        'color', 'size', 'appearance', 'modifier', 'direction', 'placement', 'behavior', 'alignment',
    ];

    /**
     * Builds a component's class string and removes the options it consumed.
     *
     * @param string $component Class-map prefix (the helper method name).
     * @param array<string, mixed> $options Method options; modifier keys and `class` are removed.
     * @return string
     */
    protected function componentClass(string $component, array &$options): string
    {
        $keys = [$component . '.base'];
        foreach (self::MODIFIER_OPTIONS as $group) {
            foreach ((array)($options[$group] ?? []) as $value) {
                $keys[] = $component . '.' . $group . '.' . (string)$value;
            }
            unset($options[$group]);
        }
        $classes = [ClassMap::classes(...$keys), ...(array)($options['class'] ?? [])];
        unset($options['class']);

        return trim(implode(' ', $classes));
    }

    /**
     * Escapes plain text unless `'escape' => false`; removes the `escape` option.
     *
     * @param string $text Plain text.
     * @param array<string, mixed> $options Method options.
     * @return string
     */
    protected function content(string $text, array &$options): string
    {
        $escape = $options['escape'] ?? true;
        unset($options['escape']);

        return $escape ? h($text) : $text;
    }
}
