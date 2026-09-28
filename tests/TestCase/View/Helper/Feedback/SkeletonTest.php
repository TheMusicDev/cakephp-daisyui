<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Feedback;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\FeedbackHelper;

class SkeletonTest extends TestCase
{
    private FeedbackHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new FeedbackHelper(new View());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        ClassMap::reset();
    }

    public function testDefault(): void
    {
        $result = $this->helper->skeleton();

        $this->assertStringContainsString('class="skeleton', $result);
        $this->assertStringContainsString('aria-hidden="true"', $result);
    }

    public function testWithText(): void
    {
        $result = $this->helper->skeleton(['text' => 'Loading data...']);

        $this->assertStringContainsString('skeleton-text', $result);
        $this->assertStringContainsString('Loading data...', $result);
    }

    public function testTextEscaped(): void
    {
        $result = $this->helper->skeleton(['text' => '<b>Loading</b>']);

        $this->assertStringContainsString('&lt;b&gt;Loading&lt;/b&gt;', $result);
    }

    public function testWithClass(): void
    {
        $result = $this->helper->skeleton(['class' => 'h-32 w-32']);

        $this->assertStringContainsString('h-32 w-32', $result);
    }

    public function testAttributePassthrough(): void
    {
        $result = $this->helper->skeleton(['id' => 'my-skeleton']);

        $this->assertStringContainsString('id="my-skeleton"', $result);
    }

    public function testAriaHiddenOverridable(): void
    {
        $result = $this->helper->skeleton(['aria-hidden' => 'false']);

        $this->assertStringContainsString('aria-hidden="false"', $result);
    }

    public function testWithTextNoAriaHidden(): void
    {
        $result = $this->helper->skeleton(['text' => 'Loading...']);

        $this->assertStringNotContainsString('aria-hidden', $result);
    }

    public function testClassMapOverride(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['skeleton.base' => 'skeleton custom']);
        ClassMap::reset();

        $result = $this->helper->skeleton();

        $this->assertStringContainsString('class="skeleton custom', $result);
    }
}
