<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use BadMethodCallException;
use Cake\View\Helper;
use InvalidArgumentException;
use TheMusicDev\DaisyUi\View\ClassMap;
use function Cake\Core\h;

/**
 * daisyUI "Navigation" components.
 *
 * @extends \Cake\View\Helper<\Cake\View\View>
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class NavigationHelper extends Helper
{
    use ComponentTrait;

    protected array $helpers = ['Html'];

    /**
     * Counter for generating unique tab group names.
     */
    private int $tabCounter = 0;

    /**
     * Not implemented: breadcrumbs are owned by the framework's BreadcrumbsHelper.
     *
     * @return never
     * @throws \BadMethodCallException Always; see the message for the owning helper.
     */
    public function breadcrumbs(): never
    {
        throw new BadMethodCallException(
            'Use the Breadcrumbs helper instead: $this->Breadcrumbs->add() / $this->Breadcrumbs->render().',
        );
    }

    /**
     * Not implemented: pagination is owned by the framework's PaginatorHelper.
     *
     * @return never
     * @throws \BadMethodCallException Always; see the message for the owning helper.
     */
    public function pagination(): never
    {
        throw new BadMethodCallException(
            'Use the Paginator helper instead: $this->Paginator->links() and related methods.',
        );
    }

    /**
     * Dock. https://daisyui.com/components/dock/
     *
     * A dock (bottom navigation) with items. Each item can have an icon and optional label.
     * Items with `url` become links; without `url` become buttons.
     * `icon` is raw HTML (raw SVG); `label` is escaped.
     *
     * @param array<string, mixed> $items List of items with `icon` (raw HTML),
     *   `label` (string, optional, escaped), `url` (optional), `active` (bool, optional).
     * @param array<string, mixed> $options `size` (xs–xl), `class`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function dock(array $items, array $options = []): string
    {
        $class = $this->componentClass('dock', $options);

        $content = '';
        foreach ($items as $item) {
            $icon = $item['icon'] ?? null;
            $label = $item['label'] ?? null;
            $url = $item['url'] ?? null;
            $active = isset($item['active']) && $item['active'];

            // Build inner content: icon + optional label
            $inner = '';
            if ($icon !== null) {
                $inner .= $icon;
            }
            if ($label !== null) {
                $inner .= $this->tag('span', h($label), ['class' => ClassMap::get('dock.part.label')]);
            }

            // Build item attributes
            $itemAttrs = [];
            if ($active) {
                $itemAttrs['class'] = ClassMap::get('dockItem.modifier.active');
                $itemAttrs['aria-current'] = 'page';
            }

            // Build the link or button
            if ($url !== null) {
                $content .= $this->Html->link($inner, $url, ['escapeTitle' => false] + $itemAttrs);
            } else {
                $content .= $this->tag('button', $inner, ['type' => 'button'] + $itemAttrs);
            }
        }

        return $this->tag('div', $content, ['class' => $class] + $options);
    }

    /**
     * Link. https://daisyui.com/components/link/
     *
     * Renders an `<a>` element with link styling. `$text` is escaped unless `'escape' => false`.
     *
     * @param string $text Plain text, escaped unless `'escape' => false`.
     * @param array<string, mixed>|string $url URL for the link (string or array).
     * @param array<string, mixed> $options `color` (neutral, primary, secondary, accent, success, info, warning, error),
     *   `appearance` (hover), `class`, `escape`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function link(string $text, array|string $url, array $options = []): string
    {
        $class = $this->componentClass('link', $options);
        $text = $this->content($text, $options);

        return $this->Html->link($text, $url, ['class' => $class, 'escapeTitle' => false] + $options);
    }

    /**
     * Menu. https://daisyui.com/components/menu/
     *
     * Renders `<ul class="menu">` with menu items. Each item can be:
     * - `['title' => 'Section']` → `<li class="menu-title">Section</li>` (escaped).
     * - `['text' => 'Home', 'url' => '/']` → `<li><a href="/">Home</a></li>`.
     * - Without `url` → `<li><button type="button">Home</button></li>`.
     * Item keys: `icon` (raw HTML before text), `active` (bool), `disabled` (bool), `children`
     * (list of items for submenu), `content` (raw HTML, replaces everything else).
     *
     * @param array<string, mixed> $items List of items.
     * @param array<string, mixed> $options `size` (xs, sm, md, lg, xl), `direction` (vertical, horizontal),
     *   `modifier` (paged), `class`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function menu(array $items, array $options = []): string
    {
        $class = $this->componentClass('menu', $options);

        return $this->tag('ul', $this->_renderMenuItems($items), ['class' => $class] + $options);
    }

    /**
     * Render menu items recursively.
     *
     * @param array<string, mixed> $items List of items.
     * @return string
     */
    private function _renderMenuItems(array $items): string
    {
        $html = '';
        foreach ($items as $item) {
            if (is_string($item)) {
                $item = ['text' => $item];
            }

            // Handle raw content
            if (isset($item['content'])) {
                $html .= $this->tag('li', $item['content']);
                continue;
            }

            // Handle title items
            if (isset($item['title']) && !isset($item['text'])) {
                $html .= $this->tag('li', h($item['title']), ['class' => ClassMap::get('menu.part.title')]);
                continue;
            }

            // Handle link/button items
            $text = h($item['text'] ?? '');
            $url = $item['url'] ?? null;
            $icon = $item['icon'] ?? null;
            $active = isset($item['active']) && $item['active'];
            $disabled = isset($item['disabled']) && $item['disabled'];
            $children = $item['children'] ?? null;

            // Build link/button content
            $content = '';
            if ($icon !== null) {
                $content .= $icon;
            }
            $content .= $text;

            // Build link/button attributes
            $itemAttrs = [];
            if ($active) {
                $itemAttrs['class'] = ClassMap::get('menuItem.modifier.active');
                $itemAttrs['aria-current'] = 'page';
            }
            if ($disabled) {
                $itemAttrs['aria-disabled'] = 'true';
                $itemAttrs['tabindex'] = '-1';
            }

            // Build the link/button element
            $linkContent = '';
            if ($url !== null && $disabled) {
                // aria-disabled alone doesn't stop a link: drop the href.
                $linkContent = $this->tag('a', $content, $itemAttrs);
            } elseif ($url !== null) {
                $linkContent = $this->Html->link($content, $url, $itemAttrs + ['escapeTitle' => false]);
            } else {
                $linkContent = $this->tag('button', $content, $itemAttrs + ['type' => 'button']);
            }

            // Build the li element
            $liAttrs = [];
            if ($disabled) {
                $liAttrs['class'] = ClassMap::get('menuItem.modifier.disabled');
            }

            if ($disabled && $children !== null && count($children) > 0) {
                // A disabled parent doesn't open: show its label only, without the submenu.
                $html .= $this->tag('li', $this->tag('span', $content, ['aria-disabled' => 'true']), $liAttrs);
            } elseif ($children !== null && count($children) > 0) {
                // Submenu with details/summary
                // The summary toggles the submenu: it holds the label, not a link or button.
                $summary = $this->tag('summary', $content);
                $submenuHtml = $this->tag('ul', $this->_renderMenuItems($children));
                $open = empty($item['open']) ? [] : ['open' => 'open'];
                $details = $this->tag('details', $summary . $submenuHtml, $open);
                $html .= $this->tag('li', $details, $liAttrs);
            } else {
                // Regular item
                $html .= $this->tag('li', $linkContent, $liAttrs);
            }
        }

        return $html;
    }

    /**
     * Navbar. https://daisyui.com/components/navbar/
     *
     * Renders `<div class="navbar">` with optional `start`, `center`, and `end` sections.
     * Section values are raw HTML (docblock specifies they are raw).
     *
     * @param array<string, mixed> $sections Array with keys `start`, `center`, `end` (each is raw HTML).
     * @param array<string, mixed> $options `class`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function navbar(array $sections, array $options = []): string
    {
        $class = $this->componentClass('navbar', $options);

        $content = '';
        if (isset($sections['start'])) {
            $content .= $this->tag('div', $sections['start'], ['class' => ClassMap::get('navbar.part.start')]);
        }
        if (isset($sections['center'])) {
            $content .= $this->tag('div', $sections['center'], ['class' => ClassMap::get('navbar.part.center')]);
        }
        if (isset($sections['end'])) {
            $content .= $this->tag('div', $sections['end'], ['class' => ClassMap::get('navbar.part.end')]);
        }

        return $this->tag('div', $content, ['class' => $class] + $options);
    }

    /**
     * Steps. https://daisyui.com/components/steps/
     *
     * Renders `<ul class="steps">` with step items. Each item is escaped text by default,
     * or an array with `text` (escaped), `color` (neutral, primary, secondary, accent, info,
     * success, warning, error), `icon` (raw HTML in a span.step-icon before text), `content`
     * (string for data-content attribute), and `current` (bool for aria-current="step").
     *
     * @param array<string, mixed> $items List of items (strings or arrays with `text`, `color`,
     *   `icon`, `content`, `current`).
     * @param array<string, mixed> $options `direction` (vertical, horizontal), `class`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function steps(array $items, array $options = []): string
    {
        $class = $this->componentClass('steps', $options);

        $steps = [];
        foreach ($items as $item) {
            if (is_string($item)) {
                $text = h($item);
                $itemOptions = [];
                $itemClass = ClassMap::get('step.base');
            } else {
                $text = h($item['text'] ?? '');
                $itemOptions = [];
                $itemClass = ClassMap::get('step.base');
                // Build color class using componentClass for the step
                if (isset($item['color'])) {
                    $colorClass = ClassMap::get('step.color.' . $item['color']);
                    $itemClass .= ' ' . $colorClass;
                }
                if (isset($item['content'])) {
                    $itemOptions['data-content'] = $item['content'];
                }
                if (isset($item['current']) && $item['current']) {
                    $itemOptions['aria-current'] = 'step';
                }
            }

            // Add icon if present
            $content = '';
            if (is_array($item) && isset($item['icon'])) {
                $content .= $this->tag('span', $item['icon'], ['class' => ClassMap::get('step.part.icon')]);
            }
            $content .= $text;

            $steps[] = $this->tag('li', $content, ['class' => $itemClass] + $itemOptions);
        }

        return $this->tag('ul', implode('', $steps), ['class' => $class] + $options);
    }

    /**
     * Tabs. https://daisyui.com/components/tab/
     *
     * Renders tabs in link mode (if no item has `content`) or content mode (if any item has `content`).
     * Link mode: `<a role="tab" class="tab" href>` or `<button role="tab" class="tab">`.
     * Content mode: `<input type="radio" name="{name}" class="tab" aria-label="{title}">` + `<div class="tab-content">`.
     *
     * @param array<string, mixed> $items List of items with `title` (required, escaped), `url`,
     *   `active` (bool), `disabled` (bool), `content` (raw HTML).
     * @param array<string, mixed> $options `appearance` (box, border, lift), `size` (xs–xl),
     *   `placement` (top, bottom), `name` (radio group name for content mode), `class`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function tabs(array $items, array $options = []): string
    {
        $class = $this->componentClass('tabs', $options);
        $name = $options['name'] ?? null;
        unset($options['escape'], $options['name']);

        // Determine mode: content mode if any item has 'content'
        $hasContent = false;
        foreach ($items as $item) {
            if (is_array($item) && isset($item['content'])) {
                $hasContent = true;
                break;
            }
        }

        $tabs = [];
        if ($hasContent) {
            // Content mode: radio inputs + tab-content divs
            if ($name === null) {
                $this->tabCounter++;
                $name = 'tabs-' . $this->tabCounter;
            }
            foreach ($items as $item) {
                $content = $item['content'] ?? '';
                $disabled = isset($item['disabled']) && $item['disabled'];
                $checked = isset($item['active']) && $item['active'];

                $attrs = [
                    'type' => 'radio',
                    'name' => $name,
                    'class' => ClassMap::get('tab.base'),
                    'role' => 'tab',
                    // Raw: attribute values are escaped by tag().
                    'aria-label' => (string)($item['title'] ?? ''),
                ];
                if ($checked) {
                    $attrs['checked'] = 'checked';
                }
                if ($disabled) {
                    $attrs['disabled'] = 'disabled';
                }

                $input = $this->tag('input', '', $attrs);
                $contentDiv = $this->tag('div', $content, ['class' => ClassMap::get('tab.part.content')]);
                $tabs[] = $input . $contentDiv;
            }
        } else {
            // Link mode: <a> or <button> tabs
            foreach ($items as $item) {
                $title = h($item['title'] ?? '');
                $url = $item['url'] ?? null;
                $disabled = isset($item['disabled']) && $item['disabled'];
                $active = isset($item['active']) && $item['active'];

                $attrs = ['role' => 'tab', 'class' => ClassMap::get('tab.base')];
                if ($active) {
                    $attrs['class'] .= ' ' . ClassMap::get('tab.modifier.active');
                    $attrs['aria-selected'] = 'true';
                }
                if ($disabled) {
                    $attrs['class'] .= ' ' . ClassMap::get('tab.modifier.disabled');
                    $attrs['aria-disabled'] = 'true';
                    $attrs['tabindex'] = '-1';
                }

                if ($url !== null && $disabled) {
                    // aria-disabled alone doesn't stop a link: drop the href.
                    $tabs[] = $this->tag('a', $title, $attrs);
                } elseif ($url !== null) {
                    $tabs[] = $this->Html->link($title, $url, $attrs + ['escapeTitle' => false]);
                } else {
                    $button = $attrs + ['type' => 'button'] + ($disabled ? ['disabled' => 'disabled'] : []);
                    $tabs[] = $this->tag('button', $title, $button);
                }
            }
        }

        return $this->tag('div', implode('', $tabs), ['role' => 'tablist', 'class' => $class] + $options);
    }

    /**
     * Megamenu. https://daisyui.com/components/megamenu/
     *
     * A large horizontal menu with items that open popovers. Each item has a label (escaped)
     * and raw HTML content.
     *
     * @param string $id Unique HTML ID for the megamenu container.
     * @param array<string, mixed> $items List of items with `label` (escaped) and `content` (raw HTML).
     * @param array<string, mixed> $options `modifier` (wide, full), `size` (xs–xl),
     *   `direction` (vertical), `class`;
     *   any other key becomes an HTML attribute.
     * @return string
     * @throws \InvalidArgumentException if more than 10 items.
     */
    public function megamenu(string $id, array $items, array $options = []): string
    {
        if (count($items) > 10) {
            throw new InvalidArgumentException('Megamenu can have a maximum of 10 items');
        }

        $class = $this->componentClass('megamenu', $options);

        $content = $this->tag('span', '', ['class' => ClassMap::get('megamenu.part.active')]);

        $itemIndex = 0;
        foreach ($items as $item) {
            // Count, don't use the array key: string keys would all map to the same id.
            $itemIndex++;
            $label = h($item['label'] ?? '');
            $itemContent = $item['content'] ?? '';
            $itemId = "{$id}-{$itemIndex}";

            $content .= $this->tag('button', $label, [
                'type' => 'button',
                'popovertarget' => $itemId,
            ]);
            $content .= $this->tag('div', $itemContent, [
                'id' => $itemId,
                'popover' => 'auto',
            ]);
        }

        return $this->tag('div', $content, [
            'id' => $id,
            'popover' => 'auto',
            'class' => $class,
        ] + $options);
    }

    /**
     * Megamenu button. https://daisyui.com/components/megamenu/
     *
     * A button that toggles a megamenu. `$text` is escaped.
     *
     * @param string $text Button text, escaped.
     * @param string $id ID of the megamenu to toggle.
     * @param array<string, mixed> $options Button styling options (from ActionsHelper->button),
     *   `class`; any other key becomes an HTML attribute.
     * @return string
     */
    public function megamenuButton(string $text, string $id, array $options = []): string
    {
        $class = $this->componentClass('button', $options);
        $text = $this->content($text, $options);

        return $this->tag('button', $text, [
            'type' => 'button',
            'popovertarget' => $id,
            'class' => $class,
        ] + $options);
    }
}
