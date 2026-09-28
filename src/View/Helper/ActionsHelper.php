<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use Cake\View\Helper;

/**
 * daisyUI "Actions" components.
 *
 * @extends \Cake\View\Helper<\Cake\View\View>
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class ActionsHelper extends Helper
{
    use ComponentTrait;

    protected array $helpers = ['Html'];

    /**
     * Button. https://daisyui.com/components/button/
     *
     * By default a `<button class="btn">` with `type="button"` (override
     * with `'type' => 'submit'`, …). With `'url'` set, an `<a>` is rendered
     * via `$this->Html->link()` instead, with no type attribute; the text
     * is handed over pre-escaped with `'escapeTitle' => false`.
     *
     * With `'behavior' => 'disabled'` (or a list containing it), the element
     * gets `tabindex="-1"`, `role="button"` and `aria-disabled="true"`
     * (daisyUI's disable rule); on a `<button>` the `disabled` attribute is
     * also set.
     *
     * @param string $text Plain text, escaped unless `'escape' => false`.
     * @param array<string, mixed> $options `color`, `appearance`, `behavior`,
     *   `size`, `modifier`, `url`, `class`, `escape`; any other key becomes
     *   an HTML attribute.
     * @return string
     */
    public function button(string $text, array $options = []): string
    {
        // `componentClass()` consumes `behavior`, so read it first.
        $behavior = (array)($options['behavior'] ?? []);
        $disabled = in_array('disabled', $behavior, true);
        $class = $this->componentClass('button', $options);
        $text = $this->content($text, $options);

        $attributes = ['class' => $class] + $options;
        if ($disabled) {
            $attributes['tabindex'] = '-1';
            $attributes['role'] = 'button';
            $attributes['aria-disabled'] = 'true';
        }
        if (isset($options['url'])) {
            unset($attributes['url']);

            return $this->Html->link(
                $text,
                $options['url'],
                ['escapeTitle' => false] + $attributes,
            );
        }
        if ($disabled) {
            $attributes['disabled'] = 'disabled';
        }

        return $this->Html->tag(
            'button',
            $text,
            array_merge(['type' => 'button'], $attributes),
        );
    }
}
