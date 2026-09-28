<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Actions;

use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\Helper\ActionsHelper;

class ButtonTest extends TestCase
{
    private ActionsHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        Configure::delete('DaisyUi');
        $this->helper = new ActionsHelper(new View());
    }

    public function testDefaultRender(): void
    {
        $this->assertSame(
            '<button type="button" class="btn">Save</button>',
            $this->helper->button('Save'),
        );
    }

    public function testTypeOverride(): void
    {
        $html = $this->helper->button('Go', ['type' => 'submit']);

        $this->assertStringContainsString('<button type="submit" class="btn">Go</button>', $html);
    }

    public function testEveryOptionGroupMapsToItsClass(): void
    {
        $html = $this->helper->button('Save', [
            'color' => 'primary',
            'size' => 'lg',
            'appearance' => 'outline',
            'modifier' => 'block',
            'behavior' => 'active',
        ]);

        $this->assertSame(
            '<button type="button" class="btn btn-primary btn-lg btn-outline btn-block btn-active">Save</button>',
            $html,
        );
    }

    public function testEachOptionGroupAlone(): void
    {
        $cases = [
            [['color' => 'error'], 'btn-error'],
            [['appearance' => 'ghost'], 'btn-ghost'],
            [['behavior' => 'active'], 'btn-active'],
            [['size' => 'xs'], 'btn-xs'],
            [['modifier' => 'circle'], 'btn-circle'],
        ];
        foreach ($cases as [$options, $class]) {
            $html = $this->helper->button('Save', $options);

            $this->assertStringContainsString('class="btn ' . $class . '"', $html);
        }
    }

    public function testUnknownOptionValueThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->expectExceptionMessage('Unknown DaisyUi class map key "button.color.purple"');
        $this->helper->button('Save', ['color' => 'purple']);
    }

    public function testClassIsAppended(): void
    {
        $html = $this->helper->button('Save', ['class' => 'my-btn']);

        $this->assertSame(
            '<button type="button" class="btn my-btn">Save</button>',
            $html,
        );
    }

    public function testOtherKeysBecomeHtmlAttributes(): void
    {
        $html = $this->helper->button('Save', ['id' => 'save-btn', 'data-action' => 'save']);

        $this->assertSame(
            '<button type="button" class="btn" id="save-btn" data-action="save">Save</button>',
            $html,
        );
    }

    public function testTextIsEscapedByDefault(): void
    {
        $html = $this->helper->button('Save & <b>exit</b>');

        $this->assertSame(
            '<button type="button" class="btn">Save &amp; &lt;b&gt;exit&lt;/b&gt;</button>',
            $html,
        );
    }

    public function testEscapeFalsePassesRawHtml(): void
    {
        $html = $this->helper->button('<b>Go</b>', ['escape' => false]);

        $this->assertSame('<button type="button" class="btn"><b>Go</b></button>', $html);
    }

    public function testUrlRendersLinkWithoutType(): void
    {
        $html = $this->helper->button('Docs', ['url' => '/docs']);

        $this->assertSame('<a href="/docs" class="btn">Docs</a>', $html);
    }

    public function testUrlWithEscapedTextDoesNotDoubleEscape(): void
    {
        $html = $this->helper->button('Save & <b>exit</b>', ['url' => '/docs']);

        $this->assertSame('<a href="/docs" class="btn">Save &amp; &lt;b&gt;exit&lt;/b&gt;</a>', $html);
    }

    public function testDisabledButton(): void
    {
        $html = $this->helper->button('Save', ['behavior' => 'disabled']);

        $this->assertSame(
            '<button type="button" class="btn btn-disabled" tabindex="-1" role="button"'
            . ' aria-disabled="true" disabled="disabled">Save</button>',
            $html,
        );
    }

    public function testDisabledLinkHasNoDisabledAttribute(): void
    {
        $html = $this->helper->button('Docs', ['url' => '/docs', 'behavior' => 'disabled']);

        $this->assertSame(
            '<a href="/docs" class="btn btn-disabled" tabindex="-1" role="button"'
            . ' aria-disabled="true">Docs</a>',
            $html,
        );
    }
}
