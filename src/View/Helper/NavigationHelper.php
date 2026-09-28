<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use BadMethodCallException;
use Cake\View\Helper;

/**
 * daisyUI "Navigation" components.
 *
 * @extends \Cake\View\Helper<\Cake\View\View>
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class NavigationHelper extends Helper
{
    use ComponentTrait;

    protected array $helpers = ['Html'];

    /**
     * Not implemented: breadcrumbs are owned by the framework's BreadcrumbsHelper.
     *
     * @return never
     * @throws \BadMethodCallException Always; see the message for the owning helper.
     */
    public function breadcrumbs(): never
    {
        throw new BadMethodCallException(
            'Use the Breadcrumbs helper instead: $this->Breadcrumbs->add() / $this->Breadcrumbs->render().',
        );
    }

    /**
     * Not implemented: pagination is owned by the framework's PaginatorHelper.
     *
     * @return never
     * @throws \BadMethodCallException Always; see the message for the owning helper.
     */
    public function pagination(): never
    {
        throw new BadMethodCallException(
            'Use the Paginator helper instead: $this->Paginator->links() and related methods.',
        );
    }
}
