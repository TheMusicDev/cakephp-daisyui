<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use Cake\View\Helper;
use TheMusicDev\DaisyUi\View\ClassMap;
use function Cake\Core\h;

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

        return $this->Html->tag('span', $text, ['class' => $class] + $options);
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
            $outer .= $this->Html->tag('figure', $this->Html->image($image, ['alt' => $imageAlt]));
        }
        $inner = '';
        if ($title !== null) {
            $inner .= $this->Html->tag('h2', h($title), ['class' => ClassMap::get('card.part.title')]);
        }
        $inner .= $escape ? $this->Html->tag('p', h($body)) : $body;
        if ($actions !== null) {
            $inner .= $this->Html->tag('div', $actions, ['class' => ClassMap::get('card.part.actions')]);
        }
        $outer .= $this->Html->tag('div', $inner, ['class' => ClassMap::get('card.part.body')]);

        return $this->Html->tag('div', $outer, ['class' => $class] + $options);
    }
}
