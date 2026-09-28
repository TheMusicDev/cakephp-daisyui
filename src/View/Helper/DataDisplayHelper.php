<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use Cake\View\Helper;

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
}
