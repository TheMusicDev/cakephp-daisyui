<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class ChatTest extends TestCase
{
    private DataDisplayHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new DataDisplayHelper(new View());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        ClassMap::reset();
    }

    public function testDefault(): void
    {
        $html = $this->helper->chat('Hello');
        $this->assertStringContainsString('class="chat chat-start"', $html);
        $this->assertStringContainsString('chat-bubble', $html);
        $this->assertStringContainsString('Hello', $html);
    }

    public function testPlacementStart(): void
    {
        $html = $this->helper->chat('Message', ['placement' => 'start']);
        $this->assertStringContainsString('chat-start', $html);
    }

    public function testPlacementEnd(): void
    {
        $html = $this->helper->chat('Message', ['placement' => 'end']);
        $this->assertStringContainsString('chat-end', $html);
    }

    public function testColorNeutral(): void
    {
        $html = $this->helper->chat('Message', ['color' => 'neutral']);
        $this->assertStringContainsString('chat-bubble-neutral', $html);
    }

    public function testColorPrimary(): void
    {
        $html = $this->helper->chat('Message', ['color' => 'primary']);
        $this->assertStringContainsString('chat-bubble-primary', $html);
    }

    public function testColorSecondary(): void
    {
        $html = $this->helper->chat('Message', ['color' => 'secondary']);
        $this->assertStringContainsString('chat-bubble-secondary', $html);
    }

    public function testColorAccent(): void
    {
        $html = $this->helper->chat('Message', ['color' => 'accent']);
        $this->assertStringContainsString('chat-bubble-accent', $html);
    }

    public function testColorInfo(): void
    {
        $html = $this->helper->chat('Message', ['color' => 'info']);
        $this->assertStringContainsString('chat-bubble-info', $html);
    }

    public function testColorSuccess(): void
    {
        $html = $this->helper->chat('Message', ['color' => 'success']);
        $this->assertStringContainsString('chat-bubble-success', $html);
    }

    public function testColorWarning(): void
    {
        $html = $this->helper->chat('Message', ['color' => 'warning']);
        $this->assertStringContainsString('chat-bubble-warning', $html);
    }

    public function testColorError(): void
    {
        $html = $this->helper->chat('Message', ['color' => 'error']);
        $this->assertStringContainsString('chat-bubble-error', $html);
    }

    public function testWithImage(): void
    {
        $html = $this->helper->chat('Message', ['image' => '<img src="avatar.png" alt="Avatar">']);
        $this->assertStringContainsString('chat-image', $html);
        $this->assertStringContainsString('avatar', $html);
        $this->assertStringContainsString('<img src="avatar.png" alt="Avatar">', $html);
    }

    public function testWithHeader(): void
    {
        $html = $this->helper->chat('Message', ['header' => 'John Doe']);
        $this->assertStringContainsString('chat-header', $html);
        $this->assertStringContainsString('John Doe', $html);
    }

    public function testWithFooter(): void
    {
        $html = $this->helper->chat('Message', ['footer' => 'Just now']);
        $this->assertStringContainsString('chat-footer', $html);
        $this->assertStringContainsString('Just now', $html);
    }

    public function testWithAll(): void
    {
        $html = $this->helper->chat('Hello World', [
            'placement' => 'end',
            'color' => 'primary',
            'image' => '<img src="avatar.png" alt="Me">',
            'header' => 'Me',
            'footer' => '1 min ago',
        ]);
        $this->assertStringContainsString('chat-end', $html);
        $this->assertStringContainsString('chat-bubble-primary', $html);
        $this->assertStringContainsString('avatar', $html);
        $this->assertStringContainsString('Me', $html);
        $this->assertStringContainsString('1 min ago', $html);
    }

    public function testEscapesByDefault(): void
    {
        $this->assertStringContainsString('&lt;b&gt;', $this->helper->chat('<b>'));
    }

    public function testEscapeFalse(): void
    {
        $html = $this->helper->chat('<b>bold</b>', ['escape' => false]);
        $this->assertStringContainsString('<b>bold</b>', $html);
    }

    public function testHeaderEscaped(): void
    {
        $html = $this->helper->chat('Message', ['header' => '<b>Bold</b>']);
        $this->assertStringContainsString('&lt;b&gt;', $html);
        $this->assertStringNotContainsString('<b>Bold</b>', $html);
    }

    public function testFooterEscaped(): void
    {
        $html = $this->helper->chat('Message', ['footer' => '<b>Bold</b>']);
        $this->assertStringContainsString('&lt;b&gt;', $html);
        $this->assertStringNotContainsString('<b>Bold</b>', $html);
    }

    public function testComponentClassAppended(): void
    {
        $html = $this->helper->chat('Message', ['class' => 'extra-class', 'id' => 'chat-1']);
        $this->assertStringContainsString('class="chat chat-start extra-class"', $html);
        $this->assertStringContainsString('id="chat-1"', $html);
    }

    public function testAttributePassthrough(): void
    {
        $html = $this->helper->chat('Message', ['data-foo' => 'bar', 'id' => 'chat-1']);
        $this->assertStringContainsString('data-foo="bar"', $html);
        $this->assertStringContainsString('id="chat-1"', $html);
    }

    public function testUnknownPlacementThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->chat('Message', ['placement' => 'nope']);
    }

    public function testUnknownColorThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->chat('Message', ['color' => 'nope']);
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['chat.placement.start' => 'chat-start custom']);
        ClassMap::reset();

        $html = $this->helper->chat('Message', ['placement' => 'start']);
        $this->assertStringContainsString('chat-start custom', $html);
    }
}
