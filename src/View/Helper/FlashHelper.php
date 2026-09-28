<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use Cake\View\Helper\FlashHelper as CoreFlashHelper;
use function Cake\Core\pluginSplit;

/**
 * daisyUI-styled Flash helper. https://daisyui.com/components/alert/
 *
 * Renders the standard flash types (`default`, `success`, `error`, `warning`,
 * `info`) with this plugin's elements, which use `FeedbackHelper::alert()`.
 * Other elements (e.g. your own `flash/custom`) render unchanged. To restyle
 * a standard type, override it in
 * `templates/plugin/TheMusicDev/DaisyUi/element/flash/<type>.php`.
 */
class FlashHelper extends CoreFlashHelper
{
    /**
     * Flash types this plugin ships an element for.
     */
    private const ELEMENTS = ['default', 'success', 'error', 'warning', 'info'];

    /**
     * Same as the core method, but standard flash types use this plugin's elements.
     *
     * @param string $key The flash key, as set by `FlashComponent`.
     * @param array<string, mixed> $options Merged into each message (e.g. `params`).
     * @return string|null
     */
    public function render(string $key = 'flash', array $options = []): ?string
    {
        $messages = $this->_View->getRequest()->getFlash()->consume($key);
        if ($messages === null) {
            return null;
        }

        $out = '';
        foreach ($messages as $message) {
            $message = $options + $message;
            $out .= $this->_View->element($this->element((string)$message['element']), $message);
        }

        return $out;
    }

    /**
     * Maps `flash/<type>` (no plugin) to this plugin's element for the standard types.
     *
     * @param string $element Element name from the flash message.
     * @return string
     */
    private function element(string $element): string
    {
        [$plugin, $name] = pluginSplit($element);
        $type = str_starts_with($name, 'flash/') ? substr($name, 6) : null;
        if ($plugin === null && in_array($type, self::ELEMENTS, true)) {
            return 'TheMusicDev/DaisyUi.' . $name;
        }

        return $element;
    }
}
