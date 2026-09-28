<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View;

use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use TheMusicDev\DaisyUi\View\Helper\ActionsHelper;
use TheMusicDev\DaisyUi\View\Helper\AssetsHelper;
use TheMusicDev\DaisyUi\View\Helper\BreadcrumbsHelper;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;
use TheMusicDev\DaisyUi\View\Helper\DataInputHelper;
use TheMusicDev\DaisyUi\View\Helper\FeedbackHelper;
use TheMusicDev\DaisyUi\View\Helper\FlashHelper;
use TheMusicDev\DaisyUi\View\Helper\FormHelper;
use TheMusicDev\DaisyUi\View\Helper\LayoutHelper;
use TheMusicDev\DaisyUi\View\Helper\MockupHelper;
use TheMusicDev\DaisyUi\View\Helper\NavigationHelper;
use TheMusicDev\DaisyUi\View\Helper\PaginatorHelper;

class DaisyUiViewTraitTest extends TestCase
{
    /**
     * @return list<list<string>>
     */
    public static function helperProvider(): array
    {
        return [
            ['Actions', ActionsHelper::class],
            ['DataDisplay', DataDisplayHelper::class],
            ['DataInput', DataInputHelper::class],
            ['Feedback', FeedbackHelper::class],
            ['Layout', LayoutHelper::class],
            ['Mockup', MockupHelper::class],
            ['Navigation', NavigationHelper::class],
            ['Assets', AssetsHelper::class],
            ['Form', FormHelper::class],
            ['Paginator', PaginatorHelper::class],
            ['Flash', FlashHelper::class],
            ['Breadcrumbs', BreadcrumbsHelper::class],
        ];
    }

    #[DataProvider('helperProvider')]
    public function testLoadDaisyUiRegistersHelpers(string $alias, string $expected): void
    {
        $view = new DaisyUiTestView();
        $view->loadHelpers();

        $this->assertInstanceOf($expected, $view->helpers()->get($alias));
    }
}
