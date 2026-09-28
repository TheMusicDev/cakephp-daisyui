<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataDisplay;

use Cake\Core\Configure;
use Cake\I18n\Date;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use DateTimeImmutable;
use OutOfBoundsException;
use TheMusicDev\DaisyUi\View\ClassMap;
use TheMusicDev\DaisyUi\View\Helper\DataDisplayHelper;

class TableTest extends TestCase
{
    private DataDisplayHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        ClassMap::reset();
        $this->helper = new DataDisplayHelper(new View());
    }

    public function testArrayRows(): void
    {
        $this->assertSame(
            '<div class="overflow-x-auto"><table class="table">'
            . '<thead><tr><th scope="col">Name</th><th scope="col">Job Title</th></tr></thead>'
            . '<tbody><tr><td>Ann</td><td>Dev</td></tr><tr><td>Bo</td><td>Ops</td></tr></tbody>'
            . '</table></div>',
            $this->helper->table(
                [['name' => 'Ann', 'job_title' => 'Dev'], ['name' => 'Bo', 'job_title' => 'Ops']],
                ['name', 'job_title'],
            ),
        );
    }

    public function testEntitiesWithAssociationPathsAndLabels(): void
    {
        $rows = [new Entity(['title' => 'Hello', 'author' => new Entity(['name' => 'Ann'])])];

        $html = $this->helper->table($rows, ['title', 'author.name' => 'Author']);

        $this->assertStringContainsString('<th scope="col">Title</th><th scope="col">Author</th>', $html);
        $this->assertStringContainsString('<td>Hello</td><td>Ann</td>', $html);
    }

    public function testCellsAndHeadersAreEscaped(): void
    {
        $html = $this->helper->table([['name' => '<b>x</b>']], ['name' => '<i>Name</i>']);

        $this->assertStringContainsString('<th scope="col">&lt;i&gt;Name&lt;/i&gt;</th>', $html);
        $this->assertStringContainsString('<td>&lt;b&gt;x&lt;/b&gt;</td>', $html);
    }

    public function testFormatCallableIsRaw(): void
    {
        $html = $this->helper->table(
            [['status' => 'ok']],
            ['status' => ['label' => 'State', 'format' => fn($value, $row) => '<span class="badge">' . htmlspecialchars($value) . '</span>']],
        );

        $this->assertStringContainsString('<td><span class="badge">ok</span></td>', $html);
    }

    public function testValueTypes(): void
    {
        $html = $this->helper->table(
            [['a' => null, 'b' => true, 'c' => 3, 'd' => new Date('2026-01-02'), 'e' => ['x']]],
            ['a', 'b', 'c', 'd', 'e'],
        );

        $this->assertStringContainsString('<td></td><td>Yes</td><td>3</td><td>', $html);
        $this->assertMatchesRegularExpression('#<td>[^<]*26[^<]*</td>#', $html);
        $this->assertStringContainsString('<td></td></tr>', $html);
    }

    public function testColumnClassAndRowHeader(): void
    {
        $html = $this->helper->table([['n' => 1, 'v' => 2]], ['n', 'v' => ['class' => 'text-right']], ['rowHeader' => true]);

        $this->assertStringContainsString('<th scope="col" class="text-right">V</th>', $html);
        $this->assertStringContainsString('<tr><th scope="row">1</th><td class="text-right">2</td></tr>', $html);
    }

    public function testModifiersSizeCaptionAttributesAndNoWrap(): void
    {
        $html = $this->helper->table([], ['n'], [
            'modifier' => ['zebra', 'pin-rows'],
            'size' => 'sm',
            'class' => 'w-full',
            'caption' => 'People <all>',
            'id' => 't1',
            'wrap' => false,
        ]);

        $this->assertStringStartsWith(
            '<table class="table table-sm table-zebra table-pin-rows w-full" id="t1"><caption>People &lt;all&gt;</caption>',
            $html,
        );
    }

    public function testEmptyMessageSpansAllColumns(): void
    {
        $this->assertStringContainsString(
            '<tbody><tr><td colspan="2">No &lt;rows&gt;</td></tr></tbody>',
            $this->helper->table([], ['a', 'b'], ['empty' => 'No <rows>']),
        );
    }

    public function testEscapeOptionIsIgnored(): void
    {
        $this->assertStringNotContainsString('&lt;table', $this->helper->table([], ['a'], ['escape' => true]));
    }

    public function testWrapperFromClassMap(): void
    {
        Configure::write('DaisyUi.classMapOverrides', ['table.part.wrapper' => 'overflow-x-auto rounded-box']);
        ClassMap::reset();

        $this->assertStringStartsWith('<div class="overflow-x-auto rounded-box">', $this->helper->table([], ['a']));
    }

    public function testUnknownModifierThrows(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->helper->table([], ['a'], ['modifier' => 'nope']);
    }

    public function testNativeDateTimeIsFormatted(): void
    {
        $this->assertStringContainsString(
            '<td>2024-01-02 03:04:05</td>',
            $this->helper->table([['at' => new DateTimeImmutable('2024-01-02 03:04:05')]], ['at']),
        );
    }
}
