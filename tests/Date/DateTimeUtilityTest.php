<?php

use YaPro\Helper\Date\DateTimeUtility;

/**
 * @coversDefaultClass \YaPro\Helper\Date\DateTimeUtility
 */
class DateTimeUtilityTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var DateTime
     */
    private $testDate;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->testDate = new \DateTime('03.09.2016');
    }

    public function getDifferenceInWorkingDaysProvider()
    {
        return [
            [
                'date' => $this->testDate,
                'expectedResult' => 0,
            ],
            [
                'date' => (clone $this->testDate)->modify('+1 weekdays'),
                'expectedResult' => 1,
            ],
            [
                'date' => (clone $this->testDate)->modify('+2 weekdays'),
                'expectedResult' => 2,
            ],
            [
                'date' => (clone $this->testDate)->modify('+3 weekdays'),
                'expectedResult' => 3,
            ],
            [
                'date' => (clone $this->testDate)->modify('+4 weekdays'),
                'expectedResult' => 4,
            ],
            [
                'date' => (clone $this->testDate)->modify('+5 weekdays'),
                'expectedResult' => 5,
            ],
            [
                'date' => (clone $this->testDate)->modify('+6 weekdays'),
                'expectedResult' => 6,
            ],
            [
                'date' => (clone $this->testDate)->modify('+7 weekdays'),
                'expectedResult' => 7,
            ],
            [
                'date' => (clone $this->testDate)->modify('+8 weekdays'),
                'expectedResult' => 8,
            ],
            [
                'date' => (clone $this->testDate)->modify('+9 weekdays'),
                'expectedResult' => 9,
            ],
            [
                'date' => (clone $this->testDate)->modify('+10 weekdays'),
                'expectedResult' => 10,
            ],
            [
                'date' => (clone $this->testDate)->modify('-1 weekdays'),
                'expectedResult' => 1,
            ],
            [
                'date' => (clone $this->testDate)->modify('-2 weekdays'),
                'expectedResult' => 2,
            ],
            [
                'date' => (clone $this->testDate)->modify('-3 weekdays'),
                'expectedResult' => 3,
            ],
            [
                'date' => (clone $this->testDate)->modify('-4 weekdays'),
                'expectedResult' => 4,
            ],
            [
                'date' => (clone $this->testDate)->modify('-5 weekdays'),
                'expectedResult' => 5,
            ],
            [
                'date' => (clone $this->testDate)->modify('-6 weekdays'),
                'expectedResult' => 6,
            ],
            [
                'date' => (clone $this->testDate)->modify('-7 weekdays'),
                'expectedResult' => 7,
            ],
            [
                'date' => (clone $this->testDate)->modify('-8 weekdays'),
                'expectedResult' => 8,
            ],
            [
                'date' => (clone $this->testDate)->modify('-9 weekdays'),
                'expectedResult' => 9,
            ],
            [
                'date' => (clone $this->testDate)->modify('-10 weekdays'),
                'expectedResult' => 10,
            ],
        ];
    }

    /**
     * @dataProvider getDifferenceInWorkingDaysProvider
     * @covers ::getDifferenceInWorkingDays
     * @param DateTime $date
     * @param $expectedResult
     */
    public function testGetDifferenceInWorkingDays(\DateTime $date, $expectedResult)
    {
        $this->assertEquals($expectedResult, DateTimeUtility::getDifferenceInWorkingDays(
            $this->testDate, 
            $date
        ));
    }
}