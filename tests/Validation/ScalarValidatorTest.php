<?php
declare(strict_types = 1);

namespace YaPro\Helper\Tests\Validation;

use PHPUnit\Framework\TestCase;
use YaPro\Helper\Validation\ScalarValidator;

class ScalarValidatorTest extends TestCase
{
    /**
     * @var ScalarValidator
     */
    private $scalarValidator;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->scalarValidator = new ScalarValidator();
    }

    public function getArrayWithIntegerValuesProvider(): array
    {
        return [
            [
                'expected' => [
                    1 => 1,
                    'two' => 2,
                ],
                'data' => [
                    1 => 1,
                    'two' => '2',
                ],
            ]
        ];
    }

    /**
     * @test
     * @dataProvider getArrayWithIntegerValuesProvider
     * @param array $expected
     * @param array $data
     */
    public function getArrayWithIntegerValues(array $expected, array $data)
    {
        $this->assertSame($expected, $this->scalarValidator->getArrayWithIntegerValues($data));
    }

    /**
     * @test
     */
    public function getArrayWithIntegerValuesException()
    {
        $this->expectException(\TypeError::class);
        $this->scalarValidator->getArrayWithIntegerValues([
            1.2,
        ]);
    }

    public function getIntegerProvider()
    {
        return [
            [
                'expected' => 1,
                'value' => 1,
            ],
            [
                'expected' => 1,
                'value' => '1',
            ],
            [
                'expected' => 1,
                'value' => '01',
            ],
            [
                'expected' => 0,
                'value' => 'false',
                'allowedCharacters' => ['false', 'f', '', null],// example: result from db
            ],
            [
                'expected' => 1234,
                'value' => '+01234',
            ],
            [
                'expected' => 10,
                'value' => '10',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getIntegerProvider
     * @param int $expected
     * @param $value
     * @param array $allowedCharacters
     */
    public function getInteger(int $expected, $value, array $allowedCharacters = [])
    {
        $this->assertSame($expected, $this->scalarValidator->getInteger($value, $allowedCharacters));
    }

    public function getIntegerExceptionProvider()
    {
        return [
            [
                'value' => null,
            ],
            [
                'value' => 1.2,
            ],
            [
                'value' => [],
            ],
            [
                'value' => [[0]],
            ],
            [
                'value' => new \StdClass(),
            ],
            [
                'value' => 'a',
                'allowedCharacters' => ['b'],
            ],
            [
                'value' => '+-01234',
            ],
            [
                'value' => '123foo',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getIntegerExceptionProvider
     * @param $value
     * @param array $allowedCharacters
     */
    public function getIntegerException($value, array $allowedCharacters = [])
    {
        $this->expectException(\TypeError::class);
        $this->scalarValidator->getInteger($value, $allowedCharacters);
    }

    public function getFloatProvider()
    {
        return [
            [
                'value' => 1,
                'expected' => 1,
            ],
            [
                'value' => 1.2,
                'expected' => 1.2,
            ],
            [
                'value' => '1.2',
                'expected' => 1.2,
            ],
            [
                'value' => 's',
                'expected' => 0.0,
                'allowedCharacters' => ['s'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getFloatProvider
     * @param $value
     * @param float $expected
     * @param array|null $allowedCharacters
     */
    public function getFloat($value, float $expected, array $allowedCharacters = [])
    {
        $this->assertSame($expected, $this->scalarValidator->getFloat($value, $allowedCharacters));
    }

    public function getFloatExceptionProvider()
    {
        return [
            [
                'value' => null,
            ],
            [
                'value' => '1,2',
            ],
            [
                'value' => '01.2',
            ],
            [
                'value' => [],
            ],
            [
                'value' => new \StdClass(),
            ],
            [
                'value' => 'string',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getFloatExceptionProvider
     * @param $value
     */
    public function getFloatException($value)
    {
        $this->expectException(\TypeError::class);
        $this->scalarValidator->getFloat($value);
    }

    public function isPatternValidProvider()
    {
        return [
            [
                'expected' => true,
                'value' => '#This#',
            ],
            [
                'expected' => false,
                'value' => 'This',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider isPatternValidProvider
     * @param bool $expected
     * @param string $pattern
     * @return void
     */
    function isPatternValid(bool $expected, $pattern): void
    {
        $this->assertSame($expected, $this->scalarValidator->isPatternValid($pattern));
    }
    
    public function correctBooleanArgumentsProvider(): array
    {
        return [
            ['true', true],
            [false, false],
        ];
    }

    /**
     * @dataProvider correctBooleanArgumentsProvider
     */
    public function testGetBooleanMethodPositive($value, bool $expectedResult): void
    {
        $result = $this->scalarValidator->getBoolean($value);
        $this->assertIsBool($result);
        $this->assertEquals($expectedResult, $result);
    }

    public function incorrectBooleanArgumentsProvider(): array
    {
        return [
            [123],
            ['abc'],
        ];
    }

    /**
     * @dataProvider incorrectBooleanArgumentsProvider
     */
    public function testGetBooleanMethodNegative($value): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->scalarValidator->getBoolean($value);
    }
}
