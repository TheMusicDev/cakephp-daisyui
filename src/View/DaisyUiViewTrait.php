<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View;

/**
 * Use in AppView and call loadDaisyUi() from initialize().
 */
trait DaisyUiViewTrait
{
    /**
     * Registers every DaisyUi helper; core Form/Paginator/Flash/Breadcrumbs are replaced.
     *
     * @return void
     */
    public function loadDaisyUi(): void
    {
        $helpers = [
            'Actions', 'DataDisplay', 'DataInput', 'Feedback', 'Layout', 'Mockup', 'Navigation',
            'Assets', 'Form', 'Paginator', 'Flash', 'Breadcrumbs',
        ];
        foreach ($helpers as $helper) {
            $this->addHelper('TheMusicDev/DaisyUi.' . $helper);
        }
    }
}
