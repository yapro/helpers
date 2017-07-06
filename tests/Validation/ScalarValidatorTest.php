<?php
declare(strict_types = 1);

namespace YaPro\Helper\Validation;

use PHPUnit\Framework\TestCase;

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
     * @expectedException \TypeError
     */
    public function getArrayWithIntegerValuesException()
    {
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
                'allowedCharacters' => [],
            ],
            [
                'expected' => 1,
                'value' => '1',
                'allowedCharacters' => [],
            ],
            [
                'expected' => 0,
                'value' => 'false',
                'allowedCharacters' => ['false', 'f', '', null],// example: result from db
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
    public function getInteger(int $expected, $value, array $allowedCharacters)
    {
        $this->assertSame($expected, $this->scalarValidator->getInteger($value, $allowedCharacters));
    }


    public function getIntegerExceptionProvider()
    {
        return [
            [
                'value' => null,
                'allowedCharacters' => [],
            ],
            [
                'value' => 1.2,
                'allowedCharacters' => [],
            ],
            [
                'value' => '01',
                'allowedCharacters' => [],
            ],
            [
                'value' => [],
                'allowedCharacters' => [],
            ],
            [
                'value' => new \StdClass(),
                'allowedCharacters' => [],
            ],
            [
                'value' => 'a',
                'allowedCharacters' => ['b'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getIntegerExceptionProvider
     * @expectedException \TypeError
     * @param $value
     * @param array $allowedCharacters
     */
    public function getIntegerException($value, array $allowedCharacters)
    {
        $this->scalarValidator->getInteger($value, $allowedCharacters);
    }

    public function getFloatProvider()
    {
        return [
            [
                'expected' => 1,
                'value' => 1,
            ],
            [
                'expected' => 1.2,
                'value' => 1.2,
            ],
            [
                'expected' => 1.2,
                'value' => '1.2',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getFloatProvider
     * @param float $expected
     * @param $value
     */
    public function getFloat(float $expected, $value)
    {
        $this->assertSame($expected, $this->scalarValidator->getFloat($value));
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
     * @expectedException \TypeError
     * @param $value
     */
    public function getFloatException($value)
    {
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
     * @return bool
     */
    function isPatternValid(bool $expected, $pattern)
    {
        $this->assertSame($expected, $this->scalarValidator->isPatternValid($pattern));
    }
}