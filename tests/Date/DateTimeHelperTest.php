<?php
declare(strict_types=1);

namespace YaPro\Helper\Date;

use PHPUnit\Framework\TestCase;

class DateTimeHelperTest extends TestCase
{
    /**
     * @var DateTimeHelper
     */
    private $dateTimeHelper;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->dateTimeHelper = new DateTimeHelper();
    }

    /**
     * @test
     */
    public function create()
    {
        $checkDate = '2017-07-01 23:22:21';
        $this->assertSame($checkDate, $this->dateTimeHelper->create($checkDate)->format('Y-m-d H:i:s'));
    }

    /**
     * @test
     */
    public function getDatesFromStringsArray()
    {
        $dates = [
            '2011-01-01',
            '2011-01-01 15:03:01',
            '2011-01-01T15:03:01.012345Z',
        ];
        $this->assertTrue(is_array($this->dateTimeHelper->getDatesFromStringsArray($dates)));
    }
}