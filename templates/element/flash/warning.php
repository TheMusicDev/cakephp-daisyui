<?php
/**
 * Flash "warning" message as a daisyUI alert. Override in your app at
 * templates/plugin/TheMusicDev/DaisyUi/element/flash/warning.php.
 *
 * @var \Cake\View\View $this
 * @var string $message
 * @var array<string, mixed> $params
 */
echo $this->element('TheMusicDev/DaisyUi.flash/_alert', ['message' => $message, 'params' => $params ?? [], 'color' => 'warning']);
