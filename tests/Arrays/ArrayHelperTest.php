<?php
declare(strict_types=1);

namespace YaPro\Helper\Tests\Arrays;

use PHPUnit\Framework\TestCase;
use YaPro\Helper\Arrays\ArrayHelper;

class ArrayHelperTest extends TestCase
{
    /**
     * @var \YaPro\Helper\ArrayHelper
     */
    private $arrayHelper;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->arrayHelper = new ArrayHelper();
    }
    /**
     * @dataProvider arrayDiffAssocMultidimensionalProvider
     */
    public function testArrayDiffAssocMultidimensional(array $array1, array $array2, array $expected)
    {
        $arrayHelper = new ArrayHelper();
        $result = $arrayHelper->arrayDiffAssocMultidimensional($array1, $array2);
        $this->assertEquals($expected, $result);
    }

    public function arrayDiffAssocMultidimensionalProvider(): array
    {
        return [
            // Case 1: Identical arrays
            'identical_arrays' => [
                ['a' => 1, 'b' => ['c' => 2]],
                ['a' => 1, 'b' => ['c' => 2]],
                []
            ],
            // Case 2: Different scalar values
            'different_scalar_values' => [
                ['a' => 1, 'b' => 2],
                ['a' => 1, 'b' => 3],
                ['b' => 2]
            ],
            // Case 3: Missing key in second array
            'missing_key' => [
                ['a' => 1, 'b' => 2],
                ['a' => 1],
                ['b' => 2]
            ],
            // Case 4: Nested array differences
            'nested_array_differences' => [
                ['a' => ['b' => 1, 'c' => 2]],
                ['a' => ['b' => 1, 'c' => 3]],
                ['a' => ['c' => 2]]
            ],
            // Case 5: Nested array missing in second array
            'nested_array_missing' => [
                ['a' => ['b' => 1]],
                ['a' => []],
                ['a' => ['b' => 1]]
            ],
            // Case 6: Array vs non-array
            'array_vs_non_array' => [
                ['a' => ['b' => 1]],
                ['a' => 42],
                ['a' => ['b' => 1]]
            ],
            // Case 7: Deep nested differences
            'deep_nested_differences' => [
                ['a' => ['b' => ['c' => 1, 'd' => 2]]],
                ['a' => ['b' => ['c' => 1, 'd' => 3]]],
                ['a' => ['b' => ['d' => 2]]]
            ],
            // Case 8: Empty first array
            'empty_first_array' => [
                [],
                ['a' => 1],
                []
            ],
            // Case 9: Empty second array
            'empty_second_array' => [
                ['a' => 1, 'b' => ['c' => 2]],
                [],
                ['a' => 1, 'b' => ['c' => 2]]
            ],
            // Case 10: Mixed types
            'mixed_types' => [
                ['a' => 1, 'b' => ['c' => 2], 'd' => 'text'],
                ['a' => '1', 'b' => ['c' => 3], 'd' => 'text'],
                ['a' => 1, 'b' => ['c' => 2]]
            ]
        ];
    }

    public static function providerFilterIntegers(): array
    {
        return [
            'только целые числа' => [
                [1, 2, 3],
                [1, 2, 3],
            ],
            'строки с целыми' => [
                ['1', '02', '003'],
                [1, 2, 3],
            ],
            'смешанные значения' => [
                [1, '2', '3.0', 'hello', 4.5, '4.5', true, false],
                [1, 2, 3],
            ],
            'с нулем и null' => [
                [0, '0', null],
                [0, 0],
            ],
            'пустой массив' => [
                [],
                [],
            ],
        ];
    }
    
    /**
     * @dataProvider providerFilterIntegers
     */
    public function testFilterIntegers(array $input, array $expected)
    {
        $arrayHelper = new ArrayHelper();
        $this->assertSame($expected, $arrayHelper->filterIntegers($input));
    }

    public function integerTailProvider(): array
    {
        return [
            // value, quantity, expectedLength, expected
            ["user123", 10,         2, 61],   // quantity=10 → хвост из 2 цифр
            ["user123", 100,        3, 761],  // quantity=100 → хвост из 3 цифр
            ["user123", 9999,       4, 7761], // quantity=9999 → хвост из 4 цифр
            ["user123", 12345,      5, 37761],
            ["user123", 123456789,  9, 870737761],
            [123456789, 15,         2, 11],   // int тоже работает
        ];
    }

    /**
     * @dataProvider integerTailProvider
     */
    public function testGetIntegerTail($value, int $quantity, int $expectedLength, int $expected)
    {
        $helper = new ArrayHelper();
        $result = $helper->getIntegerTail($value, $quantity);
        $this->assertSame($expected, $result);
        // Проверяем, что результат — число
        $this->assertIsInt($result);

        // Проверяем, что длина результата соответствует длине quantity
        $this->assertEquals(
            strlen((string)$quantity),
            strlen((string)$result),
            "Длина хвоста должна совпадать с количеством символов quantity"
        );

        // Количество цифр результата не превышает длину quantity
        $this->assertLessThanOrEqual($expectedLength, strlen((string)$result));

        // Дополнительно: число неотрицательное
        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function testMultidimensionalDiffByKeys()
    {
        $first =  ['blue' => 1, 'white' => ['purple' => 4, 'green' => 3], 'red' => 2];
        $second = ['blue' => 6, 'white' => ['yellow' => 7, 'green' => 5], 'red' => 2];
        $this->assertEquals([
            'white' => [
                'purple' => 4,
            ],
        ], $this->arrayHelper->multidimensionalDiffByKeys($first, $second));
    }

    public function testMultidimensionalSortByKeys()
    {
        $array =  [
            4 => 'first',
            'second' => true,
            '3' => 'third',
            0 => [
                'one' => 1,
                2 => 'two',
                'three' => null,
                4 => [],
                'five' => false,
                'six' => [
                    7,
                    null => 6,
                ],
            ],
            'four' => [],
            'five' => false,
        ];
        $expected = [
            0 => [
                2 => 'two',
                4 => [],
                'one' => 1,
                'three' => null,
                'five' => false,
                'six' => [
                    7,
                    null => 6,
                ],
            ],
            '3' => 'third',
            'first',
            'second' => true,
            'four' => [],
            'five' => false,
        ];
        $this->assertEquals($expected, $this->arrayHelper->multidimensionalSortByKeys($array));
    }

    public function test_getIntegerTail()
    {
        $this->assertEquals(415, $this->arrayHelper->getIntegerTail('Bob', 123));
        $this->assertEquals(757, $this->arrayHelper->getIntegerTail(123, 456));
    }

    public function test_getIteration()
    {
        $this->assertEquals('second', $this->arrayHelper->getIteration(4, ['first', 'second', 'third']));
    }

    public function test_fill()
    {
        $data = [];
        $this->arrayHelper->fill($data, 'key1', 'key2', 'keyX', 'value1');
        $this->arrayHelper->fill($data, 'key1', 'key2', 'keyX', 'value2');
        $this->arrayHelper->fill($data, 'key1', 'key3', 'keyX', 'value3');
        $expected = [
            'key1' => [
                'key2' => [
                    // тут keyX ключ, а value1 заменено на value2
                    'keyX' => 'value2'
                ],
                'key3' => [
                    'keyX' => 'value3'
                ]
            ]
        ];
        $this->assertEquals($expected, $data);
    }

    public function test_push()
    {
        $data = [];
        $this->arrayHelper->push($data, 'key1', 'key2', 'keyX', 'value1');
        $this->arrayHelper->push($data, 'key1', 'key2', 'keyX', 'value2');
        $this->arrayHelper->push($data, 'key1', 'key3', 'keyX', 'value3');
        $this->arrayHelper->push($data, 'key1', 'key3', 'keyX', 'value4');
        $expected = [
            'key1' => [
                'key2' => [
                    // тут keyX массив, с value1 и value2
                    'keyX' => [
                        'value1',
                        'value2',
                    ]
                ],
                'key3' => [
                    'keyX' => [
                        'value3',
                        'value4',
                    ]
                ]
            ]
        ];
        $this->assertEquals($expected, $data);
    }
}
