<?php
declare(strict_types = 1);

namespace YaPro\Helper\Tests;

use PHPUnit\Framework\TestCase;
use YaPro\Helper\ArrayHelper;

class ArrayHelperTest extends TestCase
{
    /**
     * @var ArrayHelper
     */
    private $arrayHelper;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->arrayHelper = new ArrayHelper();
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
        $this->assertEquals(496, $this->arrayHelper->getIntegerTail('Bob', 123));
        $this->assertEquals(522, $this->arrayHelper->getIntegerTail(123, 456));
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
