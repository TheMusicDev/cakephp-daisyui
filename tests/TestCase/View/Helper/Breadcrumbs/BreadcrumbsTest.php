<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Breadcrumbs;

use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\BreadcrumbsHelper;

class BreadcrumbsTest extends TestCase
{
    private BreadcrumbsHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        Router::reload();
        $this->helper = new BreadcrumbsHelper(new View());
    }

    public function testRendersDaisyUiMarkup(): void
    {
        $this->helper->add('Home', '/')->add('Docs', '/docs')->add('Current');

        $this->assertSame(
            '<nav class="breadcrumbs" aria-label="Breadcrumb"><ul>'
            . '<li><a href="/">Home</a></li>'
            . '<li><a href="/docs">Docs</a></li>'
            . '<li><span>Current</span></li>'
            . '</ul></nav>',
            $this->helper->render(),
        );
    }

    public function testTitlesAreEscapedByDefault(): void
    {
        $this->helper->add('<script>x</script>', '/');

        $html = $this->helper->render();
        $this->assertStringContainsString('&lt;script&gt;x&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script>', $html);
    }

    public function testEscapeFalseOutputsRawTitle(): void
    {
        $this->helper->add('<b>Home</b>', '/', ['escape' => false]);

        $this->assertStringContainsString('<a href="/"><b>Home</b></a>', $this->helper->render());
    }

    public function testEscapeOptionIsNotRenderedAndAttributesStayEscaped(): void
    {
        $this->helper->add('Home', '/', ['escape' => false, 'data-x' => '"><i>']);

        $html = $this->helper->render();
        $this->assertStringNotContainsString('escape', $html);
        $this->assertStringContainsString('data-x="&quot;&gt;&lt;i&gt;"', $html);
    }

    public function testRenderDoesNotChangeStoredCrumbs(): void
    {
        $this->helper->add('<b>', '/');
        $this->helper->render();

        $this->assertSame('<b>', $this->helper->getCrumbs()[0]['title']);
    }

    public function testUlAttributes(): void
    {
        $this->helper->add('Home', '/');

        $this->assertStringContainsString('<ul class="text-sm">', $this->helper->render(['class' => 'text-sm']));
    }

    public function testLabelConfig(): void
    {
        $helper = new BreadcrumbsHelper(new View(), ['label' => 'You are here']);
        $helper->add('Home', '/');

        $this->assertStringStartsWith('<nav class="breadcrumbs" aria-label="You are here">', $helper->render());
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['breadcrumbs.base' => 'breadcrumbs text-sm']);
        ClassMap::reset();
        $helper = new BreadcrumbsHelper(new View());
        $helper->add('Home', '/');

        $this->assertStringStartsWith('<nav class="breadcrumbs text-sm"', $helper->render());
    }

    public function testUserWrapperTemplateWins(): void
    {
        $helper = new BreadcrumbsHelper(new View(), ['templates' => ['wrapper' => '<ol{{attrs}}>{{content}}</ol>']]);
        $helper->add('Home', '/');

        $this->assertStringStartsWith('<ol>', $helper->render());
    }
}
