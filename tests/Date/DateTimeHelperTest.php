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
        $checkDate = '2017-07-01 23:59:59';
        $result = $this->dateTimeHelper->create($checkDate);
        $this->assertSame($checkDate, $result->format('Y-m-d H:i:s'));
        $this->assertTrue($result instanceof \DateTime);
    }

    /**
     * @test
     * @expectedException \UnexpectedValueException
     */
    public function createException()
    {
        $checkDate = '1999-04-31';
        $this->dateTimeHelper->create($checkDate);
    }

    /**
     * @test
     */
    public function createImmutable()
    {
        $checkDate = '2017-07-01 23:59:59';
        $result = $this->dateTimeHelper->createImmutable($checkDate);
        $this->assertSame($checkDate, $result->format('Y-m-d H:i:s'));
        $this->assertTrue($result instanceof \DateTimeImmutable);
    }

    /**
     * @test
     * @expectedException \UnexpectedValueException
     */
    public function createImmutableException()
    {
        $checkDate = '1999-04-31';
        $this->dateTimeHelper->createImmutable($checkDate);
    }

    /**
     * @test
     */
    public function getMonthlyIntervals()
    {
        $startDate = new \DateTimeImmutable('2010-12-31 23:59:59');
        $endDate = new \DateTimeImmutable('2011-02-01 00:00:00');
        $expected = [
            [
                'firstDay' => new \DateTimeImmutable('2010-12-01 00:00:00'),
                'lastDay' => new \DateTimeImmutable('2010-12-31 23:59:59'),
            ],[
                'firstDay' => new \DateTimeImmutable('2011-01-01 00:00:00'),
                'lastDay' => new \DateTimeImmutable('2011-01-31 23:59:59'),
            ],[
                'firstDay' => new \DateTimeImmutable('2011-02-01 00:00:00'),
                'lastDay' => new \DateTimeImmutable('2011-02-28 23:59:59'),
            ]
        ];
        $this->assertEquals($expected, $this->dateTimeHelper->getMonthlyIntervals($startDate, $endDate));
    }

    /**
     * @test
     */
    public function getIntervalDays()
    {
        $startDate = new \DateTimeImmutable('2010-10-01');
        $secondDate = new \DateTimeImmutable('2010-10-02');
        $endDate = new \DateTimeImmutable('2010-10-03');
        $this->assertEquals([
            $startDate,
            $secondDate,
            $endDate
        ], $this->dateTimeHelper->getIntervalDays($startDate, $endDate));
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

    public function getNumberDaysDiffProvider()
    {
        return [
            [
                'expected' => 0,
                'date1' => '2018-12-29',
                'date2' => '2018-12-29',
            ],
            [
                'expected' => 1,
                'date1' => '2018-12-29',
                'date2' => '2018-12-30',
            ],
            [
                'expected' => 2,
                'date1' => '2018-12-29',
                'date2' => '2018-12-31',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getNumberDaysDiffProvider
     * @param int $expected
     * @param string $date1
     * @param string $date2
     */
    public function getNumberDaysDiff(int $expected, string $date1, string $date2)
    {
        $date1 = new \DateTimeImmutable($date1);
        $date2 = new \DateTimeImmutable($date2);
        $this->assertSame($expected, $this->dateTimeHelper->getNumberDaysDiff($date1, $date2));
    }

    public function getDateInTheDatesRangeProvider(): array
    {
        return [
            [
                'expected' => new \DateTimeImmutable('2010-10-11'),
                'date' => '2010-10-11',
                'minDate' => '2010-10-10',
                'maxDate' => '2010-10-12',
            ],
            [
                'expected' => null,
                'date' => '2010-10-09',
                'minDate' => '2010-10-10',
                'maxDate' => '2010-10-12',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getDateInTheDatesRangeProvider
     * @param mixed $expected
     * @param string $date
     * @param string $minDate
     * @param string $maxDate
     */
    public function getDateInTheDatesRange($expected = null, string $date, string $minDate, string $maxDate)
    {
        $this->assertEquals($expected, $this->dateTimeHelper->getDateInTheDatesRange($date, $minDate, $maxDate));
    }
}