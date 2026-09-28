<?php
/**
 * Shared renderer for the daisyUI flash elements.
 *
 * Message params understood (set via `$this->Flash->success('…', ['params' => [...]])`):
 * - `escape` (bool, default true): false outputs the message as raw HTML.
 * - `class` (string): extra classes on the alert.
 * - `alert` (array): any other `FeedbackHelper::alert()` options, e.g. `['appearance' => 'soft']`.
 * - `dismiss` (bool, default true): add a ✕ that hides the message. No JavaScript: a visually
 *   hidden checkbox (keyboard-focusable, labelled "Dismiss") before the alert is toggled by the
 *   ✕ label, and the class-map key `flash.part.dismissible` (`peer-checked:hidden …`) hides the alert.
 *
 * @var \Cake\View\View $this
 * @var string $message
 * @var array<string, mixed> $params
 * @var string|null $color
 */

use TheMusicDev\DaisyUi\View\ClassMap;
use function Cake\Core\h;
use function Cake\I18n\__;

if (!$this->helpers()->has('Feedback')) {
    $this->loadHelper('Feedback', ['className' => 'TheMusicDev/DaisyUi.Feedback']);
}
$params = (array)($params ?? []);
$escape = ($params['escape'] ?? true) !== false;
$options = (array)($params['alert'] ?? []);
if ($color !== null) {
    $options += ['color' => $color];
}
$class = (string)($params['class'] ?? '');

if (($params['dismiss'] ?? true) === false) {
    echo $this->Feedback->alert((string)$message, ['escape' => $escape, 'class' => $class ?: null] + $options);

    return;
}

$id = 'flash-' . bin2hex(random_bytes(4));
$text = $escape ? h((string)$message) : (string)$message;
$close = '<label for="' . $id . '" class="'
    . h(ClassMap::classes('button.base', 'button.appearance.ghost', 'button.size.sm', 'button.modifier.circle'))
    . '" aria-hidden="true">✕</label>';
$options['class'] = trim(ClassMap::get('flash.part.dismissible') . ' ' . $class);

echo '<div>'
    . '<input type="checkbox" id="' . $id . '" class="' . h(ClassMap::get('flash.part.dismissToggle')) . '"'
    . ' aria-label="' . h(__('Dismiss')) . '">'
    . $this->Feedback->alert('<span>' . $text . '</span>' . $close, ['escape' => false] + $options)
    . '</div>';
