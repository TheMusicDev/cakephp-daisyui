<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Layout;

use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\LayoutHelper;

class FooterTest extends TestCase
{
    private LayoutHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        Router::reload();
        $this->helper = new LayoutHelper(new View());
    }

    public function testRawContent(): void
    {
        $this->assertSame('<footer class="footer"><p>© <b>me</b></p></footer>', $this->helper->footer('<p>© <b>me</b></p>'));
    }

    public function testSections(): void
    {
        $html = $this->helper->footer([
            ['title' => 'Company <Inc>', 'links' => ['About' => '/about', 'Jobs & more' => '/jobs']],
            ['title' => 'Legal', 'links' => [['text' => 'Terms', 'url' => '/terms']], 'content' => '<small>raw</small>'],
        ]);

        $this->assertSame(
            '<footer class="footer">'
            . '<nav><h6 class="footer-title">Company &lt;Inc&gt;</h6>'
            . '<a href="/about" class="link link-hover">About</a><a href="/jobs" class="link link-hover">Jobs &amp; more</a></nav>'
            . '<nav><h6 class="footer-title">Legal</h6><a href="/terms" class="link link-hover">Terms</a><small>raw</small></nav>'
            . '</footer>',
            $html,
        );
    }

    public function testModifiersClassAndAttributes(): void
    {
        $this->assertStringStartsWith(
            '<footer class="footer footer-horizontal footer-center bg-base-200" id="f">',
            $this->helper->footer('x', ['placement' => 'center', 'direction' => 'horizontal', 'class' => 'bg-base-200', 'id' => 'f']),
        );
    }

    public function testEscapeOptionIgnored(): void
    {
        $this->assertStringContainsString('<p>', $this->helper->footer('<p>x</p>', ['escape' => true]));
    }

    public function testUnknownValueThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->footer('x', ['direction' => 'nope']);
    }
}
