<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Feedback;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\Helper\FeedbackHelper;

class ToastTest extends TestCase
{
    private FeedbackHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        Configure::delete('DaisyUi');
        $this->helper = new FeedbackHelper(new View());
    }

    public function testDefaultRender(): void
    {
        $html = $this->helper->toast('<div>Message</div>');

        $this->assertSame('<div class="toast"><div>Message</div></div>', $html);
    }

    public function testPlacementStart(): void
    {
        $html = $this->helper->toast('<div>Message</div>', ['placement' => 'start']);

        $this->assertSame('<div class="toast toast-start"><div>Message</div></div>', $html);
    }

    public function testPlacementCenter(): void
    {
        $html = $this->helper->toast('<div>Message</div>', ['placement' => 'center']);

        $this->assertSame('<div class="toast toast-center"><div>Message</div></div>', $html);
    }

    public function testPlacementEnd(): void
    {
        $html = $this->helper->toast('<div>Message</div>', ['placement' => 'end']);

        $this->assertSame('<div class="toast toast-end"><div>Message</div></div>', $html);
    }

    public function testPlacementTop(): void
    {
        $html = $this->helper->toast('<div>Message</div>', ['placement' => 'top']);

        $this->assertSame('<div class="toast toast-top"><div>Message</div></div>', $html);
    }

    public function testPlacementMiddle(): void
    {
        $html = $this->helper->toast('<div>Message</div>', ['placement' => 'middle']);

        $this->assertSame('<div class="toast toast-middle"><div>Message</div></div>', $html);
    }

    public function testPlacementBottom(): void
    {
        $html = $this->helper->toast('<div>Message</div>', ['placement' => 'bottom']);

        $this->assertSame('<div class="toast toast-bottom"><div>Message</div></div>', $html);
    }

    public function testPlacementArray(): void
    {
        $html = $this->helper->toast('<div>Message</div>', ['placement' => ['top', 'end']]);

        $this->assertSame('<div class="toast toast-top toast-end"><div>Message</div></div>', $html);
    }

    public function testClassAppended(): void
    {
        $html = $this->helper->toast('<div>Message</div>', ['class' => 'my-toast']);

        $this->assertSame('<div class="toast my-toast"><div>Message</div></div>', $html);
    }

    public function testAttributePassthrough(): void
    {
        $html = $this->helper->toast('<div>Message</div>', ['id' => 'toast-1', 'data-test' => 'value']);

        $this->assertStringContainsString('id="toast-1"', $html);
        $this->assertStringContainsString('data-test="value"', $html);
    }

    public function testRawHtmlContent(): void
    {
        $html = $this->helper->toast('<alert class="alert-error">Error message</alert>');

        $this->assertStringContainsString('<alert class="alert-error">Error message</alert>', $html);
    }

    public function testUnknownPlacementThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->toast('<div>Message</div>', ['placement' => 'nope']);
    }
}
