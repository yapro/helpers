<?php
declare(strict_types = 1);

namespace YaPro\Helper;

use PHPUnit\Framework\TestCase;

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

    /**
     * @test
     */
    public function multidimensionalDiffByKeys()
    {
        $first =  ['blue' => 1, 'white' => ['purple' => 4, 'green' => 3], 'red' => 2];
        $second = ['blue' => 6, 'white' => ['yellow' => 7, 'green' => 5], 'red' => 2];
        $this->assertEquals([
            'white' => [
                'purple' => 4,
            ],
        ], $this->arrayHelper->multidimensionalDiffByKeys($first, $second));
    }
}