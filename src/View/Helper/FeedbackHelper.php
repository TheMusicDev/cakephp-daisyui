<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use Cake\View\Helper;
use InvalidArgumentException;
use TheMusicDev\DaisyUi\View\ClassMap;
use function Cake\Core\h;

/**
 * daisyUI "Feedback" components.
 *
 * @extends \Cake\View\Helper<\Cake\View\View>
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class FeedbackHelper extends Helper
{
    use ComponentTrait;

    protected array $helpers = ['Html'];

    /**
     * Alert. https://daisyui.com/components/alert/
     *
     * Renders `<div role="alert">`; override the role with `'role'`
     * (e.g. `'role' => 'status'`).
     *
     * @param string $text Plain text, escaped unless `'escape' => false`.
     * @param array<string, mixed> $options `color`, `appearance`, `direction`,
     *   `role`, `class`, `escape`; any other key becomes an HTML attribute.
     * @return string
     */
    public function alert(string $text, array $options = []): string
    {
        $class = $this->componentClass('alert', $options);
        $text = $this->content($text, $options);
        $attributes = $options + ['role' => 'alert'];

        return $this->tag('div', $text, ['class' => $class] + $attributes);
    }

    /**
     * Loading. https://daisyui.com/components/loading/
     *
     * Renders `<span class="loading …"></span>` with default `role="status"`
     * and `aria-label="Loading"`. Both are overridable.
     *
     * @param array<string, mixed> $options `appearance`, `size`, `role`,
     *   `aria-label`, `class`; any other key becomes an HTML attribute.
     * @return string
     */
    public function loading(array $options = []): string
    {
        $class = $this->componentClass('loading', $options);
        $defaults = [
            'role' => 'status',
            'aria-label' => 'Loading',
        ];
        $attributes = $options + $defaults;

        return $this->tag('span', '', ['class' => $class] + $attributes);
    }

    /**
     * Progress. https://daisyui.com/components/progress/
     *
     * @param float|int|null $value Progress value; null for indeterminate progress bar.
     * @param array<string, mixed> $options `max` (int|float, default 100), `color`,
     *   `class`; any other key becomes an HTML attribute.
     * @return string
     */
    public function progress(int|float|null $value, array $options = []): string
    {
        $max = $options['max'] ?? 100;
        unset($options['max']);

        $class = $this->componentClass('progress', $options);

        $attributes = ['class' => $class, 'max' => (string)$max] + $options;
        if ($value !== null) {
            $attributes['value'] = (string)$value;
        }

        return $this->tag('progress', '', $attributes);
    }

    /**
     * Radial progress. https://daisyui.com/components/radial-progress/
     *
     * @param float|int $value Progress value (0–100).
     * @param array<string, mixed> $options `text` (string, default "{value}%", plain text escaped),
     *   `diameter` (string, e.g. '12rem'), `thickness` (string, e.g. '2px'),
     *   `role`, `aria-valuenow`, `aria-valuemin`, `aria-valuemax`, `style`,
     *   `class`; any other key becomes an HTML attribute.
     * @return string
     * @throws \InvalidArgumentException if value is outside 0–100.
     */
    public function radialProgress(int|float $value, array $options = []): string
    {
        if ($value < 0 || $value > 100) {
            throw new InvalidArgumentException(
                "Radial progress value must be between 0 and 100, got {$value}.",
            );
        }

        $text = $options['text'] ?? "{$value}%";
        $diameter = $options['diameter'] ?? null;
        $thickness = $options['thickness'] ?? null;
        $userStyle = $options['style'] ?? null;
        unset($options['text'], $options['diameter'], $options['thickness'], $options['style']);

        $class = $this->componentClass('radialProgress', $options);

        $style = "--value:{$value};";
        if ($diameter !== null) {
            $style .= "--size:{$diameter};";
        }
        if ($thickness !== null) {
            $style .= "--thickness:{$thickness};";
        }
        if ($userStyle !== null) {
            $style .= $userStyle;
        }

        $defaults = [
            'role' => 'progressbar',
            'aria-valuenow' => (string)$value,
            'aria-valuemin' => '0',
            'aria-valuemax' => '100',
        ];
        $attributes = $options + $defaults;
        $attributes['style'] = $style;
        $attributes['class'] = $class;

        return $this->tag('div', h($text), $attributes);
    }

    /**
     * Tooltip. https://daisyui.com/components/tooltip/
     *
     * Wraps content in a tooltip. `$content` is raw HTML (normally other helpers' output);
     * callers must escape any user data in it. `$tip` is plain text by default (escaped in the
     * data-tip attribute); with `'escape' => false`, `$tip` is raw HTML rendered as a child.
     *
     * @param string $content Raw HTML, normally other helpers' output.
     * @param string $tip Tooltip text; plain text escaped unless `'escape' => false`.
     * @param array<string, mixed> $options `placement` (top, bottom, left, right),
     *   `alignment` (start, center, end), `color`, `modifier` (open), `class`,
     *   `escape`; any other key becomes an HTML attribute.
     * @return string
     */
    public function tooltip(string $content, string $tip, array $options = []): string
    {
        $escape = $options['escape'] ?? true;
        unset($options['escape']);

        $class = $this->componentClass('tooltip', $options);

        if ($escape) {
            $attributes = ['class' => $class, 'data-tip' => $tip] + $options;

            return $this->tag('div', $content, $attributes);
        }

        $attributes = ['class' => $class] + $options;
        $tipContent = $this->tag('div', $tip, ['class' => ClassMap::get('tooltip.part.content')]);

        return $this->tag('div', $tipContent . $content, $attributes);
    }

    /**
     * Toast. https://daisyui.com/components/toast/
     *
     * A toast is a wrapper that stacks elements in a corner of the page.
     * `$content` is raw HTML (normally alerts or other helpers' output);
     * callers must escape any user data in it.
     *
     * @param string $content Toast content; raw HTML.
     * @param array<string, mixed> $options `placement` (start, center, end, top, middle, bottom; string or array),
     *   `class`; any other key becomes an HTML attribute.
     * @return string
     */
    public function toast(string $content, array $options = []): string
    {
        $class = $this->componentClass('toast', $options);

        return $this->tag('div', $content, ['class' => $class] + $options);
    }

    /**
     * Skeleton. https://daisyui.com/components/skeleton/
     *
     * A skeleton component for showing loading states. With a text option, shows text-style skeleton.
     *
     * @param array<string, mixed> $options `text` (string, escaped, makes a text skeleton),
     *   `class`; any other key becomes an HTML attribute.
     * @return string
     */
    public function skeleton(array $options = []): string
    {
        $class = $this->componentClass('skeleton', $options);
        $text = $options['text'] ?? null;
        unset($options['text']);

        $attributes = ['class' => $class] + $options;

        // If text is given, add skeleton-text class and use the text as content.
        if ($text !== null) {
            $attributes['class'] .= ' ' . ClassMap::get('skeleton.modifier.text');

            return $this->tag('div', h($text), $attributes);
        }

        // No text: add aria-hidden by default (unless overridden)
        if (!isset($attributes['aria-hidden'])) {
            $attributes['aria-hidden'] = 'true';
        }

        return $this->tag('div', '', $attributes);
    }
}
