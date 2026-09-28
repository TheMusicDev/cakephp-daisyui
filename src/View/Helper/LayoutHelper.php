<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use Cake\View\Helper;
use InvalidArgumentException;
use TheMusicDev\DaisyUi\View\ClassMap;
use function Cake\Core\h;
use function Cake\I18n\__;

/**
 * daisyUI "Layout" components.
 *
 * @extends \Cake\View\Helper<\Cake\View\View>
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class LayoutHelper extends Helper
{
    use ComponentTrait;

    protected array $helpers = ['Html'];

    /**
     * Join. https://daisyui.com/components/join/
     *
     * Container for grouped items. `$content` is raw HTML (normally other helpers' output);
     * callers must escape any user data in the content.
     *
     * @param string $content Raw HTML, normally multiple helper calls.
     * @param array<string, mixed> $options `direction` (vertical, horizontal), `class`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function join(string $content, array $options = []): string
    {
        $class = $this->componentClass('join', $options);

        return $this->tag('div', $content, ['class' => $class] + $options);
    }

    /**
     * Returns the CSS class for join items via the class map.
     *
     * Use this in elements inside a join to ensure they get the `join-item` class:
     * `$this->Actions->button('A', ['class' => $this->Layout->joinItemClass()])`.
     *
     * @return string
     */
    public function joinItemClass(): string
    {
        return ClassMap::get('joinItem.base');
    }

    /**
     * Drawer. https://daisyui.com/components/drawer/
     *
     * Renders a grid layout with a toggle checkbox, content area, and sidebar.
     * `$content` and `$side` are raw HTML.
     *
     * @param string $id Unique HTML ID for the drawer toggle input.
     * @param string $content Raw HTML for the main content area.
     * @param string $side Raw HTML for the sidebar area.
     * @param array<string, mixed> $options `overlayLabel` (string, default from translation),
     *   `placement` (end), `modifier` (open), `class`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function drawer(string $id, string $content, string $side, array $options = []): string
    {
        $class = $this->componentClass('drawer', $options);
        $overlayLabel = $options['overlayLabel'] ?? __('Close sidebar');
        unset($options['overlayLabel']);

        $toggle = $this->tag('input', '', [
            'id' => $id,
            'type' => 'checkbox',
            'class' => ClassMap::get('drawer.part.toggle'),
        ]);

        $contentDiv = $this->tag('div', $content, ['class' => ClassMap::get('drawer.part.content')]);

        $overlay = $this->tag('label', '', [
            'for' => $id,
            'aria-label' => $overlayLabel,
            'class' => ClassMap::get('drawer.part.overlay'),
        ]);

        $sideDiv = $this->tag('div', $overlay . $side, ['class' => ClassMap::get('drawer.part.side')]);

        return $this->tag('div', $toggle . $contentDiv . $sideDiv, ['class' => $class] + $options);
    }

    /**
     * Drawer button. https://daisyui.com/components/drawer/
     *
     * Renders a label that toggles the drawer. Inherits `color`, `size`, `appearance`, and `modifier`
     * options from button styling, plus `class` and drawer-button styling.
     *
     * @param string $text Button text, escaped.
     * @param string $id ID of the drawer toggle input.
     * @param array<string, mixed> $options `color`, `size`, `appearance`, `modifier` (button styling),
     *   `class`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function drawerButton(string $text, string $id, array $options = []): string
    {
        $buttonClass = $this->componentClass('button', $options);
        $buttonClass .= ' ' . ClassMap::get('drawer.part.button');
        $text = $this->content($text, $options);

        return $this->tag('label', $text, ['for' => $id, 'class' => $buttonClass] + $options);
    }

    /**
     * Divider. https://daisyui.com/components/divider/
     *
     * Renders a separator with optional text. `$text` is escaped unless `'escape' => false`.
     *
     * @param string $text Plain text, escaped unless `'escape' => false`; empty for a blank divider.
     * @param array<string, mixed> $options `color` (neutral, primary, secondary, accent, success, warning, info, error),
     *   `direction` (vertical, horizontal), `placement` (start, end), `class`, `escape`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function divider(string $text = '', array $options = []): string
    {
        $class = $this->componentClass('divider', $options);
        $text = $this->content($text, $options);

        $attrs = ['class' => $class, 'role' => 'separator'] + $options;
        // Remove role if it was explicitly overridden in options
        if (isset($options['role'])) {
            $attrs['role'] = $options['role'];
        }

        return $this->tag('div', $text, $attrs);
    }

    /**
     * Footer. https://daisyui.com/components/footer/
     *
     * `$content` is raw HTML, or a list of sections, each rendered as
     * `<nav><h6 class="footer-title">{title}</h6>{links}</nav>`: `title` (escaped),
     * `links` (`['About' => '/about', …]` or a list of `['text' => …, 'url' => …]`;
     * link text escaped, styled `link link-hover`), `content` (raw HTML, after the links).
     *
     * @param array<int, array<string, mixed>>|string $content Raw HTML or sections.
     * @param array<string, mixed> $options `placement` (center), `direction` (horizontal,
     *   vertical), `class`; any other key becomes a `<footer>` attribute.
     * @return string
     */
    public function footer(string|array $content, array $options = []): string
    {
        $class = $this->componentClass('footer', $options);
        if (is_array($content)) {
            $sections = '';
            foreach ($content as $section) {
                $sections .= $this->tag('nav', $this->footerSection((array)$section));
            }
            $content = $sections;
        }

        return $this->tag('footer', $content, ['class' => $class] + $options);
    }

    /**
     * Inner HTML of one footer section.
     *
     * @param array<string, mixed> $section `title`, `links`, `content`.
     * @return string
     */
    private function footerSection(array $section): string
    {
        $html = '';
        if (isset($section['title']) && $section['title'] !== '') {
            $html .= $this->tag('h6', h((string)$section['title']), ['class' => ClassMap::get('footer.part.title')]);
        }
        $linkClass = ClassMap::classes('link.base', 'link.appearance.hover');
        foreach ((array)($section['links'] ?? []) as $text => $url) {
            if (is_array($url) && array_key_exists('text', $url)) {
                [$text, $url] = [$url['text'], $url['url'] ?? '#'];
            }
            $html .= $this->Html->link(h((string)$text), $url, ['class' => $linkClass, 'escapeTitle' => false]);
        }

        return $html . (string)($section['content'] ?? '');
    }

    /**
     * Hero. https://daisyui.com/components/hero/
     *
     * A hero section with optional overlay. `$content` is raw HTML.
     *
     * @param string $content Raw HTML for the hero content.
     * @param array<string, mixed> $options `overlay` (bool), `contentClass` (string|array, extra classes on content),
     *   `class`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function hero(string $content, array $options = []): string
    {
        $class = $this->componentClass('hero', $options);
        $overlay = isset($options['overlay']) && $options['overlay'];
        $contentClass = $options['contentClass'] ?? null;
        unset($options['overlay'], $options['contentClass']);

        $html = '';
        if ($overlay) {
            $html .= $this->tag('div', '', ['class' => ClassMap::get('hero.part.overlay')]);
        }

        // Build content class
        $heroContentClass = ClassMap::get('hero.part.content');
        if ($contentClass !== null) {
            if (is_array($contentClass)) {
                $contentClass = implode(' ', $contentClass);
            }
            $heroContentClass .= ' ' . $contentClass;
        }

        $html .= $this->tag('div', $content, ['class' => $heroContentClass]);

        return $this->tag('div', $html, ['class' => $class] + $options);
    }

    /**
     * Indicator. https://daisyui.com/components/indicator/
     *
     * Puts an element (indicator item) at a corner of another element. `$content` is raw HTML,
     * `$item` is raw HTML. Placement is optional.
     *
     * @param string $content Raw HTML for the main content.
     * @param string $item Raw HTML for the indicator item.
     * @param array<string, mixed> $options `placement` (string|list, e.g. 'start' or ['start', 'top']),
     *   `itemClass` (extra classes on the item), `class`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function indicator(string $content, string $item, array $options = []): string
    {
        $placement = $options['placement'] ?? null;
        $itemClass = $options['itemClass'] ?? null;
        unset($options['placement'], $options['itemClass']);

        $class = $this->componentClass('indicator', $options);

        // Build item class
        $placementClass = ClassMap::get('indicatorItem.base');
        if ($placement !== null) {
            if (is_string($placement)) {
                $placement = [$placement];
            }
            foreach ($placement as $p) {
                $placementClass .= ' ' . ClassMap::get('indicatorItem.placement.' . $p);
            }
        }
        if ($itemClass !== null) {
            $placementClass .= ' ' . $itemClass;
        }

        $itemHtml = $this->tag('span', $item, ['class' => $placementClass]);

        return $this->tag('div', $itemHtml . $content, ['class' => $class] + $options);
    }

    /**
     * Mask. https://daisyui.com/components/mask/
     *
     * Crops an image to a shape. `$image` is passed to Html::image().
     *
     * @param array<string, mixed>|string $image Image path/array for Html::image().
     * @param array<string, mixed> $options `alt` (string, default ''), `appearance` (shape, required),
     *   `modifier` (half-1, half-2), `class`; any other key becomes an image attribute.
     * @return string
     * @throws \InvalidArgumentException if neither `appearance` nor `modifier` is given.
     */
    public function mask(array|string $image, array $options = []): string
    {
        $appearance = $options['appearance'] ?? null;
        $modifier = $options['modifier'] ?? null;

        if ($appearance === null && $modifier === null) {
            throw new InvalidArgumentException('Mask requires either an appearance or modifier option');
        }

        $class = $this->componentClass('mask', $options);
        $alt = $options['alt'] ?? '';
        // `escape` in Html->image() would switch off attribute escaping.
        unset($options['alt'], $options['appearance'], $options['modifier'], $options['escape']);

        return $this->Html->image($image, ['class' => $class, 'alt' => $alt] + $options);
    }

    /**
     * Stack. https://daisyui.com/components/stack/
     *
     * Stacks elements on top of each other. `$content` is raw HTML.
     *
     * @param string $content Raw HTML for the stacked items.
     * @param array<string, mixed> $options `modifier` (top, bottom, start, end), `class`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function stack(string $content, array $options = []): string
    {
        $class = $this->componentClass('stack', $options);

        return $this->tag('div', $content, ['class' => $class] + $options);
    }
}
