<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use ArrayAccess;
use BackedEnum;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\View\Helper;
use DateTimeInterface;
use InvalidArgumentException;
use Stringable;
use TheMusicDev\DaisyUi\View\ClassMap;
use function Cake\Core\h;
use function Cake\I18n\__;

/**
 * daisyUI "Data display" components.
 *
 * @extends \Cake\View\Helper<\Cake\View\View>
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class DataDisplayHelper extends Helper
{
    use ComponentTrait;

    protected array $helpers = ['Html'];

    /**
     * Counter for generating unique accordion group names.
     */
    private int $accordionCounter = 0;

    /**
     * Badge. https://daisyui.com/components/badge/
     *
     * @param string $text Plain text, escaped unless `'escape' => false`.
     * @param array<string, mixed> $options `color`, `size`, `appearance`, `class`, `escape`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function badge(string $text, array $options = []): string
    {
        $class = $this->componentClass('badge', $options);
        $text = $this->content($text, $options);

        return $this->tag('span', $text, ['class' => $class] + $options);
    }

    /**
     * Card. https://daisyui.com/components/card/
     *
     * `$body` is escaped and wrapped in `<p>` by default; with `'escape' => false`
     * it is output raw (not wrapped in `<p>`), since the caller controls the markup
     * and must escape any user data in it. `title` is always escaped. `actions`
     * and `image` are raw HTML/helpers' output (`actions` comes from other helpers'
     * output and the caller must escape any user data in it; `image` is passed to
     * `Html::image()`). `card-body` is always rendered.
     *
     * @param string $body Card content; see above.
     * @param array<string, mixed> $options `title` (plain text, `<h2 class="card-title">`),
     *   `actions` (raw HTML, `<div class="card-actions">`), `image` (string|array, passed to
     *   `Html::image()`, rendered in a `<figure>` before the body), `imageAlt` (string, default
     *   `''`), `size`, `modifier`, `appearance`, `class`, `escape`;
     *   any other key becomes an attribute on the outer `<div>`.
     * @return string
     */
    public function card(string $body, array $options = []): string
    {
        $class = $this->componentClass('card', $options);
        $title = $options['title'] ?? null;
        $actions = $options['actions'] ?? null;
        $image = $options['image'] ?? null;
        $imageAlt = (string)($options['imageAlt'] ?? '');
        $escape = $options['escape'] ?? true;
        unset($options['title'], $options['actions'], $options['image'], $options['imageAlt'], $options['escape']);

        $outer = '';
        if ($image !== null) {
            $outer .= $this->tag('figure', $this->Html->image($image, ['alt' => $imageAlt]));
        }
        $inner = '';
        if ($title !== null) {
            $inner .= $this->tag('h2', h($title), ['class' => ClassMap::get('card.part.title')]);
        }
        $inner .= $escape ? $this->tag('p', h($body)) : $body;
        if ($actions !== null) {
            $inner .= $this->tag('div', $actions, ['class' => ClassMap::get('card.part.actions')]);
        }
        $outer .= $this->tag('div', $inner, ['class' => ClassMap::get('card.part.body')]);

        return $this->tag('div', $outer, ['class' => $class] + $options);
    }

    /**
     * Avatar. https://daisyui.com/components/avatar/
     *
     * Display a thumbnail image or placeholder avatar.
     *
     * @param array<string, mixed>|string|null $image Image path/array for Html::image(), or null for placeholder.
     * @param array<string, mixed> $options `alt` (string, default `''` for image alt), `placeholder`
     *   (string, default `''`, plain text always escaped for placeholder), `innerClass` (string|array,
     *   default null for inner div classes), `modifier` (online, offline, placeholder; string or list),
     *   `class`, any other key becomes an HTML attribute.
     * @return string
     */
    public function avatar(array|string|null $image, array $options = []): string
    {
        $alt = (string)($options['alt'] ?? '');
        $placeholder = (string)($options['placeholder'] ?? '');
        $innerClass = $options['innerClass'] ?? null;
        unset($options['alt'], $options['placeholder'], $options['innerClass']);

        if ($image === null) {
            // A placeholder avatar always gets `avatar-placeholder`, exactly once.
            $options['modifier'] = array_unique([...(array)($options['modifier'] ?? []), 'placeholder']);
        }

        $class = $this->componentClass('avatar', $options);

        if ($image === null) {
            $inner = $this->tag('span', h($placeholder));
        } else {
            $inner = $this->Html->image($image, ['alt' => $alt]);
        }

        $innerDivAttrs = [];
        if ($innerClass !== null) {
            $innerDivAttrs['class'] = $innerClass;
        }
        $innerDiv = $this->tag('div', $inner, $innerDivAttrs);

        return $this->tag('div', $innerDiv, ['class' => $class] + $options);
    }

    /**
     * Avatar group. https://daisyui.com/components/avatar/
     *
     * Container for multiple avatars. `$content` is raw HTML (normally several avatar() calls);
     * callers must escape any user data in the content.
     *
     * @param string $content Raw HTML, normally several avatar() calls.
     * @param array<string, mixed> $options `class`, any other key becomes an HTML attribute.
     * @return string
     */
    public function avatarGroup(string $content, array $options = []): string
    {
        $class = $this->componentClass('avatarGroup', $options);

        return $this->tag('div', $content, ['class' => $class] + $options);
    }

    /**
     * Kbd. https://daisyui.com/components/kbd/
     *
     * @param string $text Plain text, escaped unless `'escape' => false`.
     * @param array<string, mixed> $options `size`, `class`, `escape`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function kbd(string $text, array $options = []): string
    {
        $class = $this->componentClass('kbd', $options);
        $text = $this->content($text, $options);

        return $this->tag('kbd', $text, ['class' => $class] + $options);
    }

    /**
     * Status. https://daisyui.com/components/status/
     *
     * Renders `<span class="status …"></span>`. By default, sets `aria-hidden="true"`.
     * If `aria-label` is provided, sets `role="img"` and omits `aria-hidden`.
     *
     * @param array<string, mixed> $options `color`, `size`, `role`, `aria-label`,
     *   `aria-hidden`, `class`; any other key becomes an HTML attribute.
     * @return string
     */
    public function status(array $options = []): string
    {
        $class = $this->componentClass('status', $options);
        // Unlabelled = decorative, hidden from screen readers; labelled = an image with a name.
        $defaults = isset($options['aria-label']) ? ['role' => 'img'] : ['aria-hidden' => 'true'];
        $attributes = $options + $defaults;

        return $this->tag('span', '', ['class' => $class] + $attributes);
    }

    /**
     * List. https://daisyui.com/components/list/
     *
     * Renders `<ul class="list">` with list items. Each item is escaped text by default,
     * or an array with `text` (escaped), `content` (raw HTML), and `class` (extra classes).
     *
     * @param array<string, mixed> $items List of items (strings or arrays with `text`, `content`, `class`).
     * @param array<string, mixed> $options `class`, any other key becomes an HTML attribute.
     * @return string
     */
    public function list(array $items, array $options = []): string
    {
        $class = $this->componentClass('list', $options);

        $rows = [];
        foreach ($items as $item) {
            if (is_string($item)) {
                $content = h($item);
            } else {
                $content = $item['content'] ?? h($item['text'] ?? '');
            }
            $itemClass = ClassMap::get('list.part.row');
            if (is_array($item) && isset($item['class'])) {
                $itemClass .= ' ' . (is_string($item['class']) ? $item['class'] : implode(' ', $item['class']));
            }
            $rows[] = $this->tag('li', $content, ['class' => $itemClass]);
        }

        return $this->tag('ul', implode('', $rows), ['class' => $class] + $options);
    }

    /**
     * List column class. https://daisyui.com/components/list/
     *
     * Returns the class-map value for a list child column. 'grow' returns 'list-col-grow',
     * 'wrap' returns 'list-col-wrap'. Any other name throws OutOfBoundsException.
     *
     * @param string $name The column name ('grow' or 'wrap').
     * @return string
     * @throws \OutOfBoundsException
     */
    public function listColumnClass(string $name): string
    {
        return ClassMap::get('list.part.col' . ucfirst($name));
    }

    /**
     * Stat. https://daisyui.com/components/stat/
     *
     * Renders `<div class="stats">` with stat items. Each item can contain figure (raw HTML),
     * title (escaped), value (escaped), desc (escaped), actions (raw HTML), and class.
     * Parts are rendered only when set.
     *
     * @param array<string, mixed> $items List of items with `figure`, `title`, `value`,
     *   `desc`, `actions` (raw HTML), `class` keys.
     * @param array<string, mixed> $options `direction` (horizontal, vertical), `class`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function stats(array $items, array $options = []): string
    {
        $class = $this->componentClass('stats', $options);

        $stats = [];
        foreach ($items as $item) {
            $inner = '';
            if (isset($item['figure'])) {
                $inner .= $this->tag('div', $item['figure'], ['class' => ClassMap::get('stats.part.figure')]);
            }
            if (isset($item['title'])) {
                $inner .= $this->tag('div', h($item['title']), ['class' => ClassMap::get('stats.part.title')]);
            }
            if (isset($item['value'])) {
                $inner .= $this->tag('div', h($item['value']), ['class' => ClassMap::get('stats.part.value')]);
            }
            if (isset($item['desc'])) {
                $inner .= $this->tag('div', h($item['desc']), ['class' => ClassMap::get('stats.part.desc')]);
            }
            if (isset($item['actions'])) {
                $inner .= $this->tag('div', $item['actions'], ['class' => ClassMap::get('stats.part.actions')]);
            }
            $itemClass = ClassMap::get('stats.part.stat');
            if (isset($item['class'])) {
                $itemClass .= ' ' . (is_string($item['class']) ? $item['class'] : implode(' ', $item['class']));
            }
            $stats[] = $this->tag('div', $inner, ['class' => $itemClass]);
        }

        return $this->tag('div', implode('', $stats), ['class' => $class] + $options);
    }

    /**
     * Collapse. https://daisyui.com/components/collapse/
     *
     * Renders native `<details class="collapse">` with a summary and content. Title is always escaped.
     * Content is raw HTML (callers must escape user data).
     *
     * @param string $title Plain text, always escaped.
     * @param string $content Raw HTML, callers must escape user data.
     * @param array<string, mixed> $options `open` (bool), `modifier` (arrow, plus, open, close;
     *   string or list), `class`; any other key becomes an HTML attribute.
     * @return string
     */
    public function collapse(string $title, string $content, array $options = []): string
    {
        $class = $this->componentClass('collapse', $options);
        $open = isset($options['open']) && $options['open'];
        unset($options['open']);

        $summary = $this->tag('summary', h($title), ['class' => ClassMap::get('collapse.part.title')]);
        $contentDiv = $this->tag('div', $content, ['class' => ClassMap::get('collapse.part.content')]);

        $attrs = ['class' => $class] + $options;
        if ($open) {
            $attrs['open'] = 'open';
        }

        return $this->tag('details', $summary . $contentDiv, $attrs);
    }

    /**
     * Accordion. https://daisyui.com/components/accordion/
     *
     * Renders an accordion group where only one item can be open at a time using radio inputs.
     * Reuses the collapse class map. Title is always escaped, content is raw HTML.
     *
     * @param array<string, mixed> $items List of items with `title` (escaped), `content` (raw),
     *   `open` (bool) keys.
     * @param array<string, mixed> $options `name` (radio group name), `modifier` (arrow, plus;
     *   string or list, applied to all items), `class` (applied to all items); any other key
     *   becomes an HTML attribute on each item.
     * @return string
     */
    public function accordion(array $items, array $options = []): string
    {
        $name = $options['name'] ?? null;
        if ($name === null) {
            $this->accordionCounter++;
            $name = 'accordion-' . $this->accordionCounter;
        }
        unset($options['escape'], $options['name']);

        // Extract modifier and class that apply to every item
        $modifierOptions = [];
        if (isset($options['modifier'])) {
            $modifierOptions['modifier'] = $options['modifier'];
            unset($options['modifier']);
        }
        $itemClass = '';
        if (isset($options['class'])) {
            $itemClass = $options['class'];
            unset($options['class']);
        }

        $accordion = [];
        foreach ($items as $item) {
            $title = h($item['title'] ?? '');
            $content = $item['content'] ?? '';
            $checked = isset($item['open']) && $item['open'];

            // Build collapse class with modifiers
            $collapseOptions = $modifierOptions;
            if ($itemClass !== '') {
                $collapseOptions['class'] = $itemClass;
            }
            $class = $this->componentClass('collapse', $collapseOptions);

            // Create radio input
            $inputAttrs = [
                'type' => 'radio',
                'name' => $name,
                // Raw: attribute values are escaped by tag().
                'aria-label' => (string)($item['title'] ?? ''),
            ];
            if ($checked) {
                $inputAttrs['checked'] = 'checked';
            }
            $input = $this->tag('input', '', $inputAttrs);

            // Create title and content divs
            $titleDiv = $this->tag('div', $title, ['class' => ClassMap::get('collapse.part.title')]);
            $contentDiv = $this->tag('div', $content, ['class' => ClassMap::get('collapse.part.content')]);

            $accordion[] = $this->tag('div', $input . $titleDiv . $contentDiv, ['class' => $class] + $options);
        }

        return implode('', $accordion);
    }

    /**
     * Timeline. https://daisyui.com/components/timeline/
     *
     * Renders a timeline with items in chronological order. Each item can have start, middle, and end.
     * Start and end are escaped unless `'escape' => false` on the item.
     *
     * @param array<string, mixed> $items List of items with `start`, `end` (escaped unless
     *   `escape => false`), `middle` (raw HTML), `box` ('start' or 'end') keys.
     * @param array<string, mixed> $options `modifier` (snap-icon, compact; string or list),
     *   `direction` (vertical, horizontal), `connect` (bool, default true), `class`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function timeline(array $items, array $options = []): string
    {
        $class = $this->componentClass('timeline', $options);
        $connect = $options['connect'] ?? true;
        unset($options['connect']);

        $timeline = [];
        foreach ($items as $index => $item) {
            $inner = '';
            // Add hr before (except for first item)
            if ($connect && $index > 0) {
                $inner .= '<hr>';
            }

            if (isset($item['start'])) {
                $startEscape = $item['escape'] ?? true;
                $startText = $startEscape ? h($item['start']) : $item['start'];
                $startClass = ClassMap::get('timeline.part.start');
                if (isset($item['box']) && $item['box'] === 'start') {
                    $startClass .= ' ' . ClassMap::get('timeline.part.box');
                }
                $inner .= $this->tag('div', $startText, ['class' => $startClass]);
            }

            if (isset($item['middle'])) {
                $inner .= $this->tag('div', $item['middle'], ['class' => ClassMap::get('timeline.part.middle')]);
            }

            if (isset($item['end'])) {
                $endEscape = $item['escape'] ?? true;
                $endText = $endEscape ? h($item['end']) : $item['end'];
                $endClass = ClassMap::get('timeline.part.end');
                if (isset($item['box']) && $item['box'] === 'end') {
                    $endClass .= ' ' . ClassMap::get('timeline.part.box');
                }
                $inner .= $this->tag('div', $endText, ['class' => $endClass]);
            }

            $timeline[] = $this->tag('li', $inner);
        }

        return $this->tag('ul', implode('', $timeline), ['class' => $class] + $options);
    }

    /**
     * Table. https://daisyui.com/components/table/
     *
     * Rows are arrays or entities; each cell is read with `Hash::get($row, $path)`,
     * so association paths like `author.name` work. Cell values are escaped;
     * a column's `format` callable returns raw HTML (e.g. another helper's
     * output) — escape any user data in it yourself.
     *
     * @param iterable<mixed> $rows Records (arrays or `ArrayAccess`, e.g. entities).
     * @param array<int|string, mixed> $columns `['title', 'author.name' => 'Author',
     *   'created' => ['label' => 'Created', 'format' => fn($value, $row) => …, 'class' => …]]`.
     * @param array<string, mixed> $options `size`, `modifier` (zebra, pin-rows, pin-cols),
     *   `caption` (escaped), `empty` (escaped text shown when there are no rows),
     *   `rowHeader` (bool: first column as `<th scope="row">`), `wrap` (bool, default
     *   true: scroll wrapper div), `class`; any other key becomes a `<table>` attribute.
     * @return string
     */
    public function table(iterable $rows, array $columns, array $options = []): string
    {
        $caption = $options['caption'] ?? null;
        $empty = $options['empty'] ?? null;
        $rowHeader = !empty($options['rowHeader']);
        $wrap = $options['wrap'] ?? true;
        unset($options['caption'], $options['empty'], $options['rowHeader'], $options['wrap'], $options['escape']);
        $class = $this->componentClass('table', $options);

        $normalized = [];
        foreach ($columns as $path => $column) {
            if (is_int($path)) {
                $path = (string)$column;
                $column = [];
            } elseif (!is_array($column)) {
                $column = ['label' => $column];
            }
            $segments = explode('.', (string)$path);
            $column += ['label' => Inflector::humanize(Inflector::underscore((string)end($segments)))];
            $normalized[(string)$path] = $column;
        }

        $head = '';
        foreach ($normalized as $column) {
            $head .= $this->tag('th', h((string)$column['label']), [
                'scope' => 'col',
                'class' => $column['class'] ?? null,
            ]);
        }

        $body = '';
        foreach ($rows as $row) {
            $cells = '';
            $first = true;
            foreach ($normalized as $path => $column) {
                $value = is_array($row) || $row instanceof ArrayAccess ? Hash::get($row, $path) : null;
                $cell = isset($column['format']) && is_callable($column['format'])
                    ? (string)$column['format']($value, $row)
                    : h($this->cellText($value));
                $attributes = ['class' => $column['class'] ?? null];
                if ($rowHeader && $first) {
                    $cells .= $this->tag('th', $cell, ['scope' => 'row'] + $attributes);
                } else {
                    $cells .= $this->tag('td', $cell, $attributes);
                }
                $first = false;
            }
            $body .= $this->tag('tr', $cells);
        }
        if ($body === '' && $empty !== null) {
            $body = $this->tag('tr', $this->tag('td', h((string)$empty), [
                'colspan' => count($normalized),
            ]));
        }

        $table = $this->tag(
            'table',
            ($caption !== null ? $this->tag('caption', h((string)$caption)) : '')
                . $this->tag('thead', $this->tag('tr', $head))
                . $this->tag('tbody', $body),
            ['class' => $class] + $options,
        );

        return $wrap ? $this->tag('div', $table, ['class' => ClassMap::get('table.part.wrapper')]) : $table;
    }

    /**
     * Plain text for a table cell value.
     *
     * @param mixed $value Cell value.
     * @return string
     */
    private function cellText(mixed $value): string
    {
        return match (true) {
            $value === null => '',
            is_bool($value) => $value ? __('Yes') : __('No'),
            is_scalar($value), $value instanceof Stringable => (string)$value,
            // Native DateTime isn't Stringable (Cake's date classes are).
            $value instanceof DateTimeInterface => $value->format('Y-m-d H:i:s'),
            $value instanceof BackedEnum => (string)$value->value,
            default => '',
        };
    }

    /**
     * Carousel. https://daisyui.com/components/carousel/
     *
     * Display a scrollable carousel of items.
     *
     * @param array<string|array<string, mixed>> $items Each item is a string (raw HTML) or an array with:
     *   - `content` (string, raw HTML); `class` (string|array); `id` (string).
     * @param array<string, mixed> $options `modifier` (start, center, end), `direction` (horizontal, vertical),
     *   `itemClass` (classes on every item), `class`, and other keys become HTML attributes.
     * @return string
     */
    public function carousel(array $items, array $options = []): string
    {
        $class = $this->componentClass('carousel', $options);
        $itemClass = $options['itemClass'] ?? '';
        unset($options['itemClass']);

        $itemElements = [];
        foreach ($items as $item) {
            $content = '';
            $itemId = '';
            $itemCls = ClassMap::get('carousel.part.item');

            if (is_string($item)) {
                $content = $item;
            } else {
                $content = $item['content'] ?? '';
                if (isset($item['class'])) {
                    $itemCls .= ' ' . (is_string($item['class']) ? $item['class'] : implode(' ', $item['class']));
                }
                if (isset($item['id'])) {
                    $itemId = $item['id'];
                }
            }

            if ($itemClass) {
                $itemCls .= ' ' . $itemClass;
            }

            $itemAttrs = ['class' => $itemCls];
            if ($itemId) {
                $itemAttrs['id'] = $itemId;
            }

            $itemElements[] = $this->tag('div', $content, $itemAttrs);
        }

        return $this->tag('div', implode('', $itemElements), ['class' => $class] + $options);
    }

    /**
     * Chat bubble. https://daisyui.com/components/chat/
     *
     * Display a chat message with optional image, header, footer, and color.
     *
     * @param string $message Message text, escaped unless `'escape' => false`.
     * @param array<string, mixed> $options `placement` (start, end; default 'start'), `color`
     *   (neutral, primary, secondary, accent, info, success, warning, error), `image` (raw HTML),
     *   `header` (plain text, escaped), `footer` (plain text, escaped), `class`, `escape`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function chat(string $message, array $options = []): string
    {
        $placement = $options['placement'] ?? 'start';
        $color = $options['color'] ?? null;
        $image = $options['image'] ?? null;
        $header = $options['header'] ?? null;
        $footer = $options['footer'] ?? null;
        unset($options['placement'], $options['color'], $options['image'], $options['header'], $options['footer']);

        $class = ClassMap::get('chat.base');
        $class .= ' ' . ClassMap::get('chat.placement.' . $placement);
        if (isset($options['class'])) {
            $class .= ' ' . (is_string($options['class']) ? $options['class'] : implode(' ', $options['class']));
            unset($options['class']);
        }

        $messageContent = $this->content($message, $options);
        $bubbleClass = ClassMap::get('chatBubble.base');
        if ($color !== null) {
            $bubbleClass .= ' ' . ClassMap::get('chatBubble.color.' . $color);
        }

        $inner = '';
        if ($image !== null) {
            $imageClass = ClassMap::classes('chat.part.image', 'avatar.base');
            $inner .= $this->tag('div', $image, ['class' => $imageClass]);
        }
        if ($header !== null) {
            $inner .= $this->tag('div', h($header), ['class' => ClassMap::get('chat.part.header')]);
        }
        $inner .= $this->tag('div', $messageContent, ['class' => $bubbleClass]);
        if ($footer !== null) {
            $inner .= $this->tag('div', h($footer), ['class' => ClassMap::get('chat.part.footer')]);
        }

        return $this->tag('div', $inner, ['class' => $class] + $options);
    }

    /**
     * Countdown. https://daisyui.com/components/countdown/
     *
     * Display a countdown value with transition effect.
     *
     * @param int $value A number from 0 through 999.
     * @param array<string, mixed> $options `class`; any other key becomes an HTML attribute.
     * @return string
     * @throws \InvalidArgumentException if value is outside 0–999.
     */
    public function countdown(int $value, array $options = []): string
    {
        if ($value < 0 || $value > 999) {
            throw new InvalidArgumentException('Countdown value must be between 0 and 999');
        }

        $class = $this->componentClass('countdown', $options);
        $innerAttrs = [
            'style' => "--value:{$value};",
            'aria-live' => 'polite',
            'aria-label' => (string)$value,
        ];
        $inner = $this->tag('span', (string)$value, $innerAttrs);

        return $this->tag('span', $inner, ['class' => $class] + $options);
    }

    /**
     * Diff. https://daisyui.com/components/diff/
     *
     * Display a side-by-side comparison of two items.
     *
     * @param string $item1 Raw HTML content for first item.
     * @param string $item2 Raw HTML content for second item.
     * @param array<string, mixed> $options `class`; any other key becomes an HTML attribute.
     * @return string
     */
    public function diff(string $item1, string $item2, array $options = []): string
    {
        $class = $this->componentClass('diff', $options);
        $defaults = ['tabindex' => '0'];
        $attributes = $options + $defaults;

        $content = $this->tag('div', $item1, ['class' => ClassMap::get('diff.part.item1')])
            . $this->tag('div', $item2, ['class' => ClassMap::get('diff.part.item2')])
            . $this->tag('div', '', ['class' => ClassMap::get('diff.part.resizer')]);

        return $this->tag('figure', $content, ['class' => $class] + $attributes);
    }

    /**
     * Hover 3D card. https://daisyui.com/components/hover-3d/
     *
     * Display content with a 3D hover effect.
     *
     * @param string $content Raw HTML content.
     * @param array<string, mixed> $options `url` (optional; renders as `<a>` instead of `<div>`),
     *   `class`; any other key becomes an HTML attribute.
     * @return string
     */
    public function hover3d(string $content, array $options = []): string
    {
        $class = $this->componentClass('hover3d', $options);
        $url = $options['url'] ?? null;
        // `escape` in Html->link() would switch off attribute escaping.
        unset($options['url'], $options['escape']);

        $innerDivs = '';
        for ($i = 0; $i < 8; $i++) {
            $innerDivs .= '<div></div>';
        }
        $inner = $content . $innerDivs;

        if ($url !== null) {
            return $this->Html->link($inner, $url, ['class' => $class, 'escapeTitle' => false] + $options);
        }

        return $this->tag('div', $inner, ['class' => $class] + $options);
    }

    /**
     * Hover gallery. https://daisyui.com/components/hover-gallery/
     *
     * Display a gallery of images with hover effects.
     *
     * @param array<string|array<string, mixed>> $images Each image is a string (image path) or array with `src`
     *   (image path) and optional `alt` (default '').
     * @param array<string, mixed> $options `class`; any other key becomes an HTML attribute.
     * @return string
     * @throws \InvalidArgumentException if more than 10 images.
     */
    public function hoverGallery(array $images, array $options = []): string
    {
        if (count($images) > 10) {
            throw new InvalidArgumentException('Hover gallery cannot contain more than 10 images');
        }

        $class = $this->componentClass('hoverGallery', $options);

        $content = '';
        foreach ($images as $image) {
            if (is_string($image)) {
                $content .= $this->Html->image($image);
            } else {
                $alt = $image['alt'] ?? '';
                $src = $image['src'] ?? '';
                $content .= $this->Html->image($src, ['alt' => $alt]);
            }
        }

        return $this->tag('figure', $content, ['class' => $class] + $options);
    }

    /**
     * Text rotate. https://daisyui.com/components/text-rotate/
     *
     * Display rotating text lines.
     *
     * @param array<string|array<string, mixed>> $lines Each line is a string or array with `text` (plain text,
     *   escaped) and optional `class`.
     * @param array<string, mixed> $options `innerClass`, `class`; any other key becomes an HTML attribute.
     * @return string
     * @throws \InvalidArgumentException if fewer than 2 or more than 6 lines.
     */
    public function textRotate(array $lines, array $options = []): string
    {
        if (count($lines) < 2 || count($lines) > 6) {
            throw new InvalidArgumentException('Text rotate must contain between 2 and 6 lines');
        }

        $class = $this->componentClass('textRotate', $options);
        $innerClass = $options['innerClass'] ?? null;
        unset($options['innerClass']);

        $content = '';
        foreach ($lines as $line) {
            if (is_string($line)) {
                $content .= $this->tag('span', h($line));
            } else {
                $lineText = $line['text'] ?? '';
                $content .= $this->tag('span', h($lineText), isset($line['class']) ? ['class' => $line['class']] : []);
            }
        }

        $inner = $innerClass !== null
            ? $this->tag('span', $content, ['class' => $innerClass])
            : $this->tag('span', $content);

        return $this->tag('span', $inner, ['class' => $class] + $options);
    }

    /**
     * Aura. https://daisyui.com/components/aura/
     *
     * Display content with an aura/glow effect.
     *
     * @param string $content Raw HTML content (must wrap exactly one element).
     * @param array<string, mixed> $options `appearance` (dual, rainbow, holo, gold, silver, glow),
     *   `size` (xs, sm, md, lg, xl), `class`; any other key becomes an HTML attribute.
     * @return string
     */
    public function aura(string $content, array $options = []): string
    {
        $class = $this->componentClass('aura', $options);

        return $this->tag('div', $content, ['class' => $class] + $options);
    }
}
