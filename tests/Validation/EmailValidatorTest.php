<?php
declare(strict_types = 1);

namespace YaPro\Helper\Tests\Validation;

use Generator;
use PHPUnit\Framework\TestCase;
use YaPro\Helper\Validation\EmailValidator;

class EmailValidatorTest extends TestCase
{
    /**
     * @var EmailValidator
     */
    private $emailValidator;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->emailValidator = new EmailValidator();
    }

    public function isContainsEmailProvider(): Generator
    {
        yield [
            'string' => 'my@email.com',
            'expected' => true,
        ];
        yield [
            'string' => 'Вот my@email.com в тексте',
            'expected' => true,
        ];
        yield [
            'string' => 'Вот!my@email.com!в тексте',
            'expected' => true,
        ];
        yield [
            'string' => 'Вот my@127.0.0.1.рф в тексте',
            'expected' => true,
        ];
        yield [
            'string' => 'Вот мой@email.com в тексте',
            'expected' => false, // слева не может быть ру символов
        ];
        yield [
            'string' => 'Вот my@емэйл.com в тексте',
            'expected' => false, // справа не может быть ру символов
        ];
    }

    /**
     * @dataProvider isContainsEmailProvider
     */
    public function test_isContainsEmail(string $string, bool $expected)
    {
        $this->assertSame($expected, $this->emailValidator->isContainsEmail($string));
    }
}
