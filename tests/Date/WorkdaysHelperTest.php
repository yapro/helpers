<?php

namespace YaPro\Helper\Tests\Date;

use PHPUnit\Framework\TestCase;
use YaPro\Helper\Date\DateTimeHelper;
use YaPro\Helper\Date\WorkdaysHelper;
use YaPro\Helper\FileHelper;
use YaPro\Helper\LiberatorTrait;

/**
 * @coversDefaultClass \YaPro\Helper\Date\WorkdaysHelper
 */
class WorkdaysHelperTest extends TestCase
{
    use LiberatorTrait;
    /**
     * @var WorkdaysHelper
     */
    private $workdaysHelper;

    /**
     * @var \DateTime
     */
    private $testDate;

    /**
     * @var \DateTime[]
     */
    private $holidays;

    /**
     * @var \DateTime[]
     */
    private $workdays;

    /**
     * @var DateTimeHelper
     */
    private $dateTimeHelper;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->dateTimeHelper = new DateTimeHelper();
        $fixturesFolder = realpath(__DIR__ . '/../../src/Date');
        $fileHelper = new FileHelper();
        $this->holidays = $this->dateTimeHelper->getDatesFromStringsArray(
            explode(PHP_EOL, $fileHelper->getFileContent($fixturesFolder . '/holidays.txt'))
        );
        $this->workdays = $this->dateTimeHelper->getDatesFromStringsArray(
            explode(PHP_EOL, $fileHelper->getFileContent($fixturesFolder . '/workdays.txt'))
        );
        $this->workdaysHelper = new WorkdaysHelper($this->holidays, $this->workdays);
        $this->testDate = $this->dateTimeHelper->create('03.09.2016');
    }

    public function isWorkDayProvider(): array
    {
        $tests = [];
        foreach ($this->holidays as $holiday) {
            $tests[] = [
                'date' => $holiday,
                'expectedResult' => false,
            ];
        }
        foreach ($this->workdays as $workday) {
            $tests[] = [
                'date' => $workday,
                'expectedResult' => true,
            ];
        }
        // standard day off
        $tests[] = [
            'date' => $this->dateTimeHelper->create('27.01.2018'),
            'expectedResult' => false,
        ];
        // standard working day
        $tests[] = [
            'date' => $this->dateTimeHelper->create('01.02.2018'),
            'expectedResult' => true,
        ];
        return $tests;
    }

    /**
     * @dataProvider isWorkDayProvider
     * @covers ::isWorkDay
     * @param \DateTime $date
     * @param bool $expectedResult
     */
    public function testIsWorkDay(\DateTime $date, bool $expectedResult)
    {
        $this->assertSame($expectedResult, $this->workdaysHelper->isWorkDay($date));

    }

    public function getDifferenceInWorkingDaysProvider(): array
    {
        return [
            [
                'firstDate' => $this->testDate,
                'secondDate' => $this->testDate,
                'expectedResult' => 0,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('+1 weekdays'),
                'expectedResult' => 1,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('+2 weekdays'),
                'expectedResult' => 2,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('+3 weekdays'),
                'expectedResult' => 3,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('+4 weekdays'),
                'expectedResult' => 4,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('+5 weekdays'),
                'expectedResult' => 5,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('+6 weekdays'),
                'expectedResult' => 6,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('+7 weekdays'),
                'expectedResult' => 7,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('+8 weekdays'),
                'expectedResult' => 8,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('+9 weekdays'),
                'expectedResult' => 9,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('+10 weekdays'),
                'expectedResult' => 10,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('-1 weekdays'),
                'expectedResult' => 1,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('-2 weekdays'),
                'expectedResult' => 2,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('-3 weekdays'),
                'expectedResult' => 3,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('-4 weekdays'),
                'expectedResult' => 4,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('-5 weekdays'),
                'expectedResult' => 5,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('-6 weekdays'),
                'expectedResult' => 6,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('-7 weekdays'),
                'expectedResult' => 7,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('-8 weekdays'),
                'expectedResult' => 8,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('-9 weekdays'),
                'expectedResult' => 9,
            ],
            [
                'firstDate' => $this->testDate,
                'secondDate' => (clone $this->testDate)->modify('-10 weekdays'),
                'expectedResult' => 10,
            ],
            [
                'firstDate' => (clone $this->testDate)->modify('-1 weekdays'),
                'secondDate' => (clone $this->testDate)->modify('+5 weekdays'),
                'expectedResult' => 6,
            ],
            [
                'firstDate' => $this->dateTimeHelper->create('2017-04-28'),
                'secondDate' => $this->dateTimeHelper->create('2017-05-02'),
                'expectedResult' => 2,
            ],
            [
                'firstDate' => $this->dateTimeHelper->create('2017-04-28'),
                'secondDate' => $this->dateTimeHelper->create('2017-05-10'),
                'expectedResult' => 6,
            ],
            [
                'firstDate' => $this->dateTimeHelper->create('2017-06-09'),
                'secondDate' => $this->dateTimeHelper->create('2017-06-13'),
                'expectedResult' => 2,
            ],
            [
                'firstDate' => $this->dateTimeHelper->create('2017-11-03'),
                'secondDate' => $this->dateTimeHelper->create('2017-11-07'),
                'expectedResult' => 2,
            ],
            [
                'firstDate' => $this->dateTimeHelper->create('2017-12-29'),
                'secondDate' => $this->dateTimeHelper->create('2018-01-09'),
                'expectedResult' => 2,
            ],
            [
                'firstDate' => $this->dateTimeHelper->create('2018-02-22'),
                'secondDate' => $this->dateTimeHelper->create('2018-02-26'),
                'expectedResult' => 2,
            ],
            [
                'firstDate' => $this->dateTimeHelper->create('2018-03-07'),
                'secondDate' => $this->dateTimeHelper->create('2018-03-12'),
                'expectedResult' => 2,
            ],
            [
                'firstDate' => $this->dateTimeHelper->create('2018-04-28'),
                'secondDate' => $this->dateTimeHelper->create('2018-05-03'),
                'expectedResult' => 2,
            ],
            [
                'firstDate' => $this->dateTimeHelper->create('2018-04-28'),
                'secondDate' => $this->dateTimeHelper->create('2018-05-10'),
                'expectedResult' => 6,
            ],
            [
                'firstDate' => $this->dateTimeHelper->create('2018-06-09'),
                'secondDate' => $this->dateTimeHelper->create('2018-06-13'),
                'expectedResult' => 2,
            ],
            [
                'firstDate' => $this->dateTimeHelper->create('2018-11-02'),
                'secondDate' => $this->dateTimeHelper->create('2018-11-06'),
                'expectedResult' => 2,
            ],
            [
                'firstDate' => $this->dateTimeHelper->create('2018-12-29'),
                'secondDate' => $this->dateTimeHelper->create('2018-12-31'),
                'expectedResult' => 1,
            ],
        ];
    }

    /**
     * @dataProvider getDifferenceInWorkingDaysProvider
     * @covers ::getDifferenceInWorkingDays
     * @param \DateTime $firstDate
     * @param \DateTime $secondDate
     * @param $expectedResult
     */
    public function testGetDifferenceInWorkingDays(\DateTime $firstDate, \DateTime $secondDate, $expectedResult)
    {
        $this->assertSame($expectedResult, $this->workdaysHelper->getDifferenceInWorkingDays(
            $firstDate,
            $secondDate
        ));
    }

    public function getDateWithModifiedWorkdaysProvider()
    {
        return [
            [// the same dates
                'date' => $this->testDate,
                'workdays' => 0,
                'expectedDate' => $this->testDate,
            ],
            [// many holidays
                'date' => $this->dateTimeHelper->create('2018-01-01'),
                'workdays' => 1,
                'expectedDate' => $this->dateTimeHelper->create('2018-01-09'),
            ],
            [// date is friday
                'date' => $this->dateTimeHelper->create('2018-01-12'),
                'workdays' => 1,
                'expectedDate' => $this->dateTimeHelper->create('2018-01-15'),
            ],
            [// date is saturday
                'date' => $this->dateTimeHelper->create('2018-01-13'),
                'workdays' => 1,
                'expectedDate' => $this->dateTimeHelper->create('2018-01-15'),
            ],
            [// date is sunday
                'date' => $this->dateTimeHelper->create('2018-01-14'),
                'workdays' => 1,
                'expectedDate' => $this->dateTimeHelper->create('2018-01-15'),
            ],
            [// date and expectedDate are standard workdays
                'date' => $this->dateTimeHelper->create('2018-01-30'),
                'workdays' => 1,
                'expectedDate' => $this->dateTimeHelper->create('2018-01-31'),
            ],
            [// range between date and expectedDate has weekends and one holiday
                'date' => $this->dateTimeHelper->create('2018-02-22'),
                'workdays' => 1,
                'expectedDate' => $this->dateTimeHelper->create('2018-02-26'),
            ],
            [// range between date and expectedDate has weekends and two holidays
                'date' => $this->dateTimeHelper->create('2018-03-07'),
                'workdays' => 1,
                'expectedDate' => $this->dateTimeHelper->create('2018-03-12'),
            ],
            [// saturday is workday and range between date and expectedDate has one weekend and two days off
                'date' => $this->dateTimeHelper->create('2018-04-28'),
                'workdays' => 1,
                'expectedDate' => $this->dateTimeHelper->create('2018-05-03'),
            ],
            [// saturday is workday and range between date and expectedDate has one weekend and two days off
                'date' => $this->dateTimeHelper->create('2018-04-28'),
                'workdays' => 2,
                'expectedDate' => $this->dateTimeHelper->create('2018-05-04'),
            ],
            [// saturday is workday and range between date and expectedDate has three weekends and two days off
                'date' => $this->dateTimeHelper->create('2018-04-28'),
                'workdays' => 3,
                'expectedDate' => $this->dateTimeHelper->create('2018-05-07'),
            ],
            [// saturday is workday and range between date and expectedDate has three weekends and two days off
                'date' => $this->dateTimeHelper->create('2018-04-28'),
                'workdays' => 4,
                'expectedDate' => $this->dateTimeHelper->create('2018-05-08'),
            ],
            [// saturday is workday and range between date and expectedDate has three weekends and three days off
                'date' => $this->dateTimeHelper->create('2018-04-28'),
                'workdays' => 5,
                'expectedDate' => $this->dateTimeHelper->create('2018-05-10'),
            ],
            //--------------- subtracting days ---------------:
            [// many holidays
                'date' => $this->dateTimeHelper->create('2018-01-01'),
                'workdays' => -1,
                'expectedDate' => $this->dateTimeHelper->create('2017-12-29'),
            ],
            [// date is monday, expectedDate is friday
                'date' => $this->dateTimeHelper->create('2018-01-15'),
                'workdays' => -1,
                'expectedDate' => $this->dateTimeHelper->create('2018-01-12'),
            ],
            [// date is sunday, expectedDate is friday
                'date' => $this->dateTimeHelper->create('2018-01-14'),
                'workdays' => -1,
                'expectedDate' => $this->dateTimeHelper->create('2018-01-12'),
            ],
            [// date is saturday, expectedDate is friday
                'date' => $this->dateTimeHelper->create('2018-01-13'),
                'workdays' => -1,
                'expectedDate' => $this->dateTimeHelper->create('2018-01-12'),
            ],
            [// date and expectedDate are standard workdays
                'date' => $this->dateTimeHelper->create('2018-01-31'),
                'workdays' => -1,
                'expectedDate' => $this->dateTimeHelper->create('2018-01-30'),
            ],
            [// date and expectedDate are standard workdays
                'date' => $this->dateTimeHelper->create('2018-01-31'),
                'workdays' => -2,
                'expectedDate' => $this->dateTimeHelper->create('2018-01-29'),
            ],
            [// range between date and expectedDate has weekends and one holiday
                'date' => $this->dateTimeHelper->create('2018-02-26'),
                'workdays' => -1,
                'expectedDate' => $this->dateTimeHelper->create('2018-02-22'),
            ],
            [// range between date and expectedDate has weekends and two holidays
                'date' => $this->dateTimeHelper->create('2018-03-12'),
                'workdays' => -1,
                'expectedDate' => $this->dateTimeHelper->create('2018-03-07'),
            ],
            [// saturday is workday and range between date and expectedDate has one weekend and two days off
                'date' => $this->dateTimeHelper->create('2018-05-03'),
                'workdays' => -1,
                'expectedDate' => $this->dateTimeHelper->create('2018-04-28'),
            ],
            [// saturday is workday and range between date and expectedDate has one weekend and two days off
                'date' => $this->dateTimeHelper->create('2018-05-04'),
                'workdays' => -2,
                'expectedDate' => $this->dateTimeHelper->create('2018-04-28'),
            ],
            [// saturday is workday and range between date and expectedDate has three weekends and two days off
                'date' => $this->dateTimeHelper->create('2018-05-07'),
                'workdays' => -3,
                'expectedDate' => $this->dateTimeHelper->create('2018-04-28'),
            ],
            [// saturday is workday and range between date and expectedDate has three weekends and two days off
                'date' => $this->dateTimeHelper->create('2018-05-08'),
                'workdays' => -4,
                'expectedDate' => $this->dateTimeHelper->create('2018-04-28'),
            ],
            [// saturday is workday and range between date and expectedDate has three weekends and three days off
                'date' => $this->dateTimeHelper->create('2018-05-10'),
                'workdays' => -5,
                'expectedDate' => $this->dateTimeHelper->create('2018-04-28'),
            ],
        ];
    }

    /**
     * @dataProvider getDateWithModifiedWorkdaysProvider
     * @covers ::getDateWithModifiedWorkdays
     * @param \DateTime $date
     * @param int $workdays
     * @param $expectedDate
     */
    public function testGetDateWithModifiedWorkdays(\DateTime $date, int $workdays, \DateTime $expectedDate)
    {
        $this->assertEquals($expectedDate, $this->workdaysHelper->getDateWithModifiedWorkdays(
            $date,
            $workdays
        ));
    }

    public function isWeekendProvider(): array
    {
        return [
            [
                'date' => $this->dateTimeHelper->create('2018-01-19'),
                'isWeekend' => false,
            ],
            [
                'date' => $this->dateTimeHelper->create('2018-01-20'),
                'isWeekend' => true,
            ],
        ];
    }

    /**
     * @dataProvider isWeekendProvider
     * @covers ::isWeekend
     * @param \DateTime $date
     * @param bool $isWeekend
     */
    public function testIsWeekend(\DateTime $date, bool $isWeekend)
    {
        $this->assertSame($isWeekend, $this->callClassMethod($this->workdaysHelper, 'isWeekend', [$date]));
    }
}
