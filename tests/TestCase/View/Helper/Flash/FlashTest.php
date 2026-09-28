<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Flash;

use Cake\Http\ServerRequest;
use Cake\Http\Session;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\FlashHelper;

class FlashTest extends TestCase
{
    private ServerRequest $request;

    private FlashHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->loadPlugins(['TheMusicDev/DaisyUi' => []]);
        $this->request = new ServerRequest(['session' => new Session()]);
        $this->helper = new FlashHelper(new View($this->request));
    }

    protected function tearDown(): void
    {
        $this->clearPlugins();
        parent::tearDown();
    }

    public function testNothingToRender(): void
    {
        $this->assertNull($this->helper->render());
    }

    public function testDefaultTypeRendersPlainAlert(): void
    {
        $this->request->getFlash()->set('Hello', ['params' => ['dismiss' => false]]);

        $this->assertSame('<div class="alert" role="alert">Hello</div>', $this->helper->render());
    }

    public function testEachStandardTypeGetsItsColor(): void
    {
        foreach (['success', 'error', 'warning', 'info'] as $type) {
            $this->request->getFlash()->set('Msg', ['element' => $type, 'params' => ['dismiss' => false]]);
        }
        $html = (string)$this->helper->render();

        foreach (['success', 'error', 'info'] as $type) {
            $this->assertStringContainsString('<div class="alert alert-' . $type . '" role="alert">Msg</div>', $html);
        }
        // warning is overridden by the app fixture (see testAppOverrideWins)
        $this->assertStringContainsString('APP WARNING: Msg', $html);
    }

    public function testMessageIsEscapedByDefault(): void
    {
        $this->request->getFlash()->set('<b>x</b>', ['element' => 'success']);

        $this->assertStringContainsString('&lt;b&gt;x&lt;/b&gt;', (string)$this->helper->render());
    }

    public function testEscapeFalseOutputsRawHtml(): void
    {
        $this->request->getFlash()->set('<b>x</b>', ['element' => 'success', 'escape' => false]);

        $this->assertStringContainsString('<b>x</b>', (string)$this->helper->render());
    }

    public function testClassParamIsAppended(): void
    {
        $this->request->getFlash()->set('Hi', ['element' => 'info', 'params' => ['class' => 'mb-4', 'dismiss' => false]]);

        $this->assertSame('<div class="alert alert-info mb-4" role="alert">Hi</div>', $this->helper->render());
    }

    public function testAlertParamPassesOptionsThrough(): void
    {
        $this->request->getFlash()->set('Hi', [
            'element' => 'error',
            'params' => ['alert' => ['appearance' => 'soft', 'role' => 'status'], 'dismiss' => false],
        ]);

        $this->assertSame(
            '<div class="alert alert-error alert-soft" role="status">Hi</div>',
            $this->helper->render(),
        );
    }

    public function testAppOverrideWins(): void
    {
        $this->request->getFlash()->set('Careful', ['element' => 'warning']);

        $this->assertSame('APP WARNING: Careful', $this->helper->render());
    }

    public function testAppOwnElementIsLeftAlone(): void
    {
        $this->request->getFlash()->set('Mine', ['element' => 'custom']);

        $this->assertSame('CUSTOM: Mine', $this->helper->render());
    }

    public function testOtherPluginElementIsLeftAlone(): void
    {
        $this->request->getFlash()->set('Plugin', [
            'element' => 'TheMusicDev/DaisyUi.success',
            'params' => ['dismiss' => false],
        ]);

        // Already plugin-prefixed: rendered as given (the plugin's own success element).
        $this->assertSame('<div class="alert alert-success" role="alert">Plugin</div>', $this->helper->render());
    }

    public function testMessagesAreDismissibleByDefault(): void
    {
        $this->request->getFlash()->set('Saved <now>', ['element' => 'success', 'params' => ['class' => 'mb-4']]);
        $html = (string)$this->helper->render();

        $this->assertMatchesRegularExpression(
            '#^<div><input type="checkbox" id="(flash-[0-9a-f]{8})" class="peer sr-only" aria-label="Dismiss">'
            . '<div class="alert alert-success peer-checked:hidden peer-focus-visible:outline-2 mb-4" role="alert">'
            . '<span>Saved &lt;now&gt;</span>'
            . '<label for="\\1" class="btn btn-ghost btn-sm btn-circle" aria-hidden="true">✕</label>'
            . '</div></div>$#',
            $html,
        );
    }

    public function testEachDismissibleMessageGetsItsOwnId(): void
    {
        $this->request->getFlash()->set('One', ['element' => 'info']);
        $this->request->getFlash()->set('Two', ['element' => 'info']);
        preg_match_all('/id="(flash-[0-9a-f]{8})"/', (string)$this->helper->render(), $ids);

        $this->assertCount(2, array_unique($ids[1]));
    }

    public function testDismissibleKeepsRawHtmlWhenEscapeFalse(): void
    {
        $this->request->getFlash()->set('<b>x</b>', ['element' => 'success', 'escape' => false]);

        $this->assertStringContainsString('<span><b>x</b></span>', (string)$this->helper->render());
    }
}
