<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use Cake\View\Helper;

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

        return $this->Html->tag('div', $text, ['class' => $class] + $attributes);
    }
}
