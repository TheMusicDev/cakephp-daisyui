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
     * HTML elements that never have content or a closing tag.
     */
    private const VOID_ELEMENTS = ['area', 'br', 'col', 'embed', 'hr', 'img', 'input', 'source', 'track', 'wbr'];

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
     * `Html->tag()` for component markup. Always drops `escape` from the
     * attributes: in `Html->tag()` it would escape the (already built) content,
     * and `false` would turn off attribute escaping for the whole tag.
     *
     * @param string $name Tag name.
     * @param string $content Inner HTML (already escaped where needed).
     * @param array<string, mixed> $attributes Attributes.
     * @return string
     */
    protected function tag(string $name, string $content, array $attributes = []): string
    {
        unset($attributes['escape']);
        if (in_array($name, self::VOID_ELEMENTS, true)) {
            // Void elements take no content and no closing tag (`Html->tag()` would add `</input>`).
            return '<' . $name . $this->Html->templater()->formatAttributes($attributes) . '>';
        }

        return $this->Html->tag($name, $content, $attributes);
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
