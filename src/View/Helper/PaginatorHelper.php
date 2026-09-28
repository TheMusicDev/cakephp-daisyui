<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use Cake\View\Helper\PaginatorHelper as CorePaginatorHelper;
use TheMusicDev\DaisyUi\View\ClassMap;
use function Cake\Core\h;

/**
 * daisyUI-styled Paginator helper. https://daisyui.com/components/pagination/
 *
 * `prev()`, `next()`, `first()`, `last()` and `numbers()` render daisyUI
 * `join-item btn` links (classes from the class map); `links()` wraps
 * prev + numbers + next in a `join`. Templates you pass in the helper config
 * win over these defaults.
 */
class PaginatorHelper extends CorePaginatorHelper
{
    /**
     * @param array<string, mixed> $config Helper config.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $item = h(ClassMap::classes('joinItem.base', 'button.base'));
        $active = h(ClassMap::classes('joinItem.base', 'button.base', 'button.behavior.active'));
        $disabled = h(ClassMap::classes('joinItem.base', 'button.base', 'button.behavior.disabled'));
        // Per the daisyUI Button rule, class-disabled items get tabindex/role/aria-disabled.
        $disabledAttrs = 'class="' . $disabled . '" tabindex="-1" role="button" aria-disabled="true"';

        $templates = [
            'nextActive' => '<a class="' . $item . '" rel="next" href="{{url}}">{{text}}</a>',
            'nextDisabled' => '<a ' . $disabledAttrs . '>{{text}}</a>',
            'prevActive' => '<a class="' . $item . '" rel="prev" href="{{url}}">{{text}}</a>',
            'prevDisabled' => '<a ' . $disabledAttrs . '>{{text}}</a>',
            'first' => '<a class="' . $item . '" href="{{url}}">{{text}}</a>',
            'last' => '<a class="' . $item . '" href="{{url}}">{{text}}</a>',
            'number' => '<a class="' . $item . '" href="{{url}}">{{text}}</a>',
            'current' => '<a class="' . $active . '" aria-current="page">{{text}}</a>',
            'ellipsis' => '<span class="' . $disabled . '" aria-hidden="true">&hellip;</span>',
        ];
        $userTemplates = $config['templates'] ?? [];
        if (is_array($userTemplates)) {
            $templates = array_diff_key($templates, $userTemplates);
        }
        $this->setTemplates($templates);
    }

    /**
     * Prev + page numbers + next inside a daisyUI `join`, wrapped in a
     * `<nav>` landmark. Returns '' when there is only one page.
     *
     * @param array<string, mixed> $options `prev` (string, default '«'), `next`
     *   (string, default '»'), `numbers` (array, options for `numbers()`, e.g.
     *   `['first' => 1, 'last' => 1]`), `label` (string, the nav's aria-label,
     *   default 'Pagination'), `class` (appended to the join); any other key
     *   becomes an attribute on the join `<div>`.
     * @return string
     */
    public function links(array $options = []): string
    {
        if ($this->total() <= 1) {
            return '';
        }
        $prev = (string)($options['prev'] ?? '«');
        $next = (string)($options['next'] ?? '»');
        $numbers = (array)($options['numbers'] ?? []);
        $label = (string)($options['label'] ?? 'Pagination');
        $class = trim(ClassMap::get('join.base') . ' ' . implode(' ', (array)($options['class'] ?? [])));
        // `escape` in Html->tag() would escape our markup, and `false` would turn off attribute escaping.
        unset($options['prev'], $options['next'], $options['numbers'], $options['label']);
        unset($options['class'], $options['escape']);

        $items = $this->prev($prev) . $this->numbers($numbers) . $this->next($next);

        return $this->Html->tag(
            'nav',
            $this->Html->tag('div', $items, ['class' => $class] + $options),
            ['aria-label' => $label],
        );
    }
}
