<?php

namespace YaPro\Helper;

use App\Service\ExportCsvPresenter;
use PHPUnit\Framework\TestCase;

class CsvHelperTest extends TestCase
{
    public function testFormatToString(): void
    {
        $sut = new CsvHelper();
        $actual = $sut->formatToString([
            ['cheesecake', 'orange', 'triangle'],
            ['quote"', "with\nline\nbreaks", "whitespace and\ttab"],
        ]);

        $expected = <<<EXPECTED
cheesecake,orange,triangle
"quote""","with
line
breaks","whitespace and\ttab"

EXPECTED;

        $this->assertSame(
            $expected,
            $actual
        );
    }
}
