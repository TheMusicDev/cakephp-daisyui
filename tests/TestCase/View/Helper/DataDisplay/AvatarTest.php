<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class AvatarTest extends TestCase
{
    private DataDisplayHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new DataDisplayHelper(new View());
    }

    public function testImageAvatarDefault(): void
    {
        $this->assertHtml(
            [
                ['div' => ['class' => 'avatar']],
                ['div' => []],
                ['img' => ['src' => '/img/avatar.jpg', 'alt' => '']],
                '/div',
                '/div',
            ],
            $this->helper->avatar('/img/avatar.jpg'),
        );
    }

    public function testImageAvatarWithAlt(): void
    {
        $result = $this->helper->avatar('/img/avatar.jpg', ['alt' => 'User Avatar']);
        $this->assertStringContainsString('alt="User Avatar"', $result);
    }

    public function testPlaceholderAvatarEscaped(): void
    {
        $this->assertSame(
            '<div class="avatar avatar-placeholder"><div><span>&lt;b&gt;</span></div></div>',
            $this->helper->avatar(null, ['placeholder' => '<b>']),
        );
    }

    public function testPlaceholderAvatarDefault(): void
    {
        $this->assertHtml(
            [
                ['div' => ['class' => 'avatar avatar-placeholder']],
                ['div' => []],
                ['span' => []],
                '/span',
                '/div',
                '/div',
            ],
            $this->helper->avatar(null),
        );
    }

    public function testInnerClassOnInnerDiv(): void
    {
        $result = $this->helper->avatar('/img/a.jpg', ['innerClass' => 'w-16 rounded-full']);
        $this->assertStringContainsString('<div class="w-16 rounded-full">', $result);
    }

    public function testInnerClassAsArray(): void
    {
        $result = $this->helper->avatar('/img/a.jpg', ['innerClass' => ['w-16', 'rounded-full']]);
        $this->assertStringContainsString('rounded-full', $result);
    }

    public function testOnlineModifier(): void
    {
        $result = $this->helper->avatar('/img/a.jpg', ['modifier' => 'online']);
        $this->assertStringContainsString('avatar-online', $result);
    }

    public function testOfflineModifier(): void
    {
        $result = $this->helper->avatar('/img/a.jpg', ['modifier' => 'offline']);
        $this->assertStringContainsString('avatar-offline', $result);
    }

    public function testPlaceholderModifierAutoAdded(): void
    {
        $result = $this->helper->avatar(null, ['placeholder' => 'AM']);
        $this->assertStringContainsString('avatar-placeholder', $result);
    }

    public function testPlaceholderModifierNotDoubled(): void
    {
        $result = $this->helper->avatar(null, ['placeholder' => 'AM', 'modifier' => 'placeholder']);
        // Count occurrences of 'avatar-placeholder' — should be only one
        $count = substr_count($result, 'avatar-placeholder');
        $this->assertSame(1, $count);
    }

    public function testMultipleModifiers(): void
    {
        $result = $this->helper->avatar('/img/a.jpg', ['modifier' => ['online', 'placeholder']]);
        $this->assertStringContainsString('avatar-online', $result);
        $this->assertStringContainsString('avatar-placeholder', $result);
    }

    public function testClassAppended(): void
    {
        $result = $this->helper->avatar('/img/a.jpg', ['class' => 'extra']);
        $this->assertStringContainsString('class="avatar extra"', $result);
    }

    public function testAttributePassthrough(): void
    {
        $result = $this->helper->avatar('/img/a.jpg', ['id' => 'av1', 'data-test' => 'value']);
        $this->assertStringContainsString('id="av1"', $result);
        $this->assertStringContainsString('data-test="value"', $result);
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['avatar.base' => 'avatar custom']);
        ClassMap::reset();

        $result = $this->helper->avatar('/img/a.jpg');
        $this->assertStringContainsString('class="avatar custom"', $result);
    }

    public function testUnknownModifierThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->avatar('/img/a.jpg', ['modifier' => 'nope']);
    }

    public function testAvatarGroupDefault(): void
    {
        $content = $this->helper->avatar('/img/a.jpg') . $this->helper->avatar('/img/b.jpg');
        $result = $this->helper->avatarGroup($content);
        // Just verify the structure starts correctly
        $this->assertStringContainsString('<div class="avatar-group">', $result);
        $this->assertStringContainsString('<img src="/img/a.jpg"', $result);
        $this->assertStringContainsString('<img src="/img/b.jpg"', $result);
    }

    public function testAvatarGroupClassAppended(): void
    {
        $result = $this->helper->avatarGroup('<div>content</div>', ['class' => 'extra']);
        $this->assertStringContainsString('class="avatar-group extra"', $result);
    }

    public function testAvatarGroupRawContent(): void
    {
        $result = $this->helper->avatarGroup('<a href="/buy">Buy</a>');
        $this->assertStringContainsString('<a href="/buy">Buy</a>', $result);
    }

    public function testAvatarGroupAttributePassthrough(): void
    {
        $result = $this->helper->avatarGroup('<div>x</div>', ['id' => 'ag1']);
        $this->assertStringContainsString('id="ag1"', $result);
    }
}
