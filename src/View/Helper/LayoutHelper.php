<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use Cake\View\Helper;

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
}
