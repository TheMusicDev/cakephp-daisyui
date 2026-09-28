<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Navigation;

use BadMethodCallException;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use TheMusicDev\DaisyUi\View\Helper\NavigationHelper;

class RedirectTest extends TestCase
{
    private NavigationHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->helper = new NavigationHelper(new View());
    }

    public function testBreadcrumbsThrowsPointingToBreadcrumbsHelper(): void
    {
        try {
            $this->helper->breadcrumbs();
            $this->fail('Expected BadMethodCallException');
        } catch (BadMethodCallException $e) {
            $this->assertStringContainsString('$this->Breadcrumbs', $e->getMessage());
        }
    }

    public function testPaginationThrowsPointingToPaginatorHelper(): void
    {
        try {
            $this->helper->pagination();
            $this->fail('Expected BadMethodCallException');
        } catch (BadMethodCallException $e) {
            $this->assertStringContainsString('$this->Paginator', $e->getMessage());
        }
    }
}
