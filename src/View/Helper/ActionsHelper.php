<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use Cake\Utility\Inflector;
use Cake\View\Helper;
use InvalidArgumentException;
use TheMusicDev\DaisyUi\View\ClassMap;
use function Cake\Core\h;
use function Cake\I18n\__;

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
     * Counter for unique theme-controller radio group names.
     */
    private int $themeControllerCount = 0;

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
        if (isset($options['url']) && $disabled) {
            // aria-disabled alone doesn't stop a link: render it without the href.
            unset($attributes['url']);

            return $this->tag('a', $text, $attributes);
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

        return $this->tag(
            'button',
            $text,
            array_merge(['type' => 'button'], $attributes),
        );
    }

    /**
     * Swap. https://daisyui.com/components/swap/
     *
     * `$on` and `$off` are raw HTML (usually icons or other helpers' output);
     * callers must escape any user data in them. The checkbox's aria-label defaults
     * to `__('Toggle')` and is escaped; it is overridable via `'label'`.
     *
     * @param string $on Content when active; raw HTML.
     * @param string $off Content when inactive; raw HTML.
     * @param array<string, mixed> $options `label` (string, checkbox aria-label, default `__('Toggle')`),
     *   `checked` (bool), `indeterminate` (string, raw HTML → `<div class="swap-indeterminate">`),
     *   `appearance` (rotate, flip), `modifier` (active), `class`; any other key becomes an HTML attribute on the label.
     * @return string
     */
    public function swap(string $on, string $off, array $options = []): string
    {
        $label = $options['label'] ?? __('Toggle');
        $checked = $options['checked'] ?? false;
        $indeterminate = $options['indeterminate'] ?? null;
        unset($options['label'], $options['checked'], $options['indeterminate']);

        $class = $this->componentClass('swap', $options);

        $inputAttrs = [
            'type' => 'checkbox',
            'aria-label' => $label,
        ];
        if ($checked) {
            $inputAttrs['checked'] = 'checked';
        }
        $inner = $this->tag('input', '', $inputAttrs);
        $inner .= $this->tag('div', $on, ['class' => ClassMap::get('swap.part.on')]);
        $inner .= $this->tag('div', $off, ['class' => ClassMap::get('swap.part.off')]);
        if ($indeterminate !== null) {
            $inner .= $this->tag('div', $indeterminate, ['class' => ClassMap::get('swap.part.indeterminate')]);
        }

        return $this->tag('label', $inner, ['class' => $class] + $options);
    }

    /**
     * Dropdown. https://daisyui.com/components/dropdown/
     *
     * Uses native `<details>`/`<summary>` for open/close (no JavaScript).
     * `$button` is plain text, escaped unless `'escape' => false`.
     * `$content` is raw HTML (e.g. a `<ul class="menu">` list or other helpers' output);
     * callers must escape any user data in it.
     *
     * @param string $button Button text; plain text escaped unless `'escape' => false`.
     * @param string $content Dropdown content; raw HTML.
     * @param array<string, mixed> $options `buttonClass` (string|array, default the class-map `button.base`),
     *   `open` (bool), `placement` (start, center, end, top, bottom, left, right; string or array),
     *   `modifier` (hover, open, close), `class`, `escape`; any other key becomes an HTML attribute on details.
     * @return string
     */
    public function dropdown(string $button, string $content, array $options = []): string
    {
        $buttonClass = $options['buttonClass'] ?? ClassMap::get('button.base');
        $open = $options['open'] ?? false;
        $escape = $options['escape'] ?? true;
        unset($options['buttonClass'], $options['open'], $options['escape']);

        $buttonClassStr = is_array($buttonClass) ? implode(' ', $buttonClass) : $buttonClass;

        $class = $this->componentClass('dropdown', $options);

        $buttonText = $escape ? h($button) : $button;

        $summary = $this->tag('summary', $buttonText, ['class' => $buttonClassStr]);
        $contentDiv = $this->tag('div', $content, ['class' => ClassMap::get('dropdown.part.content')]);

        $detailsAttrs = ['class' => $class] + $options;
        if ($open) {
            $detailsAttrs['open'] = 'open';
        }

        return $this->tag('details', $summary . $contentDiv, $detailsAttrs);
    }

    /**
     * Modal. https://daisyui.com/components/modal/
     *
     * A native `<dialog>` (Esc closes it); open it with `modalTrigger()` using
     * the same `$id`. Markup: `modal-box` with an optional `<h3>` title (escaped,
     * linked via `aria-labelledby`), `$content` (raw HTML), and a `modal-action`
     * row holding `actions` (raw HTML) plus a close button in `<form method="dialog">`.
     * `actions` sits outside that form, so it may contain forms of its own (e.g. `postLink()`).
     *
     * @param string $id Unique element id: letters, digits, `_` and `-`, starting with a letter.
     * @param string $content Raw HTML body; escape user data yourself.
     * @param array<string, mixed> $options `title` (escaped), `actions` (raw HTML),
     *   `close` (close-button text, escaped; default 'Close'; false = no close button),
     *   `backdropClose` (bool: clicking outside closes), `placement` (top, middle,
     *   bottom, start, end), `modifier` (open), `class`; other keys become `<dialog>` attributes.
     * @return string
     * @throws \InvalidArgumentException On an invalid id.
     */
    public function modal(string $id, string $content, array $options = []): string
    {
        $this->assertModalId($id);
        $title = $options['title'] ?? null;
        $actions = (string)($options['actions'] ?? '');
        $close = $options['close'] ?? __('Close');
        $backdropClose = !empty($options['backdropClose']);
        unset($options['title'], $options['actions'], $options['close'], $options['backdropClose']);
        $class = $this->componentClass('modal', $options);

        $attributes = ['id' => $id, 'class' => $class];
        $box = '';
        if ($title !== null && $title !== '') {
            $box .= $this->tag('h3', h((string)$title), ['id' => $id . '-title']);
            $attributes['aria-labelledby'] = $id . '-title';
        }
        $box .= $content;

        if ($close !== false) {
            $actions .= $this->tag(
                'form',
                $this->tag('button', h((string)$close), ['type' => 'submit', 'class' => ClassMap::get('button.base')]),
                ['method' => 'dialog'],
            );
        }
        if ($actions !== '') {
            $box .= $this->tag('div', $actions, ['class' => ClassMap::get('modal.part.action')]);
        }

        $html = $this->tag('div', $box, ['class' => ClassMap::get('modal.part.box')]);
        if ($backdropClose) {
            $html .= $this->tag(
                'form',
                $this->tag('button', h(__('Close')), ['type' => 'submit']),
                ['method' => 'dialog', 'class' => ClassMap::get('modal.part.backdrop')],
            );
        }

        return $this->tag('dialog', $html, $attributes + $options);
    }

    /**
     * A button that opens the `modal()` with the same id:
     * `onclick="document.getElementById('{id}').showModal()"` (spec §5.8).
     * Takes every `button()` option. Inline `onclick` needs `unsafe-inline`
     * (or a nonce) under a strict Content-Security-Policy.
     *
     * @param string $text Button text, escaped unless `'escape' => false`.
     * @param string $id The modal's id.
     * @param array<string, mixed> $options `button()` options.
     * @return string
     * @throws \InvalidArgumentException On an invalid id.
     */
    public function modalTrigger(string $text, string $id, array $options = []): string
    {
        $this->assertModalId($id);
        unset($options['url']);

        return $this->button($text, [
            'onclick' => "document.getElementById('" . $id . "').showModal()",
        ] + $options + ['aria-haspopup' => 'dialog']);
    }

    /**
     * The id goes into inline JavaScript, so only a safe subset is allowed.
     *
     * @param string $id Modal id.
     * @return void
     * @throws \InvalidArgumentException When the id is not `^[A-Za-z][\w-]*$`.
     */
    private function assertModalId(string $id): void
    {
        if (!preg_match('/^[A-Za-z][\w-]*$/', $id)) {
            throw new InvalidArgumentException(sprintf(
                'Modal id "%s" is invalid: use letters, digits, "_" and "-", starting with a letter.',
                $id,
            ));
        }
    }

    /**
     * Theme controller. https://daisyui.com/components/theme-controller/
     *
     * Two themes → a toggle checkbox (unchecked = first, checked = second);
     * three or more → a dropdown of radio buttons. Themes default to
     * `DaisyUi.themes`. `AssetsHelper::css()` emits the script that applies the
     * saved theme before first paint and keeps controllers in sync (spec §5.9).
     *
     * @param array<string>|null $themes Theme names, or null for `DaisyUi.themes`.
     * @param array<string, mixed> $options `label` (escaped; the toggle's aria-label or
     *   the dropdown button text, default 'Theme'), `toggle` (bool, default true: two-theme
     *   control styled as a toggle), `class` (on the checkbox for two themes, on the
     *   dropdown `<details>` for 3+), `menuClass` (3+ themes: classes of the panel list,
     *   default class-map `themeController.part.menu`); for 3+ themes any `dropdown()` option.
     * @return string
     * @throws \InvalidArgumentException With fewer than two themes or an invalid name.
     */
    public function themeController(?array $themes = null, array $options = []): string
    {
        $themes = AssetsHelper::themes($themes);
        if (count($themes) < 2) {
            throw new InvalidArgumentException('A theme controller needs at least two themes.');
        }
        $label = (string)($options['label'] ?? __('Theme'));
        $toggle = $options['toggle'] ?? true;
        unset($options['label'], $options['toggle']);

        if (count($themes) === 2) {
            $class = [ClassMap::get('themeController.base')];
            if ($toggle) {
                $class[] = ClassMap::get('toggle.base');
            }
            $class = [...$class, ...(array)($options['class'] ?? [])];
            unset($options['class']);

            return $this->tag('input', '', [
                'type' => 'checkbox',
                'value' => $themes[1],
                'class' => implode(' ', $class),
                'aria-label' => $label,
                'data-theme-controller' => true,
            ] + $options);
        }

        $radioClass = ClassMap::get('themeController.base') . ' ' . ClassMap::classes(
            'button.base',
            'button.appearance.ghost',
            'button.size.sm',
            'button.modifier.block',
        );
        // One radio group per controller: identical names would make controllers uncheck each other.
        $group = 'theme-controller-' . ++$this->themeControllerCount;
        $menuClass = $options['menuClass'] ?? ClassMap::get('themeController.part.menu');
        unset($options['menuClass']);
        $items = '';
        foreach ($themes as $theme) {
            $items .= $this->tag('li', $this->tag('input', '', [
                'type' => 'radio',
                'name' => $group,
                'value' => $theme,
                'class' => $radioClass,
                'aria-label' => Inflector::humanize(str_replace('-', '_', $theme)),
                'data-theme-controller' => true,
            ]));
        }

        return $this->dropdown(
            $label,
            $this->tag('ul', $items, ['class' => $menuClass]),
            $options,
        );
    }

    /**
     * FAB (Floating Action Button). https://daisyui.com/components/fab/
     *
     * Display a floating action button with optional action buttons.
     *
     * @param string $icon Raw HTML icon for the trigger button.
     * @param array<array<string, mixed>> $actions Each action has:
     *   - `icon` (string, raw HTML, required); `label` (string, plain text, required; throws if missing);
     *   - `url` (string, optional); `color` (string, optional).
     * @param array<string, mixed> $options `label` (trigger aria-label, default __('Open actions')), `modifier`
     *   (flower), `class`; any other key becomes an HTML attribute.
     * @return string
     * @throws \InvalidArgumentException if any action lacks a label.
     */
    public function fab(string $icon, array $actions, array $options = []): string
    {
        $label = $options['label'] ?? __('Open actions');
        $modifier = $options['modifier'] ?? null;
        unset($options['label']);

        $class = $this->componentClass('fab', $options);

        $triggerOptions = [
            'size' => 'lg',
            'modifier' => 'circle',
            'color' => 'primary',
        ];
        $triggerClass = $this->componentClass('button', $triggerOptions);

        $trigger = $this->tag('div', $icon, [
            'tabindex' => '0',
            'role' => 'button',
            'class' => $triggerClass,
            'aria-label' => $label,
        ]);

        $actionElements = $trigger;
        foreach ($actions as $action) {
            if (empty($action['label'])) {
                throw new InvalidArgumentException('Every action must have a label');
            }

            $actionLabel = $action['label'];
            $actionIcon = $action['icon'] ?? '';
            $actionUrl = $action['url'] ?? null;
            $actionColor = $action['color'] ?? null;

            $buttonOptions = [
                'size' => 'lg',
                'modifier' => 'circle',
            ];
            if ($actionColor !== null) {
                $buttonOptions['color'] = $actionColor;
            }
            $buttonClass = $this->componentClass('button', $buttonOptions);

            $linkOptions = [
                'class' => $buttonClass,
                'aria-label' => $actionLabel,
                'escapeTitle' => false,
            ];
            $buttonAttrs = ['type' => 'button', 'class' => $buttonClass, 'aria-label' => $actionLabel];

            $button = $actionUrl !== null
                ? $this->Html->link($actionIcon, $actionUrl, $linkOptions)
                : $this->tag('button', $actionIcon, $buttonAttrs);

            if (in_array('flower', (array)$modifier, true)) {
                $tooltipClass = ClassMap::classes('tooltip.base', 'tooltip.placement.left');
                $actionElements .= $this->tag('div', $button, ['class' => $tooltipClass, 'data-tip' => $actionLabel]);
            } else {
                $actionElements .= $this->tag('div', h($actionLabel) . $button);
            }
        }

        return $this->tag('div', $actionElements, ['class' => $class] + $options);
    }
}
