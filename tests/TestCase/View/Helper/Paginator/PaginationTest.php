<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Paginator;

use ArrayIterator;
use Cake\Core\Configure;
use Cake\Datasource\Paging\PaginatedResultSet;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\PaginatorHelper;

class PaginationTest extends TestCase
{
    private PaginatorHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        Router::reload();
        Router::createRouteBuilder('/')->connect('/{controller}/{action}/*');
        $request = new ServerRequest([
            'url' => '/articles/index',
            'params' => ['plugin' => null, 'controller' => 'Articles', 'action' => 'index', 'pass' => []],
        ]);
        Router::setRequest($request);
        $this->helper = $this->helperOnPage(2, 5);
    }

    /**
     * @param int $page Current page.
     * @param int $pages Page count.
     * @param array<string, mixed> $config Helper config.
     * @return \TheMusicDev\DaisyUi\View\Helper\PaginatorHelper
     */
    private function helperOnPage(int $page, int $pages, array $config = []): PaginatorHelper
    {
        $helper = new PaginatorHelper(new View(Router::getRequest()), $config);
        $helper->setPaginated(new PaginatedResultSet(new ArrayIterator([]), [
            'alias' => 'Articles',
            'scope' => null,
            'count' => 10,
            'totalCount' => $pages * 10,
            'perPage' => 10,
            'limit' => 10,
            'pageCount' => $pages,
            'currentPage' => $page,
            'requestedPage' => $page,
            'start' => ($page - 1) * 10 + 1,
            'end' => $page * 10,
            'hasPrevPage' => $page > 1,
            'hasNextPage' => $page < $pages,
            'sort' => null,
            'direction' => null,
            'sortDefault' => false,
            'directionDefault' => false,
            'completeSort' => [],
        ]));

        return $helper;
    }

    public function testNumberIsJoinItemButton(): void
    {
        $this->assertStringContainsString(
            '<a class="join-item btn" href="/Articles/index?page=3&amp;limit=10">3</a>',
            $this->helper->numbers(),
        );
    }

    public function testCurrentPageIsActiveWithAriaCurrent(): void
    {
        $this->assertStringContainsString(
            '<a class="join-item btn btn-active" aria-current="page">2</a>',
            $this->helper->numbers(),
        );
    }

    public function testPrevAndNextLinks(): void
    {
        $this->assertSame(
            '<a class="join-item btn" rel="prev" href="/Articles/index?limit=10">Prev</a>',
            $this->helper->prev('Prev'),
        );
        $this->assertSame(
            '<a class="join-item btn" rel="next" href="/Articles/index?page=3&amp;limit=10">Next</a>',
            $this->helper->next('Next'),
        );
    }

    public function testDisabledPrevFollowsTheButtonDisabledRule(): void
    {
        $this->assertSame(
            '<a class="join-item btn btn-disabled" tabindex="-1" role="button" aria-disabled="true">Prev</a>',
            $this->helperOnPage(1, 5)->prev('Prev'),
        );
    }

    public function testEllipsisIsHiddenDisabledItem(): void
    {
        $html = $this->helperOnPage(10, 20)->numbers(['first' => 1, 'modulus' => 2]);

        $this->assertStringContainsString(
            '<span class="join-item btn btn-disabled" aria-hidden="true">&hellip;</span>',
            $html,
        );
    }

    public function testLinksWrapsPrevNumbersNextInJoinInsideNav(): void
    {
        $html = $this->helper->links(['class' => 'mt-4', 'id' => 'pager']);

        $this->assertStringStartsWith(
            '<nav aria-label="Pagination"><div class="join mt-4" id="pager"><a class="join-item btn" rel="prev"',
            $html,
        );
        $this->assertStringContainsString('aria-current="page">2</a>', $html);
        $this->assertStringEndsWith('rel="next" href="/Articles/index?page=3&amp;limit=10">»</a></div></nav>', $html);
    }

    public function testLinksCustomTextAndLabel(): void
    {
        $html = $this->helper->links(['prev' => 'Back', 'next' => 'More', 'label' => 'Articles pages']);

        $this->assertStringContainsString('<nav aria-label="Articles pages">', $html);
        $this->assertStringContainsString('>Back</a>', $html);
        $this->assertStringContainsString('>More</a>', $html);
    }

    public function testLinksEscapesText(): void
    {
        $this->assertStringContainsString('&lt;b&gt;', $this->helper->links(['prev' => '<b>']));
    }

    public function testLinksIsEmptyForASinglePage(): void
    {
        $this->assertSame('', $this->helperOnPage(1, 1)->links());
    }

    public function testClassMapOverrideReachesTemplates(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['button.base' => 'btn btn-sm']);
        ClassMap::reset();

        $this->assertStringContainsString(
            '<a class="join-item btn btn-sm" href="/Articles/index?page=3&amp;limit=10">3</a>',
            $this->helperOnPage(2, 5)->numbers(),
        );
    }

    public function testUserTemplatesWin(): void
    {
        $helper = $this->helperOnPage(2, 5, ['templates' => ['number' => '<b>{{text}}</b>']]);

        $this->assertStringContainsString('<b>3</b>', $helper->numbers());
        // Templates the user didn't pass still get the daisyUI version.
        $this->assertStringContainsString('btn-active', $helper->numbers());
    }

    public function testLinksIgnoresEscapeSoAttributesStayEscaped(): void
    {
        $html = $this->helper->links(['data-x' => '"><script>', 'escape' => false]);

        $this->assertStringContainsString('data-x="&quot;&gt;&lt;script&gt;"', $html);
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringNotContainsString('escape=', $html);
        $this->assertStringContainsString('<a class="join-item btn" rel="prev"', $this->helper->links(['escape' => true]));
    }
}
