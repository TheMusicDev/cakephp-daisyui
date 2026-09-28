<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View;

use Cake\View\View;
use TheMusicDev\DaisyUi\View\DaisyUiViewTrait;

class DaisyUiTestView extends View
{
    use DaisyUiViewTrait;

    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->loadDaisyUi();
    }
}
