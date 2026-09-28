<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\Test\TestCase\View\Helper\DataInput;

use BadMethodCallException;
use Cake\TestSuite\TestCase;
use Cake\View\View;
use PHPUnit\Framework\Attributes\DataProvider;
use TheMusicDev\DaisyUi\View\Helper\DataInputHelper;

class RedirectTest extends TestCase
{
    private DataInputHelper $helper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->helper = new DataInputHelper(new View());
    }

    /**
     * @return list<list<string>>
     */
    public static function methodProvider(): array
    {
        return [
            ['calendar'],
            ['checkbox'],
            ['fieldset'],
            ['fileInput'],
            ['filter'],
            ['label'],
            ['radio'],
            ['range'],
            ['rating'],
            ['select'],
            ['input'],
            ['textarea'],
            ['toggle'],
            ['validator'],
            ['otp'],
        ];
    }

    #[DataProvider('methodProvider')]
    public function testMethodThrowsPointingToFormHelper(string $method): void
    {
        try {
            $this->helper->{$method}();
            $this->fail('Expected BadMethodCallException');
        } catch (BadMethodCallException $e) {
            $this->assertStringContainsString('$this->Form->control(', $e->getMessage());
        }
    }

    public function testCalendarPointsToNativeDateInput(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('"type" => "date"');
        $this->helper->calendar();
    }
}
