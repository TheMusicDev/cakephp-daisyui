<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\Actions;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use InvalidArgumentException;
use OutOfBoundsException;
use PHPUnit\Framework\Attributes\DataProvider;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\ActionsHelper;

class ModalTest extends TestCase
{
    private ActionsHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new ActionsHelper(new View());
    }

    public function testDefaultModal(): void
    {
        $this->assertSame(
            '<dialog id="m1" class="modal"><div class="modal-box"><p>Hi</p>'
            . '<div class="modal-action"><form method="dialog"><button type="submit" class="btn">Close</button></form></div>'
            . '</div></dialog>',
            $this->helper->modal('m1', '<p>Hi</p>'),
        );
    }

    public function testTitleIsEscapedAndLabelsTheDialog(): void
    {
        $html = $this->helper->modal('m1', 'Body', ['title' => 'Delete <b>it</b>?']);

        $this->assertStringStartsWith('<dialog id="m1" class="modal" aria-labelledby="m1-title">', $html);
        $this->assertStringContainsString('<h3 id="m1-title">Delete &lt;b&gt;it&lt;/b&gt;?</h3>Body', $html);
    }

    public function testActionsAreRawAndOutsideTheDialogForm(): void
    {
        $html = $this->helper->modal('m1', 'Body', ['actions' => '<form method="post"><button>Delete</button></form>']);

        $this->assertStringContainsString(
            '<div class="modal-action"><form method="post"><button>Delete</button></form>'
            . '<form method="dialog"><button type="submit" class="btn">Close</button></form></div>',
            $html,
        );
    }

    public function testCloseTextAndNoCloseButton(): void
    {
        $this->assertStringContainsString('>Got &lt;it&gt;</button>', $this->helper->modal('m1', 'x', ['close' => 'Got <it>']));
        $this->assertStringNotContainsString('modal-action', $this->helper->modal('m1', 'x', ['close' => false]));
    }

    public function testBackdropClose(): void
    {
        $this->assertStringEndsWith(
            '<form method="dialog" class="modal-backdrop"><button type="submit">Close</button></form></dialog>',
            $this->helper->modal('m1', 'x', ['backdropClose' => true]),
        );
    }

    public function testPlacementModifierClassAndAttributes(): void
    {
        $this->assertStringStartsWith(
            '<dialog id="m1" class="modal modal-open modal-bottom extra" data-x="1">',
            $this->helper->modal('m1', 'x', ['placement' => 'bottom', 'modifier' => 'open', 'class' => 'extra', 'data-x' => '1']),
        );
    }

    public function testUnknownPlacementThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->modal('m1', 'x', ['placement' => 'nope']);
    }

    public function testTrigger(): void
    {
        $this->assertSame(
            '<button type="button" class="btn btn-primary" onclick="document.getElementById(&#039;edit-user&#039;).showModal()"'
            . ' aria-haspopup="dialog">Edit &lt;me&gt;</button>',
            $this->helper->modalTrigger('Edit <me>', 'edit-user', ['color' => 'primary']),
        );
    }

    public function testTriggerIgnoresUrl(): void
    {
        $this->assertStringStartsWith('<button', $this->helper->modalTrigger('Open', 'm1', ['url' => '/x']));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function badIds(): array
    {
        return [
            'quote' => ["m1');alert(1);//"],
            'starts with digit' => ['1m'],
            'space' => ['my modal'],
            'empty' => [''],
        ];
    }

    #[DataProvider('badIds')]
    public function testInvalidIdsThrow(string $id): void
    {
        try {
            $this->helper->modal($id, 'x');
            $this->fail('modal() accepted ' . $id);
        } catch (InvalidArgumentException) {
        }
        $this->expectException(InvalidArgumentException::class);
        $this->helper->modalTrigger('Open', $id);
    }
}
